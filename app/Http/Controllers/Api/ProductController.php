<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * List the active products.
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::active()->orderBy('id')->get());
    }

    /**
     * Show an active product.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}
