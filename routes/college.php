<?php

namespace App\Http\Controllers\College;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\College\CollegeLoginController;
use App\Http\Controllers\College\DashboardController;


Route::name('college.')->group(function () {
    Route::post("/do-login", [CollegeLoginController::class,'doCollegeLogin'])->name('do.login');
   
     Route::middleware(['auth:college'])->group(function () {

        Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

        // Route::post('/logout',[DashboardController::class,'logout'])->name('college.logout');

    });

});