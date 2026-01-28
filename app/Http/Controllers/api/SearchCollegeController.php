<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\College;
use Illuminate\Support\Facades\Validator;

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
            'keyword' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }

        $keyword = trim($request->keyword ?? '');
        $location = trim($request->location ?? '');

        $query = College::with('courses');

        // Apply keyword filter
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                  ->orWhereHas('courses', function ($courseQuery) use ($keyword) {
                      $courseQuery->where('name', 'LIKE', "%$keyword%");
                  });
            });
        }

        // Apply location filter
        if (!empty($location)) {
            $query->where('location', 'LIKE', "%$location%");
        }

        $colleges = $query->get();

        // No data found
        if ($colleges->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => (object)[],
                "message" => "No colleges found"
            ], 200);
        }


        $formattedColleges = [];

        foreach ($colleges as $college) {
            $formattedColleges[] = [
                "id" => (string) $college->id,
                "name" => $college->name,
                "location" => $college->location,
                "rating" => (string) $college->rating,
                "courses" => $college->courses->pluck('name')->implode(', ')
            ];
        }

        // Success response
        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "colleges" => $formattedColleges,
                "total_colleges" => (string) count($formattedColleges)
            ],
            "message" => "Colleges fetched successfully"
        ], 200);

    } catch (\Exception $e) {
        // Server error
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

        // Fetch college with relations
        $college = College::with(['courses', 'images', 'facilities'])
            ->find($request->id);

        // If not found
        if (!$college) {
            return response()->json([
                "status" => "0",
                "status_code" => "404",
                "data" => (object)[],
                "message" => "College not found"
            ], 404);
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


}
