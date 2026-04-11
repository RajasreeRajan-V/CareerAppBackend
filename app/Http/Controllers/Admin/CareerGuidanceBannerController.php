<?php

namespace App\Http\Controllers\Admin;

use App\Models\CareerGuidanceBanner;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CareerGuidanceBannerController 
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guidanceBanners = CareerGuidanceBanner::latest()->paginate(10);
        return view('admin.admin_career_guidance_banner', compact('guidanceBanners'));
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
        'name'             => 'required|string|max:255',
        'profession'       => 'nullable|string|max:255',
        'instructor_name'  => 'required|string|max:255',
        'description'      => 'required|string',
        'event_date'       => 'required|date|after_or_equal:today',
        'start_time'       => 'required|date_format:H:i',
        'end_time'         => 'required|date_format:H:i|after:start_time',
        'google_meet_link' => 'required|url',
        'image'            => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')
            ->store('career_guidance_banners', 'public');
    }

    $meetCode = $this->extractMeetCode($request->google_meet_link);

    if (!$meetCode) {
        return back()
            ->withErrors(['google_meet_link' => 'Invalid Google Meet link'])
            ->withInput();
    }

    CareerGuidanceBanner::create([
        'name'             => $request->name,
        'profession'       => $request->profession,
        'instructor_name'  => $request->instructor_name,
        'description'      => $request->description,
        'event_date'       => $request->event_date,
        'start_time'       => $request->start_time,
        'end_time'         => $request->end_time,
        'google_meet_link' => $meetCode, 
        'image'            => $imagePath,
    ]);

    return redirect()
        ->route('admin.guidance_banners.index')
        ->with('success', 'Career Guidance Banner added successfully!');
}

    private function extractMeetCode($url)
{
    $parsedUrl = parse_url($url);

    // Ensure host is meet.google.com
    if (!isset($parsedUrl['host']) || $parsedUrl['host'] !== 'meet.google.com') {
        return null;
    }

    // Extract path (e.g. /abc-defg-hij)
    if (isset($parsedUrl['path'])) {
        return ltrim($parsedUrl['path'], '/');
    }

    return null;
}
    /**
     * Display the specified resource.
     */
    public function show(CareerGuidanceBanner $careerGuidanceBanner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CareerGuidanceBanner $careerGuidanceBanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
       $banner = CareerGuidanceBanner::findOrFail($id);
       $request->validate([
        'name'             => 'required|string|max:255',
        'profession'       => 'nullable|string|max:255',
        'instructor_name'  => 'required|string|max:255',
        'description'      => 'required|string',
        'event_date'       => 'required|date|after_or_equal:today',
        'start_time'       => 'required',
        'end_time'         => 'required|after:start_time',
        'google_meet_link' => 'required',
        'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072', // 3MB
    ]);
    $banner->name            = $request->name;
    $banner->profession      = $request->profession;
    $banner->instructor_name = $request->instructor_name;
    $banner->description     = $request->description;
    $banner->event_date      = $request->event_date;
    $banner->start_time      = $request->start_time;
    $banner->end_time        = $request->end_time;
    $banner->google_meet_link= $request->google_meet_link;
    if ($request->hasFile('image')) {

        // Delete old image if exists
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        // Store new image
        $imagePath = $request->file('image')->store('career_guidance_banners', 'public');
        $banner->image = $imagePath;
    }
    $banner->save();

    return redirect()
        ->route('admin.guidance_banners.index')
        ->with('success', 'Career Guidance Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = CareerGuidanceBanner::findOrFail($id);

        // Delete image if exists
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()
            ->route('admin.guidance_banners.index')
            ->with('success', 'Career Guidance Banner deleted successfully.');
  
    }
}
