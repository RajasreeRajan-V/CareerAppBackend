<?php

namespace App\Http\Controllers\Admin;

use App\Models\CareerRecordVideo;
use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

class CareerRecordVideoController 
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $videos = CareerRecordVideo::latest()->paginate(10);
        return view('admin.admin_record_video', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
        'title'     => 'required|string|max:255',
        'about'     => 'required|string',
        'url'       => 'required|url',
        'duration'  => 'required|string|regex:/^\d{1,2}:\d{2}$/',
        'creator'   => 'required|string|max:255',
    ]);
    $videoId = $this->extractYoutubeId($request->url);
    if (!$videoId) {
        return back()
            ->withInput()
            ->withErrors(['url' => 'Invalid YouTube URL']);
    }
    CareerRecordVideo::create([
        'title'     => $request->title,
        'about'     => $request->about,
        'url'       => $videoId, // store only ID
        'duration'  => $request->duration,
        'creator'   => $request->creator,
    ]);
    return redirect()
        ->back()
        ->with('success', 'Video added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CareerRecordVideo $careerRecordVideo)
    {
        //
    }

private function extractYoutubeId($url)
{
    $parsedUrl = parse_url($url);

    if (!$parsedUrl) {
        return null;
    }

    // 1. Standard watch URL (youtube.com/watch?v=ID)
    if (isset($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $query);
        if (isset($query['v'])) {
            return $query['v'];
        }
    }

    // 2. Short URL (youtu.be/ID)
    if (isset($parsedUrl['host']) && strpos($parsedUrl['host'], 'youtu.be') !== false) {
        return ltrim($parsedUrl['path'], '/');
    }

    // 3. Shorts URL (youtube.com/shorts/ID)
    if (isset($parsedUrl['path']) && strpos($parsedUrl['path'], '/shorts/') !== false) {
        $segments = explode('/', trim($parsedUrl['path'], '/'));
        return $segments[1] ?? null; // shorts/VIDEO_ID
    }

    // 4. Embed URL (youtube.com/embed/ID)
    if (isset($parsedUrl['path']) && strpos($parsedUrl['path'], '/embed/') !== false) {
        $segments = explode('/', trim($parsedUrl['path'], '/'));
        return $segments[1] ?? null;
    }

    return null;
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CareerRecordVideo $careerRecordVideo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'title'     => 'required|string|max:255',
        'about'     => 'required|string',
        'url'       => 'required|url',
        'duration'  => 'required|string|regex:/^\d{1,2}:\d{2}$/',
        'creator'   => 'required|string|max:255',
    ]);

    $video = CareerRecordVideo::findOrFail($id);

    $videoId = $this->extractYoutubeId($request->url);

    if (!$videoId) {
        return back()
            ->withInput()
            ->withErrors(['url' => 'Invalid YouTube URL']);
    }

    $video->update([
        'title'     => $request->title,
        'about'     => $request->about,
        'url'       => $videoId,
        'duration'  => $request->duration,
        'creator'   => $request->creator,
    ]);

    return redirect()->back()->with('success', 'Video updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $video = CareerRecordVideo::findOrFail($id);
        $video->delete();
        return redirect()->back()->with('success', 'Video deleted successfully.');
    }
}
