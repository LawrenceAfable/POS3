<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Default route
Route::get('/', function () {
  return view('layouts.master');
});


// Navigate to the index
Route::get('/product-api', [ProductController::class, 'index'])->name('products.index'); // Fetch products from API
Route::get('/product-local', [ProductController::class, 'index1'])->name('products.index1'); // Fetch products from API

// Save data from API to Database
Route::get('/products/store', [ProductController::class, 'saveFromApi'])->name('saveFromApi');

// Show the form to create a new product
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Store a new product
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Show the form to edit an existing product
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Update an existing product
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

// Delete a product
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

