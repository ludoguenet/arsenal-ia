<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductReturnRequest;
use App\Models\Product;
use App\Models\ProductReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductReturnController extends Controller
{
    /**
     * Show the form to submit a product return request.
     */
    public function create(): View
    {
        return view('product-returns.create', [
            'products' => Product::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a new product return request.
     */
    public function store(StoreProductReturnRequest $request): RedirectResponse
    {
        $productReturn = ProductReturn::query()->create($request->validated());

        return redirect()
            ->route('product-returns.show', $productReturn)
            ->with('status', 'Votre demande de retour a bien été enregistrée.');
    }

    /**
     * Show a product return request.
     */
    public function show(ProductReturn $productReturn): View
    {
        return view('product-returns.show', [
            'productReturn' => $productReturn->load('product'),
        ]);
    }
}
