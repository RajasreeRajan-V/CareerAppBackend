<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerRecordVideo;
use Illuminate\Support\Facades\Validator;

class CareerRecordVideoController extends Controller
{

    /**
     * Home page preview (latest 5 videos)
     */
 public function homeVideos()
{
    try {
        $videos = CareerRecordVideo::inRandomOrder()
                    ->limit(5)
                    ->get();
        if ($videos->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "stored" => "0",
                ],
                "message" => "No videos found"
            ], 200);
        }
        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "stored" => "0",
                "videos" => $videos
            ],
            "message" => "Videos fetched successfully"
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

public function index(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }
        $perPage = $request->per_page ?? 10;
        $videos = CareerRecordVideo::inRandomOrder()->paginate($perPage);
        if ($videos->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "stored" => "0",
                ],
                "message" => "No videos found"
            ], 200);
        }
        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "stored" => "0",
                "videos" => $videos->items(),
                "current_page" => (string) $videos->currentPage(),
                "last_page" => (string) $videos->lastPage(),
                "per_page" => (string) $videos->perPage(),
                "total_videos" => (string) $videos->total(),
            ],
            "message" => "Videos fetched successfully"
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