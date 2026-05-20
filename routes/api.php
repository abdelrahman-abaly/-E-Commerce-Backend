<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;


// ==================== Public Routes ====================
Route::prefix('auth')->name('auth.')->group(function () {

    Route::post('register', RegisterController::class)->name('register');
    Route::post('login',    LoginController::class)->name('login');

    // Password Reset
    Route::post('forgot-password', [PasswordResetController::class, 'forgot'])->name('password.forgot');
    Route::post('reset-password',  [PasswordResetController::class, 'reset'])->name('password.reset');
});

// ==================== Protected Routes ====================
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('logout',     [LogoutController::class, 'logout'])->name('logout');
        Route::post('logout-all', [LogoutController::class, 'logoutAll'])->name('logout.all');

        // Email Verification
        Route::post('email/resend', [EmailVerificationController::class, 'send'])
            ->name('verification.send');

        Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->middleware('signed') // التوقيع الرقمي للحماية
            ->name('verification.verify');
    });

    // ==================== Customer Routes ====================
    Route::middleware('role:customer,admin')->group(function () {
        // هنا هيجي Cart, Orders, Profile في المراحل الجاية
    });

    // ==================== Admin Routes ====================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // هنا هيجي Product management, User management, ...
    });
});




Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // ==================== Admin ====================
    Route::prefix('admin')->group(function () {

        // Categories
        Route::apiResource('categories', CategoryController::class);

        Route::apiResource('products', AdminProductController::class);
    });
});
