<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\College;
use App\Models\CollegeImage;
use App\Models\CollegeFacility;
use App\Models\Course;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = College::with(['facilities', 'courses', 'images'])->get();
        return view('admin.manage_college', compact('colleges'));
    }

    public function create()
    {
        return view('admin.college_creation');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'street'   => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state'    => 'required|string|max:255',

            'rating'  => 'required|numeric|min:0|max:5',
            'phone'   => 'required|string|max:12',
            'email'   => 'required|email',
            'website' => 'nullable|url',
            'about'   => 'nullable|string',

            'images'   => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'facilities.*' => 'nullable|string',
            'courses.*'    => 'nullable|string',
        ]);

        $location = $request->street . ', ' . $request->district . ', ' . $request->state;

        $college = College::create([
            'name'     => $request->name,
            'location' => $location,
            'rating'   => $request->rating,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'website'  => $request->website,
            'about'    => $request->about,
        ]);

        // Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('colleges', 'public');

                CollegeImage::create([
                    'college_id' => $college->id,
                    'image_url'  => $path,
                ]);
            }
        }

        // Facilities
        if ($request->filled('facilities')) {
            foreach ($request->facilities as $facility) {
                if (!empty($facility)) {
                    CollegeFacility::create([
                        'college_id' => $college->id,
                        'facility'   => $facility,
                    ]);
                }
            }
        }

        // Courses
        if ($request->filled('courses')) {
            foreach ($request->courses as $course) {
                if (!empty($course)) {
                    Course::create([
                        'college_id' => $college->id,
                        'name'       => $course,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.college.index')
            ->with('success', 'College added successfully');
    }

   public function update(Request $request, $id)
{
    $college = College::findOrFail($id);

    $request->validate([
        'name'         => 'required|string|max:255',

        'street'       => 'required|string|max:255',
        'district'     => 'required|string|max:255',
        'state'        => 'required|string|max:255',

        'rating'       => 'nullable|numeric|min:0|max:5',
        'phone'        => 'nullable|string|max:20',
        'email'        => 'nullable|email',
        'website'      => 'nullable|url',
        'about'        => 'nullable|string',
        'images.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'facilities.*' => 'nullable|string',
        'courses.*'    => 'nullable|string',
    ]);

    $location = $request->street . ', ' . $request->district . ', ' . $request->state;

    $college->update([
        'name'     => $request->name,
        'location' => $location,
        'rating'   => $request->rating,
        'phone'    => $request->phone,
        'email'    => $request->email,
        'website'  => $request->website,
        'about'    => $request->about,
    ]);

    $college->facilities()->delete();
    if ($request->facilities) {
        foreach ($request->facilities as $facility) {
            if (!empty($facility)) {
                $college->facilities()->create(['facility' => $facility]);
            }
        }
    }

    $college->courses()->delete();
    if ($request->courses) {
        foreach ($request->courses as $course) {
            if (!empty($course)) {
                $college->courses()->create(['name' => $course]);
            }
        }
    }

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('colleges', 'public');
            $college->images()->create(['image_url' => $path]);
        }
    }

    return redirect()
        ->route('admin.college.index')
        ->with('success', 'College updated successfully!');
}

    public function destroy($id)
    {
        $college = College::findOrFail($id);
        $college->delete();

        return redirect()
            ->route('admin.college.index')
            ->with('success', 'College deleted successfully!');
    }

    public function editJson($id)
{
    $college = College::with(['facilities','courses'])->findOrFail($id);
    return response()->json([
        'facilities' => $college->facilities,
        'courses' => $college->courses,
    ]);
}
}
