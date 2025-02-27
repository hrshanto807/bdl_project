<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

// page routes
Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile'])->name('userProfilePage');

// Registration and OTP routes
Route::get("/register", [UserController::class,"UserRegister"])->name('register');
Route::get('/sendotp', [UserController::class,'SendOtpPage'])->name('forgot');
Route::get('/verifyotp', [UserController::class,'VerifyOtpPage'])->name('verifyotp');
Route::get('/resetpass', [UserController::class,'ResetPasswordPage'])->name('resetPass');
Route::get('/login', [UserController::class,'LoginPage'])->name('login');
Route::get('/', [UserController::class, 'dashboard'])->name('home')->middleware('auth:sanctum');
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware('auth:sanctum');


// User web routes
Route::post('/userRegister', [UserController::class, 'Useregister'])->name('UserRegister');
Route::post('/userLogin', [UserController::class, 'UserLogin'])->name('UserLogin');
Route::get('/userProfile', [UserController::class,'UserProfile'])->middleware('auth:sanctum')->name('userProfile');
Route::post('/sendOTP', [UserController::class,'SendOTP'])->name('sendOTP');
Route::post('/verify-otp', [UserController::class, 'VerifyOTP'])->name('verifyOTP');
Route::post('/resetUserPass', [UserController::class,'ResetPassword'])->middleware('auth:sanctum')->name("resetPassword");
Route::get('/userLogout', [UserController::class,'UserLogout'])->middleware('auth:sanctum')->name('userLogout');
Route::put('/updateProfile', [UserController::class, 'UpdateProfile'])->middleware('auth:sanctum')->name('updateProfile');



// Customer web routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/createCustomer', [CustomerController::class, 'CreateCustomer']);
    Route::get('/customerList', [CustomerController::class, 'CustomerList']);
    Route::post('/deleteCustomer', [CustomerController::class, 'DeleteCustomer']);
    Route::post('/updateCustomer', [CustomerController::class, 'UpdateCustomer']);
});


// category routes

Route::middleware('auth:sanctum')->group(function () {    
    Route::get('/categoryList', [CategoryController::class, 'CategoryList'])->name('categoryList');
    Route::post('/createCategory', [CategoryController::class, 'CreateCategory'])->name('createCategory');
    Route::post('/deleteCategory', [CategoryController::class, 'DeleteCategory'])->name('deleteCategory');
    Route::post('/updateCategory', [CategoryController::class,  'UpdateCategory'])->name('updateCategory');
});


// Product routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/productList', [ProductController::class, 'ProductList'])->name('productList');
    Route::post('/createProduct', [ProductController::class, 'CreateProduct'])->name('createProduct');
    Route::post('/deleteProduct', [ProductController::class, 'ProductDelete'])->name('deleteProduct');
    Route::post('/updateProduct', [ProductController::class, 'UpdateProduct'])->name('updateProduct');
});


