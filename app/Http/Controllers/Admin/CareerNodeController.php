<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerNode;
class CareerNodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return view('admin.career_creation');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
