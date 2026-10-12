<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists only active products', function () {
    $activeProducts = Product::factory()->count(2)->create();
    Product::factory()->inactive()->create();

    $response = $this->getJson(route('products.index'));

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.id', $activeProducts[0]->id)
        ->assertJsonPath('data.1.id', $activeProducts[1]->id);
});

it('shows an active product', function () {
    $product = Product::factory()->create([
        'name' => 'Clavier mécanique',
        'description' => 'Clavier compact.',
        'price' => 89.90,
    ]);

    $response = $this->getJson(route('products.show', $product));

    $response->assertOk()
        ->assertJsonPath('data.id', $product->id)
        ->assertJsonPath('data.name', 'Clavier mécanique')
        ->assertJsonPath('data.description', 'Clavier compact.')
        ->assertJsonPath('data.price', '89.90')
        ->assertJsonMissingPath('data.is_active');
});

it('returns not found for an inactive product', function () {
    $product = Product::factory()->inactive()->create();

    $response = $this->getJson(route('products.show', $product));

    $response->assertNotFound();
});

it('returns not found for a missing product', function () {
    $response = $this->getJson(route('products.show', 999));

    $response->assertNotFound();
});
