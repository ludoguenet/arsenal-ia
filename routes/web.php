<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\Support\ProductReturnController as SupportProductReturnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('products', [ProductController::class, 'index'])->name('products.list');

Route::resource('product-returns', ProductReturnController::class)->only(['create', 'store', 'show']);

Route::get('support/product-returns', [SupportProductReturnController::class, 'index'])->name('support.product-returns.index');
