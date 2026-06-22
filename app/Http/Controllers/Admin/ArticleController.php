<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;
use Illuminate\Support\Facades\Http;


class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index()
    {
        $articles = Article::latest()->get();
        return view('admin.admin_article', compact('articles'));
    }

    /**
     * Store a newly uploaded article.
     */

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'file'  => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:20480',
    ]);

    // Store uploaded file temporarily
    $tempPath = $request->file('file')->store('temp', 'public');
    $localFile = storage_path('app/public/' . $tempPath);

    $mimeType = $request->file('file')->getMimeType();

    // If already a PDF, skip conversion
    if ($mimeType === 'application/pdf') {

        $pdfName = uniqid() . '.pdf';

        Storage::disk('public')->copy(
            $tempPath,
            'articles/' . $pdfName
        );

        Storage::disk('public')->delete($tempPath);

    } else {

        try {

            // Create CloudConvert Job
            $job = Http::withoutVerifying()
                ->withToken(env('CLOUDCONVERT_API_KEY'))
                ->post('https://api.cloudconvert.com/v2/jobs', [
                    'tasks' => [
                        'import-file' => [
                            'operation' => 'import/upload',
                        ],
                        'convert-file' => [
                            'operation'     => 'convert',
                            'input'         => 'import-file',
                            'output_format' => 'pdf',
                        ],
                        'export-file' => [
                            'operation' => 'export/url',
                            'input'     => 'convert-file',
                        ],
                    ],
                ]);

            if (!$job->successful()) {
                Storage::disk('public')->delete($tempPath);

                return back()->with(
                    'error',
                    'Failed to upload the document (your daily limit exceeded!!).'
                );
            }

            $jobData = $job->json();

            $uploadTask = collect($jobData['data']['tasks'])
                ->firstWhere('name', 'import-file');

            if (!$uploadTask) {
                Storage::disk('public')->delete($tempPath);

                return back()->with(
                    'error',
                    'CloudConvert upload task not found.'
                );
            }

            // Upload file to CloudConvert
            Http::withoutVerifying()
                ->asMultipart()
                ->post(
                    $uploadTask['result']['form']['url'],
                    array_merge(
                        collect($uploadTask['result']['form']['parameters'])
                            ->map(fn ($value, $key) => [
                                'name'     => $key,
                                'contents' => $value,
                            ])
                            ->values()
                            ->toArray(),
                        [
                            [
                                'name'     => 'file',
                                'contents' => fopen($localFile, 'r'),
                                'filename' => basename($localFile),
                            ],
                        ]
                    )
                );

            // Wait for conversion
            sleep(10);

            $jobId = $jobData['data']['id'];

            $completedJob = Http::withoutVerifying()
                ->withToken(env('CLOUDCONVERT_API_KEY'))
                ->get("https://api.cloudconvert.com/v2/jobs/{$jobId}");

            if (!$completedJob->successful()) {
                Storage::disk('public')->delete($tempPath);

                return back()->with(
                    'error',
                    'Failed to fetch conversion status.'
                );
            }

            $completedData = $completedJob->json();

            $exportTask = collect($completedData['data']['tasks'])
                ->firstWhere('name', 'export-file');

            if (
                !$exportTask ||
                !isset($exportTask['result']['files'][0]['url'])
            ) {
                Storage::disk('public')->delete($tempPath);

                return back()->with(
                    'error',
                    'PDF conversion failed.'
                );
            }

            $pdfUrl = $exportTask['result']['files'][0]['url'];

            $pdfContent = file_get_contents($pdfUrl);

            if (!$pdfContent) {
                Storage::disk('public')->delete($tempPath);

                return back()->with(
                    'error',
                    'Unable to download converted PDF.'
                );
            }

            $pdfName = uniqid() . '.pdf';

            Storage::disk('public')->put(
                'articles/' . $pdfName,
                $pdfContent
            );

            // Delete temporary uploaded file
            Storage::disk('public')->delete($tempPath);

        } catch (\Exception $e) {

            Storage::disk('public')->delete($tempPath);

            return back()->with(
                'error',
                'Conversion failed: ' . $e->getMessage()
            );
        }
    }

    // Save article record
    Article::create([
        'title'     => $request->title,
        'file_path' => 'articles/' . $pdfName,
    ]);

    return redirect()
        ->route('admin.articles.index')
        ->with(
            'success',
            'Article uploaded successfully.'
        );
}

    /**
     * Delete the specified article.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        Storage::disk('public')->delete($article->file_path);

        $article->delete();

        return redirect()
            ->route('admin.articles.index')  // ← fixed
            ->with('success', 'Article deleted successfully.');
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);

        $path = storage_path('app/public/' . $article->file_path);

        return response()->make(
            file_get_contents($path),
            200,
            [
                'Content-Type' => mime_content_type($path),
                'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            ]
        );
    }
    
    public function edit(string $id)
{
    $article = Article::findOrFail($id);
    return response()->json([
        'id'    => $article->id,
        'title' => $article->title,
    ]);
}
public function update(Request $request, string $id)
{
    $article = Article::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'file'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:20480',
    ]);

    if ($request->hasFile('file')) {

        $tempPath  = $request->file('file')->store('temp', 'public');
        $localFile = storage_path('app/public/' . $tempPath);
        $mimeType  = $request->file('file')->getMimeType();

        if ($mimeType === 'application/pdf') {

            $pdfName = uniqid() . '.pdf';
            Storage::disk('public')->copy($tempPath, 'articles/' . $pdfName);
            Storage::disk('public')->delete($tempPath);

        } else {

            try {

                $job = Http::withoutVerifying()
                    ->withToken(env('CLOUDCONVERT_API_KEY'))
                    ->post('https://api.cloudconvert.com/v2/jobs', [
                        'tasks' => [
                            'import-file'  => ['operation' => 'import/upload'],
                            'convert-file' => [
                                'operation'     => 'convert',
                                'input'         => 'import-file',
                                'output_format' => 'pdf',
                            ],
                            'export-file'  => [
                                'operation' => 'export/url',
                                'input'     => 'convert-file',
                            ],
                        ],
                    ]);

                if (!$job->successful()) {
                    Storage::disk('public')->delete($tempPath);
                    return back()->with('error', 'Failed to upload the document (daily limit exceeded).');
                }

                $jobData    = $job->json();
                $uploadTask = collect($jobData['data']['tasks'])->firstWhere('name', 'import-file');

                if (!$uploadTask) {
                    Storage::disk('public')->delete($tempPath);
                    return back()->with('error', 'CloudConvert upload task not found.');
                }

                Http::withoutVerifying()
                    ->asMultipart()
                    ->post(
                        $uploadTask['result']['form']['url'],
                        array_merge(
                            collect($uploadTask['result']['form']['parameters'])
                                ->map(fn ($v, $k) => ['name' => $k, 'contents' => $v])
                                ->values()->toArray(),
                            [['name' => 'file', 'contents' => fopen($localFile, 'r'), 'filename' => basename($localFile)]]
                        )
                    );

                sleep(10);

                $completedJob = Http::withoutVerifying()
                    ->withToken(env('CLOUDCONVERT_API_KEY'))
                    ->get("https://api.cloudconvert.com/v2/jobs/{$jobData['data']['id']}");

                if (!$completedJob->successful()) {
                    Storage::disk('public')->delete($tempPath);
                    return back()->with('error', 'Failed to fetch conversion status.');
                }

                $exportTask = collect($completedJob->json()['data']['tasks'])->firstWhere('name', 'export-file');

                if (!$exportTask || !isset($exportTask['result']['files'][0]['url'])) {
                    Storage::disk('public')->delete($tempPath);
                    return back()->with('error', 'PDF conversion failed.');
                }

                $pdfContent = file_get_contents($exportTask['result']['files'][0]['url']);

                if (!$pdfContent) {
                    Storage::disk('public')->delete($tempPath);
                    return back()->with('error', 'Unable to download converted PDF.');
                }

                $pdfName = uniqid() . '.pdf';
                Storage::disk('public')->put('articles/' . $pdfName, $pdfContent);
                Storage::disk('public')->delete($tempPath);

            } catch (\Exception $e) {
                Storage::disk('public')->delete($tempPath);
                return back()->with('error', 'Conversion failed: ' . $e->getMessage());
            }
        }

        Storage::disk('public')->delete($article->file_path);
        $article->file_path = 'articles/' . $pdfName;
    }

    $article->title = $request->title;
    $article->save();

    return redirect()
    ->route('admin.articles.index')
    ->with('success', 'Article updated successfully.');
}
}
