<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AdmisionBanner;
class AdmisionBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        $banners = AdmisionBanner::latest()->get();
        return response()->json([
            'status' => "1",
            'status_code' => "200",
            'data' => [
                'banners' => $banners->map(function ($banner) {
                    return [
                        'id' => (string) $banner->id,
                        'title' => $banner->title,
                        'image_url' => asset('storage/' . $banner->image),
                        'description' => $banner->description ?? '',
                        'link' => $banner->link ?? '',
                    ];
                }),
                'total_banners' => (string) $banners->count(),
            ],
            'message' => 'Banners fetched successfully'
        ], 200);
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
       $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        'description' => 'nullable|string',
        'link' => 'nullable|url',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => "0",
            'status_code' => "422",
            'data' => (object)[],
            'message' => $validator->errors()->first(),
        ], 422);
    }
    $imagePath = $request->file('image')->store('admision-banners', 'public');

    $banner = AdmisionBanner::create([
        'title' => $request->title,
        'image' => $imagePath,
        'description' => $request->description,
        'link' => $request->link,
    ]);
    
     return response()->json([
        'status' => "1",
        'status_code' => "200",
        'data' => [
            'id' => (string) $banner->id,
            'title' => $banner->title,
            'image_url' => asset('storage/' . $banner->image),
            'description' => $banner->description ?? '',
            'link' => $banner->link ?? '',
        ],
        'message' => 'Banner created successfully'
    ], 200);
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
}
