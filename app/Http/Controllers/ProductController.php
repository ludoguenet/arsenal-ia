<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * List the active products.
     */
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::active()->orderBy('name')->get(),
        ]);
    }
}
