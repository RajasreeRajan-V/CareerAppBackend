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
        $careerNodes = CareerNode::all();
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
        'video' => 'required|mimes:mp4,mov,avi|max:51200', 
        'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

   
    if ($request->hasFile('video')) {
        $videoPath = $request->file('video')->store('career_videos', 'public');
    }

    // Upload Thumbnail
    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = $request->file('thumbnail')->store('career_thumbnails', 'public');
    }

    CareerNode::create([
        'title' => $request->title,
        'description' => $request->description,
        'subjects' => json_encode($request->subjects),
        'career_options' => json_encode($request->career_options),
        'video' => $videoPath,
        'thumbnail' => $thumbnailPath,
    ]);

    return redirect()->route('admin.career_nodes.index')
                     ->with('success', 'Careers Created Successfully');
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
        $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'subjects' => 'nullable|string',
        'career_options' => 'nullable|string',
        'video' => 'nullable|mimes:mp4,mov,avi|max:51200', 
        'thumbnail' => 'nullable|image|max:10240', 
    ]);
    $career->title = $validated['title'];
    $career->description = $validated['description'] ?? '';
    $career->subjects = !empty($validated['subjects'])
        ? json_encode(array_map('trim', explode(',', $validated['subjects'])))
        : null;
    $career->career_options = !empty($validated['career_options'])
        ? json_encode(array_map('trim', explode(',', $validated['career_options'])))
        : null;

    if ($request->hasFile('video')) {
        // Delete old video if exists
        if ($career->video && Storage::exists('public/' . $career->video)) {
            Storage::delete('public/' . $career->video);
        }
        $career->video = $request->file('video')->store('career_videos', 'public');
    }
    if ($request->hasFile('thumbnail')) {
        // Delete old thumbnail if exists
        if ($career->thumbnail && Storage::exists('public/' . $career->thumbnail)) {
            Storage::delete('public/' . $career->thumbnail);
        }
        $career->thumbnail = $request->file('thumbnail')->store('career_thumbnails', 'public');
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
