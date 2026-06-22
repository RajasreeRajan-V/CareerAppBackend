<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavedCollege;
use App\Models\College;  
use Illuminate\Support\Facades\Validator;
use App\Models\CollegeView;
use App\Models\State;
use App\Models\District;
use App\Models\Course;
use App\Models\User;

class SearchCollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
    
public function searchColleges(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'keyword'  => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"      => "0",
                "status_code" => "422",
                "data"        => (object)[],
                "message"     => collect($validator->errors()->all())->first()
            ], 422);
        }

        $keyword  = trim($request->keyword ?? '');
        $location = trim($request->location ?? '');
        $perPage  = $request->per_page ?? 10;

        $query = College::with(['courses'])->latest();

        if (!empty($keyword)) {
            // Remove special chars, keep letters, numbers, spaces
            $cleanedInput    = preg_replace('/[^a-zA-Z0-9\s]/', '', $keyword);
            // Combine all words together (no spaces) for joined matching
            $combinedKeyword = preg_replace('/\s+/', '', $cleanedInput);

            $query->where(function ($q) use ($cleanedInput, $combinedKeyword) {

                // Match college name as full phrase (with spaces)
                $q->orWhereRaw(
                    "REGEXP_REPLACE(name, '[^a-zA-Z0-9 ]', '') LIKE ?",
                    ["%$cleanedInput%"]
                )
                // Match college name as combined word (no spaces) e.g. "B ed" => "Bed"
                ->orWhereRaw(
                    "REGEXP_REPLACE(name, '[^a-zA-Z0-9]', '') LIKE ?",
                    ["%$combinedKeyword%"]
                )
                // Match courses as full phrase
                ->orWhereHas('courses', function ($courseQuery) use ($cleanedInput, $combinedKeyword) {
                    $courseQuery->whereRaw(
                        "REGEXP_REPLACE(name, '[^a-zA-Z0-9 ]', '') LIKE ?",
                        ["%$cleanedInput%"]
                    )
                    ->orWhereRaw(
                        "REGEXP_REPLACE(name, '[^a-zA-Z0-9]', '') LIKE ?",
                        ["%$combinedKeyword%"]
                    );
                });
            });
        }

        if (!empty($location)) {
            $cleanedLocation    = preg_replace('/[^a-zA-Z0-9\s]/', '', $location);
            $combinedLocation   = preg_replace('/\s+/', '', $cleanedLocation);

            $query->where(function ($q) use ($cleanedLocation, $combinedLocation) {
                $q->orWhereRaw(
                    "REGEXP_REPLACE(location, '[^a-zA-Z0-9 ]', '') LIKE ?",
                    ["%$cleanedLocation%"]
                )
                ->orWhereRaw(
                    "REGEXP_REPLACE(location, '[^a-zA-Z0-9]', '') LIKE ?",
                    ["%$combinedLocation%"]
                );
            });
        }

        $colleges = $query->paginate($perPage);

        if ($colleges->isEmpty()) {
            return response()->json([
                "status"      => "1",
                "status_code" => "200",
                "data"        => (object)[],
                "message"     => "No colleges found"
            ], 200);
        }

        $formattedColleges = $colleges->getCollection()->map(function ($college) {
            return [
                "id"       => (string) $college->id,
                "name"     => $college->name,
                "location" => $college->location,
                "rating"   => (string) $college->rating,
                "courses"  => $college->courses->pluck('name')->implode(', '),
            ];
        });

        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => [
                "colleges"       => $formattedColleges,
                "current_page"   => (string) $colleges->currentPage(),
                "last_page"      => (string) $colleges->lastPage(),
                "per_page"       => (string) $colleges->perPage(),
                "total_colleges" => (string) $colleges->total(),
            ],
            "message" => "Colleges fetched successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function collegeDetails(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'id'      => 'required|numeric|exists:colleges,id',
            'user_id' => 'nullable|numeric|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"      => "0",
                "status_code" => "422",
                "data"        => (object)[],
                "message"     => collect($validator->errors()->all())->first()
            ], 422);
        }
        $college = College::with([
            'courses.feeStructures',
            'images',
            'facilities'
        ])->find($request->id);
        if (!$college) {
            return response()->json([
                "status"      => "0",
                "status_code" => "404",
                "data"        => (object)[],
                "message"     => "College not found"
            ], 404);
        }
        $formattedCollege = [
            "id"       => (string) $college->id,
            "name"     => $college->name,
            "location" => $college->location,
            "rating"   => (string) $college->rating,
            "courses"  => $college->courses->map(function ($course) {
                return [
                    "course_id"         => (string) $course->id,
                    "course_name"       => $course->name,
                    "has_fee_structure" => $course->feeStructures->isNotEmpty() ? 1 : 0,
                ];
            })->values(),
            "phone"      => $college->phone,
            "email"      => $college->email,
            "website"    => $college->website,
            "about"      => $college->about,
            "images"     => $college->images->pluck('image_url')->map(function ($image) {
                return asset('storage/' . $image);
            })->toArray(),
            "facilities" => $college->facilities->pluck('facility')->toArray(),
        ];
        // Only process user-specific data if user_id is provided
        if ($request->filled('user_id')) {
            $user = User::findOrFail($request->user_id);
            // Track college view
            CollegeView::firstOrCreate([
                'user_id'    => $user->id,
                'college_id' => $college->id,
            ]);
            // Check if saved
            $formattedCollege['is_saved'] = SavedCollege::where('user_id', $user->id)
                ->where('college_id', $college->id)
                ->exists();
        }
        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => ["college" => $formattedCollege],
            "message"     => "College details fetched successfully"
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function saveCollege(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|numeric|exists:users,id',
            'college_id' => 'required|numeric|exists:colleges,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"      => "0",
                "status_code" => "422",
                "data"        => (object)[],
                "message"     => collect($validator->errors()->all())->first()
            ], 422);
        }

        $existing = SavedCollege::where('user_id', $request->user_id)
            ->where('college_id', $request->college_id)
            ->first();

        if ($existing) {
            return response()->json([
                "status"      => "0",
                "status_code" => "409",
                "data"        => (object)[],
                "message"     => "College already saved"
            ], 409);
        }

        SavedCollege::create([
            'user_id'    => $request->user_id,
            'college_id' => $request->college_id,
        ]);

        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => (object)[],
            "message"     => "College saved successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function removeSavedCollege(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|numeric|exists:users,id',
            'college_id' => 'required|numeric|exists:colleges,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"      => "0",
                "status_code" => "422",
                "data"        => (object)[],
                "message"     => collect($validator->errors()->all())->first()
            ], 422);
        }

        $deleted = SavedCollege::where('user_id', $request->user_id)
            ->where('college_id', $request->college_id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                "status"      => "0",
                "status_code" => "404",
                "data"        => (object)[],
                "message"     => "College not found in saved list"
            ], 404);
        }

        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => (object)[],
            "message"     => "College removed from saved list"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function getSavedColleges(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|numeric|exists:users,id',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page'     => 'nullable|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"      => "0",
                "status_code" => "422",
                "data"        => (object)[],
                "message"     => collect($validator->errors()->all())->first()
            ], 422);
        }

        $perPage = $request->per_page ?? 10;
        $page    = $request->page ?? 1;

        $savedColleges = SavedCollege::where('user_id', $request->user_id)
            ->with(['college.courses', 'college.images', 'college.facilities'])
            ->paginate($perPage, ['*'], 'page', $page);

        if ($savedColleges->isEmpty()) {
            return response()->json([
                "status"      => "1",
                "status_code" => "200",
                "data"        => [
                    "colleges"       => [],
                    "current_page"   => "1",
                    "last_page"      => "1",
                    "per_page"       => (string) $perPage,
                    "total_colleges" => "0"
                ],
                "message" => "No saved colleges found"
            ], 200);
        }

        $formattedColleges = $savedColleges->getCollection()->map(function ($saved) {
            $college = $saved->college;
            if (!$college) return null;
            return [
                "id"         => (string) $college->id,
                "name"       => $college->name,
                "location"   => $college->location,
                "rating"     => (string) $college->rating,
                "courses"    => $college->courses->pluck('name')->implode(', '),
                "phone"      => $college->phone,
                "email"      => $college->email,
                "website"    => $college->website,
                "about"      => $college->about,
                "images"     => $college->images->pluck('image_url')->map(function ($image) {
                    return asset('storage/' . $image);
                })->toArray(),
                "facilities" => $college->facilities->pluck('facility')->toArray(),
                "is_saved"   => true,
            ];
        })->filter()->values();

        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => [
                "colleges"       => $formattedColleges,
                "current_page"   => (string) $savedColleges->currentPage(),
                "last_page"      => (string) $savedColleges->lastPage(),
                "per_page"       => (string) $savedColleges->perPage(),
                "total_colleges" => (string) $savedColleges->total(),
            ],
            "message" => "Saved colleges fetched successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function getStates()
    {
        $states = State::orderBy('name')->get();

        return response()->json([
            'status' => true,
            'data' => $states
        ]);
    }

    /**
     * Get districts by state_id
     */
    public function getDistricts(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id'
        ]);

        $districts = District::where('state_id', $request->state_id)
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $districts
        ]);
    }
    
    public function courseFeeStructure(Request $request)
{
    try {
        // Validate
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }

        // Fetch course with fee structures
        $course = Course::with('feeStructures.breakdowns')
                        ->find($request->course_id);

        if (!$course) {
            return response()->json([
                "status" => "0",
                "status_code" => "404",
                "data" => (object)[],
                "message" => "Course not found"
            ], 404);
        }

        // Format response
        $formattedData = [
            "course_id" => (string) $course->id,
            "course_name" => $course->name,

            "fee_structures" => $course->feeStructures->map(function ($fee) {
                return [
                    "fee_structure_id" => (string) $fee->id,
                    "fee_type" => $fee->fee_type, // government, management, nri
                    "fee_mode" => $fee->fee_mode, // total, yearly, semester
                    "total_amount" => (string) $fee->total_amount,

                    "breakdowns" => $fee->breakdowns->map(function ($b) {
                        return [
                            "label" => $b->label,
                            "amount" => (string) $b->amount,
                        ];
                    })->values()
                ];
            })->values()
        ];

        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => $formattedData,
            "message" => "Fee structure fetched successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong"
        ], 500);
    }
}


}
