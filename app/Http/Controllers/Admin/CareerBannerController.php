<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerBanner;
use Illuminate\Support\Facades\Storage;

class CareerBannerController 
{
    public function index()
    {
        $banners = CareerBanner::latest()->get();
        return view('admin.careerbanner', compact('banners'));
    }

    public function create()
    {
        // Logic to show form for creating a new career banner
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
        'title' => 'required',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp',
    ]);
        $imagePath = $request->file('image')->store(
            'career_banners',
            'public'
        );
        CareerBanner::create([
            'title' => $validated['title'],
            'image' => $imagePath,
        ]);
        return redirect()
            ->route('admin.careerBanner.index')
            ->with('success', 'Career banner created successfully.');
    }

    public function edit($id)
    {
        // Logic to show form for editing an existing career banner
    }

    public function update(Request $request, $id)
    {
        $banner = CareerBanner::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    $banner->title = $request->title;
    if ($request->hasFile('image')) {

        // Delete old image if exists
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        // Store new image
        $imagePath = $request->file('image')->store('career-banners', 'public');

        $banner->image = $imagePath;
    }
    $banner->save();

    return redirect()->back()->with('success', 'Career banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = CareerBanner::findOrFail($id);
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
        Storage::disk('public')->delete($banner->image);
    }
        $banner->delete();
        return redirect()->back()->with('success', 'Career banner deleted successfully.');
    }
}
