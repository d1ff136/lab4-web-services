<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog', [CatalogController::class, 'index'])->name('api.catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('api.catalog.show');

// Часткове оновлення лише через PATCH (PUT повертає 405).
Route::apiResource('products', ProductController::class)->except('update')->names('api.products');
Route::patch('/products/{product}', [ProductController::class, 'update'])->name('api.products.update');

Route::apiResource('categories', CategoryController::class)->except('update')->names('api.categories');
Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('api.categories.update');