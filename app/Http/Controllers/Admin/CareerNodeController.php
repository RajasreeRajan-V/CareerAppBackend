<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerNode;
use Illuminate\Support\Facades\Storage;

class CareerNodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $careerNodes = CareerNode::latest()->paginate(5);
        return view('admin.manage_career', compact('careerNodes'));
    }

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
        'video'          => ['nullable', 'url', 'regex:/(?:youtube\.com.*v=|youtu\.be\/)([^&#]+)/'],
        'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        'specialization' => 'nullable|string',
    ]);

    $videoId = null;

    if ($request->filled('video')) {
        $videoId = $this->extractYoutubeId($request->video);

        if (!$videoId) {
            return back()->withErrors(['video' => 'Invalid YouTube URL'])->withInput();
        }
    }

    if (!$request->filled('video') && !$request->hasFile('thumbnail')) {
        return back()->withErrors([
            'video' => 'Upload either a video URL or a thumbnail.'
        ])->withInput();
    }

    $career = new CareerNode();
    $career->title          = $request->title;
    $career->description    = $request->description;
    $career->subjects       = json_encode($request->subjects ?? []);
    $career->career_options = json_encode($request->career_options);
    $career->video          = $videoId;
    $career->specialization = $request->specialization;

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
        'title'            => 'nullable|unique:career_nodes,title,' . $career->id,
        'description'      => 'nullable',
        'subjects'         => 'nullable|array',
        'subjects.*'       => 'nullable|string',
        'career_options'   => 'nullable|array',
        'video'            => ['nullable', 'url', 'regex:/(?:youtube\.com.*v=|youtu\.be\/)([^&#]+)/'],
        'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        'specialization'   => 'nullable|string',
        'remove_thumbnail' => 'nullable|string',
    ]);

    $videoId   = $career->video;
    $thumbnail = $career->thumbnail;

    if ($request->filled('video')) {
        $videoId = $this->extractYoutubeId($request->video);

        if (!$videoId) {
            return back()->withErrors(['video' => 'Invalid YouTube URL'])->withInput();
        }

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

    } elseif ($request->input('remove_thumbnail') === '1') {
        if ($thumbnail && Storage::exists('public/' . $thumbnail)) {
            Storage::delete('public/' . $thumbnail);
        }
        $thumbnail = null;
    }

    $career->title          = $request->title          ?? $career->title;
    $career->description    = $request->description    ?? $career->description;
    $career->subjects       = json_encode($request->subjects ?? []);
    $career->career_options = json_encode($request->career_options ?? []);
    $career->video          = $videoId;
    $career->thumbnail      = $thumbnail;
    $career->specialization = $request->specialization;

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
