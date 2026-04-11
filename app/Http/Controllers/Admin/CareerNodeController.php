<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerNode;
use Illuminate\Support\Facades\Storage;
use App\Models\CareerLink;

class CareerNodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $careerNodes = CareerNode::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('specialization', 'like', "%{$search}%")
                        ->orWhereRaw("JSON_SEARCH(subjects, 'one', CONCAT('%', ?, '%')) IS NOT NULL", [$search])
                        ->orWhereRaw("JSON_SEARCH(career_options, 'one', CONCAT('%', ?, '%')) IS NOT NULL", [$search]);
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('admin.manage_career', compact('careerNodes', 'search'));
    }

//     public function index(Request $request)
// {
//     $search = $request->input('search');

//     $careerLinks = CareerLink::with(['parent', 'child'])
//         ->when($search, function ($query) use ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->whereHas('parent', fn($q) => $q->where('title', 'like', "%{$search}%"))
//                   ->orWhereHas('child', fn($q) => $q->where('title', 'like', "%{$search}%"));
//             });
//         })
//         ->latest()
//         ->paginate(10)
//         ->withQueryString(); 

//     $careerNodes = CareerNode::orderBy('title')->get();

//     return view('admin.career_path_link', compact('careerLinks', 'careerNodes', 'search'));
// }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('admin.career_creation');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'title'          => 'required|unique:career_nodes,title',
        'description'    => 'required',
        'subjects'       => 'nullable|array',
        'subjects.*'     => 'nullable|string',
        'career_options' => 'required|array',
        'career_options.*' => 'required|string',
        'video'          => ['nullable', 'url', 'regex:/(?:youtube\.com.*v=|youtu\.be\/)([^&#]+)/'],
        'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:3072',
        'specialization' => 'nullable|string',
        'newgen_course'  => 'required|boolean',
    ]);

    // Ensure at least one media type is provided
    if (!$request->filled('video') && !$request->hasFile('thumbnail')) {
        return back()->withErrors([
            'video' => 'Please provide a YouTube video URL or upload a thumbnail image.',
        ])->withInput();
    }

    $videoId = null;

    if ($request->filled('video')) {
        $videoId = $this->extractYoutubeId($request->video);

        if (!$videoId) {
            return back()->withErrors(['video' => 'Invalid YouTube URL.'])->withInput();
        }
    }

    $career = new CareerNode();
    $career->title          = $request->title;
    $career->description    = $request->description;
    $career->subjects       = json_encode(array_filter($request->subjects ?? []));
    $career->career_options = json_encode($request->career_options);
    $career->specialization = $request->specialization;
    $career->newgen_course  = $request->boolean('newgen_course');
    $career->video          = $videoId ?? '';
    $career->thumbnail      = '';

    if ($request->hasFile('thumbnail')) {
        $career->thumbnail = $request->file('thumbnail')->store('career_thumbnails', 'public');
    }

    $career->save();

    return redirect()->route('admin.career_nodes.index')
        ->with('success', 'Career created successfully.');
}

    private function extractYoutubeId($url)
{
    $parsedUrl = parse_url($url);

    // youtube.com/watch?v=VIDEO_ID
    if (isset($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $query);
        if (isset($query['v'])) {
            return $query['v'];
        }
    }

    // youtu.be/VIDEO_ID
    if (isset($parsedUrl['host']) && $parsedUrl['host'] === 'youtu.be') {
        return ltrim($parsedUrl['path'], '/');
    }

    return null;
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $career = CareerNode::findOrFail($id);

    $request->validate([
        'title'            => 'required|unique:career_nodes,title,' . $career->id,
        'description'      => 'required',
        'subjects'         => 'nullable|array',
        'subjects.*'       => 'nullable|string',
        'career_options'   => 'required|array',
        'career_options.*' => 'required|string',
        'video'            => ['nullable', 'url', 'regex:/(?:youtube\.com.*v=|youtu\.be\/)([^&#]+)/'],
        'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:3072',
        'specialization'   => 'nullable|string',
        'remove_thumbnail' => 'nullable|string',
        'newgen_course'    => 'required|boolean',
    ]);

    $videoId   = $career->video;
    $thumbnail = $career->thumbnail;

    // Determine if there's still a valid media after the update
    $willHaveVideo     = $request->filled('video');
    $willHaveNewThumb  = $request->hasFile('thumbnail');
    $willRemoveThumb   = $request->input('remove_thumbnail') === '1';
    $willKeepOldThumb  = $thumbnail && !$willRemoveThumb && !$willHaveVideo;

    if (!$willHaveVideo && !$willHaveNewThumb && !$willKeepOldThumb) {
        return back()->withErrors([
            'video' => 'Please provide a YouTube video URL or upload a thumbnail image.',
        ])->withInput();
    }

    if ($request->filled('video')) {
        $videoId = $this->extractYoutubeId($request->video);

        if (!$videoId) {
            return back()->withErrors(['video' => 'Invalid YouTube URL.'])->withInput();
        }

        // Remove old thumbnail if switching to video
        if ($thumbnail && Storage::exists('public/' . $thumbnail)) {
            Storage::delete('public/' . $thumbnail);
        }
        $thumbnail = null;

    } else {
        $videoId = null;
    }

    if ($request->hasFile('thumbnail')) {
        if ($thumbnail && Storage::exists('public/' . $thumbnail)) {
            Storage::delete('public/' . $thumbnail);
        }

        $thumbnail = $request->file('thumbnail')->store('career_thumbnails', 'public');
        $videoId   = null;

    } elseif ($willRemoveThumb) {
        if ($thumbnail && Storage::exists('public/' . $thumbnail)) {
            Storage::delete('public/' . $thumbnail);
        }
        $thumbnail = null;
    }

    $career->title          = $request->title;
    $career->description    = $request->description;
    $career->subjects       = json_encode(array_filter($request->subjects ?? []));
    $career->career_options = json_encode($request->career_options);
    $career->video          = $videoId ?? '';
    $career->thumbnail      = $thumbnail ?? '';
    $career->specialization = $request->specialization;
    $career->newgen_course  = $request->boolean('newgen_course');

    $career->save();

    return redirect()->route('admin.career_nodes.index')
        ->with('success', 'Career updated successfully.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $career = CareerNode::findOrFail($id);

        // Delete associated video file
        if ($career->video && Storage::exists('public/' . $career->video)) {
            Storage::delete('public/' . $career->video);
        }

        // Delete associated thumbnail file
        if ($career->thumbnail && Storage::exists('public/' . $career->thumbnail)) {
            Storage::delete('public/' . $career->thumbnail);
        }

        $career->delete();

        return redirect()->route('admin.career_nodes.index')
                         ->with('success', 'Career Deleted Successfully');
    }
}
