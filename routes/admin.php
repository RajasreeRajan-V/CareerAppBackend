<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdmisionBannerController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\CareerNodeController;
use App\Http\Controllers\Admin\CareerLinkController;
use App\Http\Controllers\Admin\CareerBannerController;
use App\Http\Controllers\Admin\CareerRecordVideoController;
use App\Http\Controllers\Admin\CollegeRegistrationController;
use App\Http\Controllers\Admin\CareerGuidanceBannerController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->group(function () {
    Route::post("/do-login", [LoginController::class,'doLogin'])->name('do.login');
   
     Route::middleware(['auth:admin'])->group(function () {

        Route::get("/dashboard", [DashboardController::class,'dashboard'])->name('dashboard');
        
        Route::get('/search', [DashboardController::class, 'search'])->name('search');

        Route::resource('admissionBanner', AdmisionBannerController::class);
        
        Route::resource('college', CollegeController::class);
        
        Route::resource('career_nodes', CareerNodeController::class);
        
         Route::get('/college/{id}/edit-json', [CollegeController::class, 'editJson'])->name('college.edit-json');

        Route::resource('career_link' , CareerLinkController::class);

        Route::resource('careerBanner' , CareerBannerController::class);

        Route::resource('RecordVideo' , CareerRecordVideoController::class);

        Route::resource('guidance_banners' , CareerGuidanceBannerController::class);

        Route::resource('college_registration' , CollegeRegistrationController::class);

        Route::resource('createLocation', LocationController::class);

        Route::resource('userManagement', UserManageController::class);
        
     });
});
