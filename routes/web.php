<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;


// page routes

// Authentication
Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile'])->name('userProfilePage');
Route::get("/register", [UserController::class, "UserRegister"])->name('register');
Route::get('/sendotp', [UserController::class, 'SendOtpPage'])->name('forgot');
Route::get('/verifyotp', [UserController::class, 'VerifyOtpPage'])->name('verifyotp');
Route::get('/resetpass', [UserController::class, 'ResetPasswordPage'])->name('resetPass');
Route::get('/login', [UserController::class, 'LoginPage'])->name('login');
Route::get('/', [UserController::class, 'dashboard'])->name('home')->middleware('auth:sanctum');
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware('auth:sanctum');

// Category page routes
Route::get('/addcategory', [CategoryController::class, 'addCategory'])->name('AddCategory');
// Route::get('/listcategory', [CategoryController::class, 'ListCategory'])->name('ListCategory');
Route::get('/deltecategory', [CategoryController::class, "deleteCategory"])->name('DeleteCategory');
Route::get('/eiditcategory', [CategoryController::class, 'editCategory'])->name('EditCategory');

// Product page routes
Route::get('/product-add', [ProductController::class, 'productAdd'])->middleware('auth:sanctum')->name('productAdd');

// Customer page routes
Route::get('/add-customer', [CustomerController::class, 'AddCustomer'])->name('AddCustomer')->middleware('auth:sanctum');



// =============================================================================================================================================================


//  web routes

// User web routes
Route::post('/userRegister', [UserController::class, 'Useregister'])->name('UserRegister');
Route::post('/userLogin', [UserController::class, 'UserLogin'])->name('UserLogin');
Route::middleware('auth:sanctum')->group(function () {  
    Route::get('/userProfile', [UserController::class, 'UserProfile'])->name('userProfile');
    Route::post('/resetUserPass', [UserController::class, 'ResetPassword'])->name("resetPassword");
    Route::get('/userLogout', [UserController::class, 'UserLogout'])->name('userLogout');
    Route::put('/updateProfile', [UserController::class, 'UpdateProfile'])->name('updateProfile');
});

Route::post('/sendOTP', [UserController::class, 'SendOTP'])->name('sendOTP');
Route::post('/verify-otp', [UserController::class, 'VerifyOTP'])->name('verifyOTP');



// Customer web routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/createCustomer', [CustomerController::class, 'CreateCustomer'])->name('CreateCustomer');
    Route::get('/customerList', [CustomerController::class, 'CustomerList'])->name('customerList');
    Route::post('/deleteCustomer', [CustomerController::class, 'DeleteCustomer'])->name('deleteCustomer');
    Route::post('/updateCustomer', [CustomerController::class, 'UpdateCustomer'])->name('editCustomer');
});


// category routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categoryList', [CategoryController::class, 'CategoryList'])->name('categoryList');
    Route::post('/createCategory', [CategoryController::class, 'CreateCategory'])->name('createCategory');
    Route::post('/deleteCategory', [CategoryController::class, 'DeleteCategory'])->name('deleteCategory');
    Route::post('/updateCategory', [CategoryController::class,  'UpdateCategory'])->name('updateCategory');
    Route::get('/get-categories', [CategoryController::class, 'getCategories']);
});


// Product routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/productList', [ProductController::class, 'ProductList'])->name('productList');
    Route::post('/createProduct', [ProductController::class, 'CreateProduct'])->name('createProduct');
    Route::post('/deleteProduct', [ProductController::class, 'ProductDelete'])->name('deleteProduct');
    Route::post('/updateProduct', [ProductController::class, 'ProductUpdate'])->name('updateProduct');
});

// Invoice routes
Route::post("/invoice-create",[InvoiceController::class,'invoiceCreate'])->middleware('auth:sanctum')->name('invoiceCreate');
Route::get("/invoice-select",[InvoiceController::class,'invoiceSelect'])->middleware('auth:sanctum');
Route::post("/invoice-details",[InvoiceController::class,'InvoiceDetails'])->middleware('auth:sanctum')->name('invoiceDetails');
// In routes/web.php
Route::post('/invoice-delete', [InvoiceController::class, 'invoiceDelete'])->name('deleteInvoice')->middleware('auth:sanctum');
Route::get('/invoice-list', [InvoiceController::class, 'invoiceList'])->name('invoiceList')->middleware('auth:sanctum');
Route::get('/customers', [InvoiceController::class, 'customer_list'])->name('customerlist')->middleware('auth:sanctum');
Route::get('/products', [ProductController::class, 'product_list'])->name('productlist')->middleware('auth:sanctum');
route::get('/edit-invoice', [InvoiceController::class, 'editInvoice'])->name('editInvoice')->middleware('auth:sanctum');

// Route::get('/invoice', [InvoiceController::class, 'showInvoice'])->name('')->middleware('');
// Report
Route::get("/sales-report/{FormDate}/{ToDate}",[ReportController::class,'SalesReport'])->middleware('auth:sanctum');













