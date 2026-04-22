<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CollegeImage;
class DashboardController extends Controller
{
  public function index()
    {
        $college = auth()->guard('college')->user();
        $images = $college->images;
        return view('college.dashboard', compact('college', 'images'));
    }
}
