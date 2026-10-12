<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists only active products', function () {
    $activeProduct = Product::factory()->create(['name' => 'Clavier mécanique']);
    Product::factory()->inactive()->create(['name' => 'Webcam HD']);

    $response = $this->get(route('products.list'));

    $response->assertOk()
        ->assertSee('Clavier mécanique')
        ->assertSee(route('product-returns.create', ['product_id' => $activeProduct->id]), escape: false)
        ->assertDontSee('Webcam HD');
});

it('shows a message when no product is available', function () {
    $response = $this->get(route('products.list'));

    $response->assertOk()->assertSee('Aucun produit disponible.');
});
