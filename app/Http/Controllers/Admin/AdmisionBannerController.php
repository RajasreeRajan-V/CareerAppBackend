<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmisionBanner;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class AdmisionBannerController 
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = AdmisionBanner::latest()->paginate(10); 
        return view('admin.admission_banner', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       AdmisionBanner::create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'link'        => 'nullable|url',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                'admission-banners',
                'public'
            );
        }
        AdmisionBanner::create([
            'title'       => $request->title,
            'image'       => $imagePath,
            'description' => $request->description,
            'link' => $request->link, // only if column exists
        ]);
        return redirect()
            ->route('admin.admissionBanner.index')
            ->with('success', 'Admission banner created successfully!');
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
    public function update(Request $request, $id)
    {
        $banner = AdmisionBanner::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            
        ]);
        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('admission-banners', 'public');
        }
        $banner->update($data);
        return redirect()
            ->route('admin.admissionBanner.index')
            ->with('success', 'Admission banner updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = AdmisionBanner::findOrFail($id);
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()
            ->route('admin.admissionBanner.index')
            ->with('success', 'Admission banner deleted successfully!');
    }
}
