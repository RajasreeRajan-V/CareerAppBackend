<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CollegeCourseController extends Controller
{
    public function index()
    {
        $collegeId = Auth::guard('college')->id();

        $course = Course::where('college_id', $collegeId)->get();

        return view('college.college_course', compact('course'));
    }

    public function store(Request $request)
{
    $collegeId = Auth::guard('college')->id();

    $request->validate([
        'name' => [
            'required', 'string', 'max:255',
            \Illuminate\Validation\Rule::unique('courses')->where(fn($q) => $q->where('college_id', $collegeId)),
        ],
    ]);

    Course::create([
        'name'       => $request->name,
        'college_id' => $collegeId,
    ]);

    return redirect()->back()->with('success', 'Course added successfully.');
}

    public function update(Request $request, string $id)
{
    $collegeId = Auth::guard('college')->id();

    $request->validate([
        'name' => [
            'required', 'string', 'max:255',
            \Illuminate\Validation\Rule::unique('courses')->where(fn($q) => $q->where('college_id', $collegeId))->ignore($id),
        ],
    ]);

    $course = Course::where('id', $id)
        ->where('college_id', $collegeId)
        ->firstOrFail();

    $course->update(['name' => $request->name]);

    return redirect()->back()->with('success', 'Course updated successfully.');
}

    public function destroy(string $id)
    {
        $course = Course::where('id', $id)
            ->where('college_id', Auth::guard('college')->id())
            ->firstOrFail();

        $course->delete();

        return redirect()->back()->with('success', 'Course deleted successfully.');
    }

    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
}