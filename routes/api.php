<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Models\Invoice;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::delete('/product/{id}', [ProductController::class, 'deleteProduct'])->middleware('auth:sanctum');

// routes/api.php
Route::get('/products', [ProductController::class, 'product_list']);


