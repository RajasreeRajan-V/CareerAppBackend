<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\College\CollegeLoginController;
use App\Http\Controllers\College\DashboardController;
use App\Http\Controllers\College\CollegeManageController;
use App\Http\Controllers\College\ChangePasswordController;
use App\Http\Controllers\College\CollegeFacilityController;
use App\Http\Controllers\College\CollegeCourseController;
use App\Http\Controllers\College\CollegeFeeController;
use App\Http\Controllers\College\CollegeImageController;
use App\Http\Controllers\College\CollegeViewersController;

Route::name('college.')->group(function () {

    // Public
    Route::post('/do-login', [CollegeLoginController::class, 'doCollegeLogin'])->name('do.login');

    // Auth required
    Route::middleware(['auth:college'])->group(function () {

        // Change password — outside password_changed middleware so first-login colleges can access it
        Route::get('/change-password',  [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
        Route::post('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('password.update');

        // All routes below require password to have been changed
        Route::middleware(['college.password_changed'])->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('dashboard/viewers', [CollegeViewersController::class, 'index'])->name('dashboard.viewers');

            Route::resource('collegeEdit', CollegeManageController::class);
            Route::resource('collegeCourse', CollegeCourseController::class);
            Route::resource('facilities', CollegeFacilityController::class)->except(['show', 'create', 'edit']);
            Route::resource('images', CollegeImageController::class)->except(['create', 'edit', 'show']);

            // Fee Structure
            Route::get('feeStructure',                              [CollegeFeeController::class, 'index'])->name('feeStructure.index');
            Route::get('feeStructure/create/{course}',              [CollegeFeeController::class, 'create'])->name('feeStructure.create');
            Route::post('feeStructure/{course}',                    [CollegeFeeController::class, 'store'])->name('feeStructure.store');
            Route::get('feeStructure/{feeStructure}',               [CollegeFeeController::class, 'show'])->name('feeStructure.show');
            Route::get('feeStructure/{feeStructure}/edit',          [CollegeFeeController::class, 'edit'])->name('feeStructure.edit');
            Route::put('/feeStructure/{course}/{feeStructure}',     [CollegeFeeController::class, 'update'])->name('feeStructure.update');
            Route::delete('feeStructure/{feeStructure}',            [CollegeFeeController::class, 'destroy'])->name('feeStructure.destroy');

        });
    });

});