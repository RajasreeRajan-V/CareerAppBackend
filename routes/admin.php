<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;

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
     });
});
