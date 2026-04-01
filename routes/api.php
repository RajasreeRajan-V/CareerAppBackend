<?php

use App\Http\Controllers\SignupController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AdmisionBannerController;
use App\Http\Controllers\api\SearchCollegeController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\CareerBannerController;
use App\Http\Controllers\api\CareernodeController;
use App\Http\Controllers\api\CareerRecordVideoController;
use App\Http\Controllers\api\CareerGuidanceBannerController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('signup', [SignupController::class, 'store']);
Route::post('login', [SignupController::class,'login']);
Route::get('admision-banners', [AdmisionBannerController::class, 'index']);
Route::get('career-banners', [CareerBannerController::class, 'index']);
Route::get('career-home-nodes', [CareernodeController::class, 'getCareerHomeNodes']);
Route::post('forgot-password', [SignupController::class, 'forgotPassword']);
Route::post('reset-password', [SignupController::class, 'resetPassword']);
Route::get('career-guidance-banners', [CareerGuidanceBannerController::class, 'index']);
Route::post('career-guidance-register', [CareerGuidanceBannerController::class, 'register']);
Route::get('states', [SearchCollegeController::class, 'getStates']);
Route::post('districts', [SearchCollegeController::class, 'getDistricts']);

Route::middleware('auth.token')->group(function () {
    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::post('search-colleges', [SearchCollegeController::class, 'searchColleges']);
    
    Route::post('college-details', [SearchCollegeController::class, 'collegeDetails']);
    
    Route::post('save-college', [SearchCollegeController::class, 'saveCollege']);
    Route::delete('remove-saved-college', [SearchCollegeController::class, 'removeSavedCollege']);
    Route::get('saved-colleges', [SearchCollegeController::class, 'getSavedColleges']);
    Route::post('update-profile', [ProfileController::class, 'updateProfile']);
    Route::post('change-password', [ProfileController::class, 'changePassword']);
    Route::post('search-careernodes', [CareernodeController::class, 'searchCareerNodes']);
    Route::post('careernode-details', [CareernodeController::class, 'careerNodeDetails']);
    Route::post('/career-child-nodes', [CareernodeController::class, 'getChildCareerNodes']);
    Route::post('/career-child-basic', [CareernodeController::class, 'getChildCareerNodesBasic']);
    Route::get('career-record-videos/home', [CareerRecordVideoController::class, 'homeVideos']);
    Route::get('career-record-videos', [CareerRecordVideoController::class, 'index']);

});

