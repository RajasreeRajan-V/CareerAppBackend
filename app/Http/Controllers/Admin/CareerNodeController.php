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
        'title' => 'required|unique:career_nodes,title',
        'description' => 'required',
        'subjects' => 'required|array',
        'career_options' => 'required|array',
        'video'          => ['required', 'url', 'regex:/(?:youtube\.com.*v=|youtu\.be\/)([^&#]+)/'],
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
    ]);

    $videoId = $this->extractYoutubeId($request->video);

    if (!$videoId) {
        return back()->withErrors(['video' => 'Invalid YouTube URL'])->withInput();
    }
    $thumbnailPath = null;

    // Upload Thumbnail
    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = $request->file('thumbnail')->store('career_thumbnails', 'public');
    }

    CareerNode::create([
        'title' => $request->title,
        'description' => $request->description,
        'subjects' => json_encode($request->subjects),
        'career_options' => json_encode($request->career_options),
        'video' => $videoId, // Store ONLY YouTube ID
        'thumbnail' => $thumbnailPath,
    ]);

    return redirect()->route('admin.career_nodes.index')
                     ->with('success', 'Careers Created Successfully');
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
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'subjects' => 'required|array',
        'career_options' => 'required|array',
        'video' => ['required', 'url', 'regex:/(?:youtube\.com.*v=|youtu\.be\/)([^&#]+)/'],
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
    ]);

    $videoId = $this->extractYoutubeId($request->video);

    if (!$videoId) {
        return back()->withErrors(['video' => 'Invalid YouTube URL'])->withInput();
    }

    // Update basic fields
    $career->title = $request->title;
    $career->description = $request->description;
    $career->subjects = json_encode($request->subjects);
    $career->career_options = json_encode($request->career_options);
    $career->video = $videoId; // Store ONLY YouTube ID

    // Upload Thumbnail (if changed)
    if ($request->hasFile('thumbnail')) {

        // Delete old thumbnail
        if ($career->thumbnail && Storage::exists('public/' . $career->thumbnail)) {
            Storage::delete('public/' . $career->thumbnail);
        }

        $career->thumbnail = $request->file('thumbnail')
            ->store('career_thumbnails', 'public');
    }

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
