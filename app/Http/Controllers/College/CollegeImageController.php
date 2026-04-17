<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CollegeImage;

class CollegeImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $college = Auth::guard('college')->user()->college;
        $images = $college->images;

        return view('college.college_collegeimg', compact('images'));
    }

    /**
     * Store a newly created image.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $college = Auth::guard('college')->user()->college;

        // Store image
        $path = $request->file('image')->store('college_images', 'public');

        CollegeImage::create([
            'college_id' => $college->id,
            'image_url'  => $path
        ]);

        return back()->with('success', 'Image uploaded successfully');
    }

    /**
     * Update the specified image.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $college = Auth::guard('college')->user()->college;

        $image = CollegeImage::where('id', $id)
            ->where('college_id', $college->id)
            ->firstOrFail();

        // Delete old image (important 🔥)
        if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
            Storage::disk('public')->delete($image->image_url);
        }

        // Store new image
        $path = $request->file('image')->store('college_images', 'public');

        $image->update([
            'image_url' => $path
        ]);

        return back()->with('success', 'Image updated successfully');
    }

    /**
     * Remove the specified image.
     */
    public function destroy(string $id)
    {
        $college = Auth::guard('college')->user()->college;

        $image = CollegeImage::where('id', $id)
            ->where('college_id', $college->id)
            ->firstOrFail();

        // Delete file from storage
        if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
            Storage::disk('public')->delete($image->image_url);
        }

        $image->delete();

        return back()->with('success', 'Image deleted successfully');
    }
}
