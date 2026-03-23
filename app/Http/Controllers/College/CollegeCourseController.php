<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth; 
class CollegeCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course = Course::where('college_id', auth()->user()->college_id)->get();
        return view('college.college_course', compact('course'));
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
        'name' => 'required|string|max:255',
    ]);

    Course::create([
        'name' => $request->name,
        'college_id' => auth()->user()->college_id 
    ]);
    return redirect()->back()->with('success', 'Profile updated successfully');
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
       $request->validate([
        'name' => 'required|string|max:255',
    ]);
    $course = Course::where('id', $id)
        ->where('college_id', auth()->user()->college_id)
        ->firstOrFail();
    $course->update([
        'name' => $request->name,
    ]);
    return redirect()->back()->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $course = Course::where('id', $id)
        ->where('college_id', auth()->user()->college_id)
        ->firstOrFail();
        $course->delete();
        return redirect()->back()->with('success', 'Course deleted successfully!');
    }
}
