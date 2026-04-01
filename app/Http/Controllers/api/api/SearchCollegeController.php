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
        // Validate request
        $validator = Validator::make($request->all(), [
            'keyword'   => 'nullable|string|max:255',
            'location'  => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'per_page'  => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }

        $keyword   = trim($request->keyword ?? '');
        $location  = trim($request->location ?? '');
        $perPage   = $request->per_page ?? 10;

        $query = College::with(['courses'])
                ->latest();

        // Keyword filter
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                  ->orWhereHas('courses', function ($courseQuery) use ($keyword) {
                      $courseQuery->where('name', 'LIKE', "%$keyword%");
                  });
            });
        }

        // Location filter
        if (!empty($location)) {
            $query->where('location', 'LIKE', "%$location%");
        }

        // PAGINATION HERE
        $colleges = $query->paginate($perPage);

        if ($colleges->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => (object)[],
                "message" => "No colleges found"
            ], 200);
        }

        $formattedColleges = $colleges->getCollection()->map(function ($college) {
            return [
                "id"       => (string) $college->id,
                "name"     => $college->name,
                "location" => $college->location,
                "rating"   => (string) $college->rating,
                "courses"  => $college->courses->pluck('name')->implode(', ')
            ];
        });

        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "colleges"        => $formattedColleges,
                "current_page"    => (string) $colleges->currentPage(),
                "last_page"       => (string) $colleges->lastPage(),
                "per_page"        => (string) $colleges->perPage(),
                "total_colleges"  => (string) $colleges->total(),
            ],
            "message" => "Colleges fetched successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function collegeDetails(Request $request)
{
    try {
        // Validate request
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:colleges,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }
        
        $user = $request->authUser ?? null;


        // Fetch college with relations
        $college = College::with(['courses', 'images', 'facilities'])
            ->find($request->id);
            
        if ($user) {
            CollegeView::firstOrCreate([
                'user_id' => $user->id,
                'college_id' => $college->id,
            ]);
        }    

        // If not found
        if (!$college) {
            return response()->json([
                "status" => "0",
                "status_code" => "404",
                "data" => (object)[],
                "message" => "College not found"
            ], 404);
        }
        
        $isSaved = false;

        if ($user) {
            $isSaved = SavedCollege::where('user_id', $user->id)
                ->where('college_id', $college->id)
                ->exists();
        }


        // Format response
        $formattedCollege = [
            "id" => (string) $college->id,
            "name" => $college->name,
            "location" => $college->location,
            "rating" => (string) $college->rating,
            "courses" => $college->courses->pluck('name')->implode(', '),
            "phone" => $college->phone,
            "email" => $college->email,
            "website" => $college->website,
            "about" => $college->about,
            "images" => $college->images->pluck('image_url')->map(function ($image) {
                return asset('storage/' . $image);
            })->toArray(),

            "facilities" => $college->facilities->pluck('facility')->toArray(),
            "is_saved" => $isSaved,

        ];

        // Success response
        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "college" => $formattedCollege
            ],
            "message" => "College details fetched successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function saveCollege(Request $request)
{
    try {
        // Validate request
        $validator = Validator::make($request->all(), [
            'college_id' => 'required|numeric|exists:colleges,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }

        $user = $request->authUser; // Auth token middleware sets this
        $collegeId = $request->college_id;

        // Check if already saved
        $existing = SavedCollege::where('user_id', $user->id)
            ->where('college_id', $collegeId)
            ->first();

        if ($existing) {
            return response()->json([
                "status" => "0",
                "status_code" => "409",
                "data" => (object)[],
                "message" => "College already saved"
            ], 409);
        }

        // Save the college
        SavedCollege::create([
            'user_id' => $user->id,
            'college_id' => $collegeId,
        ]);

        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => (object)[],
            "message" => "College saved successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function removeSavedCollege(Request $request)
{
    try {
        // Validate request
        $validator = Validator::make($request->all(), [
            'college_id' => 'required|numeric|exists:colleges,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }

        // Get authenticated user from middleware
        $user = $request->authUser;
        $collegeId = $request->college_id;

        // Delete saved college
        $deleted = SavedCollege::where('user_id', $user->id)
            ->where('college_id', $collegeId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                "status" => "0",
                "status_code" => "404",
                "data" => (object)[],
                "message" => "College not found in saved list"
            ], 404);
        }

        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => (object)[],
            "message" => "College removed from saved list"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong. Please try again later."
        ], 500);
    }
}

public function getSavedColleges(Request $request)
{
    try {
        $user = $request->authUser;

        if (!$user) {
            return response()->json([
                "status" => "0",
                "status_code" => "401",
                "data" => (object)[],
                "message" => "Unauthorized"
            ], 401);
        }

        // Pagination inputs
        $perPage = $request->per_page ?? 10;
        $page    = $request->page ?? 1;

        // PAGINATED QUERY
        $savedColleges = SavedCollege::where('user_id', $user->id)
            ->with(['college.courses', 'college.images', 'college.facilities'])
            ->paginate($perPage, ['*'], 'page', $page);

        if ($savedColleges->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "colleges" => [],
                    "current_page" => "1",
                    "last_page" => "1",
                    "per_page" => (string)$perPage,
                    "total_colleges" => "0"
                ],
                "message" => "No saved colleges found"
            ], 200);
        }

        $formattedColleges = $savedColleges->getCollection()->map(function ($saved) {
            $college = $saved->college;

            if (!$college) return null;

            return [
                "id" => (string) $college->id,
                "name" => $college->name,
                "location" => $college->location,
                "rating" => (string) $college->rating,
                "courses" => $college->courses->pluck('name')->implode(', '),
                "phone" => $college->phone,
                "email" => $college->email,
                "website" => $college->website,
                "about" => $college->about,
                "images" => $college->images->pluck('image_url')->map(function ($image) {
                    return asset('storage/' . $image);
                })->toArray(),
                "facilities" => $college->facilities->pluck('facility')->toArray(),
                "is_saved" => true
            ];
        })->filter()->values(); // remove nulls safely

        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "colleges"        => $formattedColleges,
                "current_page"    => (string) $savedColleges->currentPage(),
                "last_page"       => (string) $savedColleges->lastPage(),
                "per_page"        => (string) $savedColleges->perPage(),
                "total_colleges"  => (string) $savedColleges->total(),
            ],
            "message" => "Saved colleges fetched successfully"
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong. Please try again later."
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


}
