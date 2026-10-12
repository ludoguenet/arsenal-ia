<?php

namespace App\Http\Controllers\Support;

use App\Enums\ReturnCategory;
use App\Enums\ReturnPriority;
use App\Http\Controllers\Controller;
use App\Http\Requests\Support\IndexProductReturnRequest;
use App\Models\ProductReturn;
use Illuminate\View\View;

class ProductReturnController extends Controller
{
    /**
     * List the product return requests for the support team.
     */
    public function index(IndexProductReturnRequest $request): View
    {
        $productReturns = ProductReturn::query()
            ->with('product')
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->enum('category', ReturnCategory::class)))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->enum('priority', ReturnPriority::class)))
            ->when($request->filled('requires_human_review'), fn ($query) => $query->where('requires_human_review', $request->boolean('requires_human_review')))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('support.product-returns.index', [
            'productReturns' => $productReturns,
            'categories' => ReturnCategory::cases(),
            'priorities' => ReturnPriority::cases(),
        ]);
    }
}
