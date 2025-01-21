<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::get('/products', [ProductController::class, 'index']); // Fetch all products
// Route::get('/products/{id}', [ProductController::class, 'show']); // Fetch a single product
// Route::post('/products', [ProductController::class, 'store']); // Create a new product
// Route::put('/products/{id}', [ProductController::class, 'update']); // Update a product
// Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Delete a product
