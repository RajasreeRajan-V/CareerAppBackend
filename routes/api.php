<?php

use App\Http\Controllers\SignupController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdmisionBannerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('signup', [SignupController::class, 'store']);

Route::post('login', [SignupController::class,'login']);

Route::apiResource('admision-banners', AdmisionBannerController::class);




