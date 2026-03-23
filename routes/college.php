<?php

namespace App\Http\Controllers\College;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\College\CollegeLoginController;
use App\Http\Controllers\College\DashboardController;
use App\Http\Controllers\College\CollegeManageController;
use App\Http\Controllers\College\ChangePasswordController;

Route::name('college.')->group(function () {
    Route::post("/do-login", [CollegeLoginController::class,'doCollegeLogin'])->name('do.login');
    
        Route::middleware(['auth:college'])->group(function () {
            Route::get('/change-password',  [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
            Route::post('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('password.update');

            Route::resource('collegeEdit', CollegeManageController::class);
            Route::resource('collegeCourse', CollegeCourseController::class);

           Route::middleware('college.password_changed')->group(function () {
                Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            });
            // Route::post('/logout',[DashboardController::class,'logout'])->name('college.logout');

        });

});