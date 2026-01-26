<?php

use App\Http\Controllers\SignupController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AdmisionBannerController;
use App\Http\Controllers\api\SearchCollegeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('signup', [SignupController::class, 'store']);

Route::post('login', [SignupController::class,'login']);

Route::get('admision-banners', [AdmisionBannerController::class, 'index']);

Route::middleware('auth.token')->group(function () {
    Route::post('search-colleges', [SearchCollegeController::class, 'searchColleges']);
    Route::post('college-details', [SearchCollegeController::class, 'collegeDetails']);
});






