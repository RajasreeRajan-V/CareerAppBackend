<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerBanner;

class CareerBannerController extends Controller
{
    public function index()
    {
        try {

            $banners = CareerBanner::latest()->get();

            if ($banners->isEmpty()) {
                return response()->json([
                    "status" => "1",
                    "status_code" => "200",
                    "data" => (object)[],
                    "message" => "No career banners found"
                ], 200);
            }

            $formattedBanners = [];

            foreach ($banners as $banner) {
                $formattedBanners[] = [
                    "id" => (string) $banner->id,
                    "title" => $banner->title,
                    "image" => $banner->image 
                        ? asset('storage/' . $banner->image)
                        : null,
                ];
            }

            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "banners" => $formattedBanners,
                    "total_banners" => (string) count($formattedBanners)
                ],
                "message" => "Career banners fetched successfully"
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
