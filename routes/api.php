<?php

use App\Http\Controllers\Api\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::bind('product', fn (string $value) => Product::active()->findOrFail($value));

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
