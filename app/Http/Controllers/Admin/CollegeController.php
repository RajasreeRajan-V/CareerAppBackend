<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\College;
use App\Models\CollegeImage;
use App\Models\CollegeFacility;
use App\Models\Course;
use App\Models\State;
use App\Models\District;
use Illuminate\Support\Facades\Storage;
use App\Mail\CollegeRegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class CollegeController extends Controller
{
   public function index()
{
    $colleges = College::with(['facilities', 'courses', 'images'])
        ->latest()
        ->paginate(5);

    $states = State::orderBy('name')->get();

    return view('admin.manage_college', compact('colleges', 'states'));
}

    public function create()
    {
        $states = State::orderBy('name')->get();
        return view('admin.college_creation', compact('states'));
    }

public function store(Request $request)
{
    $request->validate([
        'name'         => 'required|string|max:255',
        'street'       => 'required|string|max:255',
        'state_id'     => 'required|exists:states,id',
        'district_id'  => 'required|exists:districts,id',
        'rating'       => 'required|numeric|min:0|max:5',
        'phone'        => 'required|regex:/^[0-9]{10,12}$/|unique:colleges,phone',
        'email'        => 'required|email|unique:colleges,email',
        'website'      => 'nullable|url',
        'about'        => 'nullable|string',
        'images'       => 'nullable|array',
        'images.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'facilities'   => 'nullable|array',
        'facilities.*' => 'nullable|string|max:255',
        'courses'      => 'nullable|array',
        'courses.*'    => 'nullable|string|max:255',
    ]);

    // ✅ Duplicate checks using helper
    if ($error = $this->checkDuplicates($request->input('facilities', []), 'facility')) {
        return back()->withErrors(['facilities' => $error])->withInput();
    }

    if ($error = $this->checkDuplicates($request->input('courses', []), 'course')) {
        return back()->withErrors(['courses' => $error])->withInput();
    }

    $state    = State::findOrFail($request->state_id);
    $district = District::findOrFail($request->district_id);
    $location = $request->street . ', ' . $district->name . ', ' . $state->name;

    $plainPassword = Str::random(8);

    $college = College::create([
        'name'             => $request->name,
        'location'         => $location,
        'state_id'         => $request->state_id,
        'district_id'      => $request->district_id,
        'rating'           => $request->rating,
        'phone'            => $request->phone,
        'email'            => $request->email,
        'website'          => $request->website,
        'about'            => $request->about,
        'password'         => Hash::make($plainPassword),
        'password_changed' => false,
    ]);

    // Images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('colleges', 'public');
            CollegeImage::create(['college_id' => $college->id, 'image_url' => $path]);
        }
    }

    // ✅ Facilities — with $seen guard as final DB-level protection
    $seenFacilities = [];
    if ($request->filled('facilities')) {
        foreach ($request->facilities as $facility) {
            $trimmed = trim($facility);
            $key     = strtolower($trimmed);
            if (!empty($trimmed) && !in_array($key, $seenFacilities)) {
                $seenFacilities[] = $key;
                CollegeFacility::create(['college_id' => $college->id, 'facility' => $trimmed]);
            }
        }
    }

    // ✅ Courses — with $seen guard as final DB-level protection
    $seenCourses = [];
    if ($request->filled('courses')) {
        foreach ($request->courses as $course) {
            $trimmed = trim($course);
            $key     = strtolower($trimmed);
            if (!empty($trimmed) && !in_array($key, $seenCourses)) {
                $seenCourses[] = $key;
                Course::create(['college_id' => $college->id, 'name' => $trimmed]);
            }
        }
    }

    Mail::to($college->email)->send(new CollegeRegisteredMail(
        $college->name,
        $college->email,
        $plainPassword
    ));

    return redirect()
        ->route('admin.college.index')
        ->with('success', 'College added and credentials sent to ' . $college->email);
}


public function update(Request $request, $id)
{
    $college = College::findOrFail($id);

    $request->validate([
        'name'         => 'required|string|max:255',
        'street'       => 'required|string|max:255',
        'state_id'     => 'required|exists:states,id',
        'district_id'  => 'required|exists:districts,id',
        'rating'       => 'required|numeric|min:0|max:5',
        'phone'        => 'required|regex:/^[0-9]{10,12}$/|unique:colleges,phone,' . $id,
        'email'        => 'required|email|unique:colleges,email,' . $id,
        'website'      => 'nullable|url',
        'about'        => 'nullable|string',
        'images'       => 'nullable|array',
        'images.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'facilities'   => 'nullable|array',
        'facilities.*' => 'nullable|string|max:255',
        'courses'      => 'nullable|array',
        'courses.*'    => 'nullable|string|max:255',
    ]);

    // ✅ Duplicate checks using helper
    if ($error = $this->checkDuplicates($request->input('facilities', []), 'facility')) {
        return back()->withErrors(['facilities' => $error])->withInput();
    }

    if ($error = $this->checkDuplicates($request->input('courses', []), 'course')) {
        return back()->withErrors(['courses' => $error])->withInput();
    }

    $state    = State::findOrFail($request->state_id);
    $district = District::findOrFail($request->district_id);
    $location = $request->street . ', ' . $district->name . ', ' . $state->name;

    $college->update([
        'name'        => $request->name,
        'location'    => $location,
        'state_id'    => $request->state_id,
        'district_id' => $request->district_id,
        'rating'      => $request->rating,
        'phone'       => $request->phone,
        'email'       => $request->email,
        'website'     => $request->website,
        'about'       => $request->about,
    ]);

    // Images — remove deselected, keep existing, add new
    $keepImages = $request->input('existing_images', []);
    $oldImages  = $college->images()->whereNotIn('image_url', $keepImages)->get();

    foreach ($oldImages as $oldImage) {
        Storage::disk('public')->delete($oldImage->image_url);
        $oldImage->delete();
    }

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('colleges', 'public');
            CollegeImage::create(['college_id' => $college->id, 'image_url' => $path]);
        }
    }

    // ✅ Facilities — delete old, re-insert with $seen guard
    $college->facilities()->delete();
    $seenFacilities = [];
    if ($request->filled('facilities')) {
        foreach ($request->facilities as $facility) {
            $trimmed = trim($facility);
            $key     = strtolower($trimmed);
            if (!empty($trimmed) && !in_array($key, $seenFacilities)) {
                $seenFacilities[] = $key;
                CollegeFacility::create(['college_id' => $college->id, 'facility' => $trimmed]);
            }
        }
    }

    // ✅ Courses — delete old, re-insert with $seen guard
    $college->courses()->delete();
    $seenCourses = [];
    if ($request->filled('courses')) {
        foreach ($request->courses as $course) {
            $trimmed = trim($course);
            $key     = strtolower($trimmed);
            if (!empty($trimmed) && !in_array($key, $seenCourses)) {
                $seenCourses[] = $key;
                Course::create(['college_id' => $college->id, 'name' => $trimmed]);
            }
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

private function checkDuplicates(array $items, string $field): ?string
{
    $cleaned = array_filter(array_map('trim', $items), fn($v) => $v !== '');
    $lowered = array_map('strtolower', array_values($cleaned));
    
    if (count($lowered) !== count(array_unique($lowered))) {
        return "Duplicate {$field} names are not allowed.";
    }
    return null;
}
}
