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
use App\Services\FirebaseNotificationService;
use App\Models\PushNotification;

class CollegeController extends Controller
{
   public function index(Request $request)
{
    $search = $request->input('search');

    $colleges = College::with(['facilities', 'courses', 'images'])
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(5)
        ->withQueryString();

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
    
    if ($request->filled('website') && !preg_match('/^https?:\/\//i', $request->website)) {
        $request->merge([
            'website' => 'https://' . $request->website
        ]);
    }
   $request->validate([
    'name'         => 'required|string|max:255',
    'street'       => 'required|string|max:255',
    'state_id'     => 'required|exists:states,id',
    'district_id'  => 'required|exists:districts,id',
    'rating'       => 'nullable|numeric|between:0,5',
    'phone'        => 'required|regex:/^[0-9]{10,12}$/|unique:colleges,phone',
    'email'      => 'required|email:rfc,dns|unique:colleges,email',
    'website'      => 'nullable|url',
    'about'        => 'nullable|string',

    // Images
    'images'       => 'nullable|array',
    'images.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

    // Facilities & Courses
    'facilities.*' => 'nullable|string|max:255',
    'courses.*'    => 'nullable|string|max:255',

], [

    // Image Messages
    'images.*.image' => 'Each file must be a valid image.',
    'images.*.mimes' => 'Images must be jpeg, png, jpg, gif, or svg format.',
    'images.*.max'   => 'Each image size must not exceed 2MB.',

    // Facility Messages
    'facilities.*.max' => 'Each facility name must not exceed 255 characters.',

    // Course Messages
    'courses.*.max' => 'Each course name must not exceed 255 characters.',
]);

    $state    = State::findOrFail($request->state_id);
    $district = District::findOrFail($request->district_id);
    $location = $request->street . ', ' . $district->name . ', ' . $state->name;

    // Generate plain password before hashing
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
        // 'is_verified'      => false,
        'password_changed' => false,
    ]);
    
    
    // Images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('colleges', 'public');
            CollegeImage::create(['college_id' => $college->id, 'image_url' => $path]);
        }
    }

    // Facilities
    if ($request->filled('facilities')) {
        foreach ($request->facilities as $facility) {
            if (!empty($facility)) {
                CollegeFacility::create(['college_id' => $college->id, 'facility' => $facility]);
            }
        }
    }

    // Courses
    if ($request->filled('courses')) {
        foreach ($request->courses as $course) {
            if (!empty($course)) {
                Course::create(['college_id' => $college->id, 'name' => $course]);
            }
        }
    }

    // Send credentials â€” catch mail failures gracefully
    try {
        Mail::to($college->email)->send(new CollegeRegisteredMail(
            $college->name,
            $college->email,
            $plainPassword
        ));
        $mailMessage = 'College added and credentials sent to ' . $college->email;
    } catch (\Exception $e) {
        \Log::error('College mail failed: ' . $e->getMessage());
        $mailMessage = 'College added successfully, but email could not be sent to ' . $college->email . '. Please share credentials manually.';
    }
    
//     try {
//         $firebase = new FirebaseNotificationService();
//         $firebase->sendToAll(
//     'ðŸŽ“ A New College Just Joined!',
//     $college->name . ' is now available. Find your perfect course today.'
// );
//     } catch (\Exception $e) {
//         \Log::error('FCM notification failed for college [' . $college->id . ']: ' . $e->getMessage());
//         // Non-blocking â€” college is already saved, just log and continue
//     }
        
   try {
    $firebase = new FirebaseNotificationService();

    $firebase->sendToAll(
        'New College Added: ' . $college->name,
        'Discover programs, courses, and admission details now available on our platform.',
        $college->id
    );

} catch (\Exception $e) {

    \Log::error(
        'FCM notification failed for college [' .
        $college->id .
        ']: ' .
        $e->getMessage()
    );
}


    return redirect()->route('admin.college.index')->with('success', $mailMessage);
}


public function update(Request $request, $id)
{
    $college = College::findOrFail($id);

    // Auto-prepend https:// if missing
    if ($request->filled('website') && !preg_match('/^https?:\/\//i', $request->website)) {
        $request->merge(['website' => 'https://' . $request->website]);
    }

    $request->validate([
        'name'         => 'required|string|max:255',
        'street'       => 'required|string|max:255',
        'state_id'     => 'required|exists:states,id',
        'district_id'  => 'required|exists:districts,id',
        'rating'       => 'nullable|numeric|between:0,5',   // fixed
        'phone' => 'required|digits:10',
        'email' => 'required|email:rfc,dns|unique:colleges,email,' . $id,
        'website'      => 'nullable|url',
        'about'        => 'nullable|string',
        'images'       => 'nullable|array',
        'images.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'facilities'   => 'nullable|array',
        'facilities.*' => 'nullable|string|max:255',
        'courses'      => 'nullable|array',
        'courses.*'    => 'nullable|string|max:255',
    ]);

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

    // Images â€” remove deselected, keep existing, add new
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

    // Facilities
    $college->facilities()->delete();
    if ($request->filled('facilities')) {
        foreach ($request->facilities as $facility) {
            if (!empty($facility)) {
                CollegeFacility::create(['college_id' => $college->id, 'facility' => $facility]);
            }
        }
    }

    // Courses
    $college->courses()->delete();
    if ($request->filled('courses')) {
        foreach ($request->courses as $course) {
            if (!empty($course)) {
                Course::create(['college_id' => $college->id, 'name' => $course]);
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
    PushNotification::where('college_id', $college->id)->delete();
    $college->delete();

    return redirect()
        ->route('admin.college.index')
        ->with('success', 'College and related notifications deleted successfully!');
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
