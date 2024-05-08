<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::controller(AuthController::class)->group(function () {
    Route::post("/login", 'login');
    Route::post("/register", 'create');
    Route::post('/customer/support', 'contactSupport');
});

Route::controller(EmailVerificationController::class)->group(function () {
    Route::post("/verify-email", 'verificationNotification');
    Route::get('/verify-email/{token}/{email}', 'verify');
});

Route::controller(PasswordResetController::class)->group(function () {
    Route::post("/password/email", 'passwordResetEmail');
    Route::post('/password/reset', 'changePassword');
});


Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {

        Route::post('/user/change-password', 'changePassword');
        Route::post('/logout', 'logout');
        Route::post('/logout-all-device', 'logoutFromAllDevice');
        Route::get("/user", "user");

    });
});
