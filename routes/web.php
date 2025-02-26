<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;

// page routes
Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile'])->name('userProfilePage');

// Registration and OTP routes
Route::get("/register", [UserController::class,"UserRegister"])->name('register');
Route::get('/sendotp', [UserController::class,'SendOtpPage'])->name('forgot');
Route::get('/verifyotp', [UserController::class,'VerifyOtpPage'])->name('verifyotp');
Route::get('/resetpass', [UserController::class,'ResetPasswordPage'])->middleware('auth:sanctum')->name('resetPass');
Route::get('/login', function () {
    return view('componands.login_form');
})->name('login');
Route::get('/', function () {
    return view('componands.login_form');
})->name('login');


// User web routes
Route::post('/userRegister', [UserController::class, 'Useregister'])->name('UserRegister');
Route::post('/userLogin', [UserController::class, 'UserLogin'])->name('UserLogin');
Route::get('/userProfile', [UserController::class,'UserProfile'])->middleware('auth:sanctum')->name('userProfile');
Route::post('/sendOTP', [UserController::class,'SendOTP'])->name('sendOTP');
Route::post('/verify-otp', [UserController::class, 'VerifyOTP'])->name('verifyOTP');
Route::post('/resetUserPass', [UserController::class,'ResetPassword'])->middleware('auth:sanctum')->name("resetPassword");
Route::get('/userLogout', [UserController::class,'UserLogout'])->middleware('auth:sanctum')->name('userLogout');

// Customer web routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/createCustomer', [CustomerController::class, 'CreateCustomer']);
    Route::get('/customerList', [CustomerController::class, 'CustomerList']);
    Route::post('/deleteCustomer', [CustomerController::class, 'DeleteCustomer']);
    Route::post('/updateCustomer', [CustomerController::class, 'UpdateCustomer']);
});
