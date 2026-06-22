<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollegeFeeStructure;
use App\Models\CollegeImage;
class DashboardController extends Controller
{
  public function index()
{
    $college = auth()->guard('college')->user();

    return view('college.dashboard', [
        'college'            => $college,
        'images'             => $college->images,
        'totalImages'        => $college->images()->count(),
        'totalCourses'       => $college->collegeCourses()->count(),
        'totalFeeStructures' => CollegeFeeStructure::whereHas('collegeCourse', function($q) use ($college) {
                            $q->where('college_id', $college->id);
                        })->count(),
        'totalViews'         => 0,
    ]);
}
}
