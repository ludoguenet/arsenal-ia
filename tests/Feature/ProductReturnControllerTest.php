<?php

use App\Models\Product;
use App\Models\ProductReturn;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * @return array<string, mixed>
 */
function validProductReturnPayload(Product $product): array
{
    return [
        'product_id' => $product->id,
        'customer_name' => 'Camille Dubois',
        'customer_email' => 'camille.dubois@example.com',
        'reason' => 'L\'écran est arrivé endommagé.',
        'customer_description' => 'La dalle présente une fissure en bas à gauche.',
    ];
}

it('shows the return form with only active products', function () {
    $activeProduct = Product::factory()->create(['name' => 'Clavier mécanique']);
    Product::factory()->inactive()->create(['name' => 'Webcam HD']);

    $response = $this->get(route('product-returns.create'));

    $response->assertOk()
        ->assertSee($activeProduct->name)
        ->assertDontSee('Webcam HD');
});

it('creates a product return request', function () {
    $product = Product::factory()->create();

    $response = $this->post(route('product-returns.store'), validProductReturnPayload($product));

    $productReturn = ProductReturn::query()->sole();

    $response->assertRedirect(route('product-returns.show', $productReturn))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status');

    expect($productReturn->product_id)->toBe($product->id)
        ->and($productReturn->customer_name)->toBe('Camille Dubois')
        ->and($productReturn->customer_email)->toBe('camille.dubois@example.com')
        ->and($productReturn->reason)->toBe('L\'écran est arrivé endommagé.')
        ->and($productReturn->customer_description)->toBe('La dalle présente une fissure en bas à gauche.');
});

it('requires all customer fields', function () {
    $response = $this->from(route('product-returns.create'))->post(route('product-returns.store'), []);

    $response->assertRedirect(route('product-returns.create'))
        ->assertSessionHasErrors(['product_id', 'customer_name', 'customer_email', 'reason', 'customer_description']);

    expect(ProductReturn::query()->count())->toBe(0);
});

it('rejects invalid field values', function (string $field, mixed $value) {
    $product = Product::factory()->create();

    $response = $this->post(
        route('product-returns.store'),
        [...validProductReturnPayload($product), $field => $value],
    );

    $response->assertSessionHasErrors([$field]);
})->with([
    'non-integer product id' => ['product_id', 'abc'],
    'invalid email' => ['customer_email', 'not-an-email'],
    'too long customer name' => ['customer_name', str_repeat('a', 256)],
    'non-string reason' => ['reason', ['array']],
]);

it('rejects a return request for a nonexistent product', function () {
    $response = $this->post(
        route('product-returns.store'),
        [...validProductReturnPayload(Product::factory()->make()), 'product_id' => 999],
    );

    $response->assertSessionHasErrors(['product_id']);

    expect(ProductReturn::query()->count())->toBe(0);
});

it('rejects a return request for an inactive product', function () {
    $product = Product::factory()->inactive()->create();

    $response = $this->post(route('product-returns.store'), validProductReturnPayload($product));

    $response->assertSessionHasErrors(['product_id']);

    expect(ProductReturn::query()->count())->toBe(0);
});

it('ignores triage fields submitted by the client', function () {
    $product = Product::factory()->create();

    $response = $this->post(route('product-returns.store'), [
        ...validProductReturnPayload($product),
        'category' => 'defective',
        'damage_level' => 'high',
        'priority' => 'urgent',
        'triage_summary' => 'Injected summary.',
        'recommended_action' => 'review_refund',
        'requires_human_review' => false,
        'triaged_at' => '2026-01-01 00:00:00',
    ]);

    $response->assertSessionHasNoErrors();

    $productReturn = ProductReturn::query()->sole();

    expect($productReturn->category)->toBeNull()
        ->and($productReturn->damage_level)->toBeNull()
        ->and($productReturn->priority)->toBeNull()
        ->and($productReturn->triage_summary)->toBeNull()
        ->and($productReturn->recommended_action)->toBeNull()
        ->and($productReturn->requires_human_review)->toBeNull()
        ->and($productReturn->triaged_at)->toBeNull();
});

it('shows a product return with its product and triage fields', function () {
    $productReturn = ProductReturn::factory()->triaged()->create();

    $response = $this->get(route('product-returns.show', $productReturn));

    $response->assertOk()
        ->assertSee($productReturn->customer_email)
        ->assertSee($productReturn->product->name)
        ->assertSee('damaged_on_arrival')
        ->assertSee('medium')
        ->assertSee('high')
        ->assertSee($productReturn->triage_summary)
        ->assertSee('offer_replacement')
        ->assertSee('Non requise')
        ->assertSee($productReturn->triaged_at->format('d/m/Y H:i'));
});

it('shows placeholders when a product return has not been triaged', function () {
    $productReturn = ProductReturn::factory()->create();

    $response = $this->get(route('product-returns.show', $productReturn));

    $response->assertOk()
        ->assertSee('Non évalué')
        ->assertSee('Non classée')
        ->assertSee('Non définie')
        ->assertSee('Aucun résumé disponible.')
        ->assertSee('Non déterminée')
        ->assertSee('Pas encore triée');
});

it('returns not found for a missing product return', function () {
    $response = $this->get(route('product-returns.show', 999));

    $response->assertNotFound();
});

it('preselects the product passed in the query string', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('product-returns.create', ['product_id' => $product->id]));

    $response->assertOk()->assertSee('value="'.$product->id.'" selected', escape: false);
});
