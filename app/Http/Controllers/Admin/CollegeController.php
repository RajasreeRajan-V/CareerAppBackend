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
        return view('admin.college_creation');
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:5',
            'phone' => 'required|string|max:12',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'about' => 'nullable|string',
            'images'   => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'facilities.*' => 'nullable|string',
            'courses.*' => 'nullable|string',
        ]);
        $college = College::create([
            'name'     => $request->name,
            'location' => $request->location,
            'rating'   => $request->rating,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'website'  => $request->website,
            'about'    => $request->about,
        ]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('colleges', 'public');

                CollegeImage::create([
                    'college_id' => $college->id,
                    'image_url'      => $path,
                ]);
            }
        }
       if ($request->has('facilities')) {
            foreach ($request->facilities as $facility) {
                if (!empty($facility)) {  
                    CollegeFacility::create([
                        'college_id' => $college->id,
                        'facility'   => $facility,  
                    ]);
                }
            }
        }
        if ($request->has('courses')) {
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
}
