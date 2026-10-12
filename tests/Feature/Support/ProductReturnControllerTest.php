<?php

use App\Enums\ReturnCategory;
use App\Enums\ReturnPriority;
use App\Models\ProductReturn;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists product returns with their product and triage fields', function () {
    $productReturn = ProductReturn::factory()->triaged()->create(['customer_name' => 'Camille Dubois']);

    $response = $this->get(route('support.product-returns.index'));

    $response->assertOk()
        ->assertSee('Camille Dubois')
        ->assertSee($productReturn->product->name)
        ->assertSee('damaged_on_arrival')
        ->assertSee('medium')
        ->assertSee('high')
        ->assertSee('offer_replacement')
        ->assertDontSee($productReturn->customer_email);
});

it('paginates product returns', function () {
    ProductReturn::factory()->count(16)->create();

    $response = $this->get(route('support.product-returns.index'));

    $response->assertOk()->assertViewHas(
        'productReturns',
        fn ($productReturns) => $productReturns->count() === 15 && $productReturns->total() === 16,
    );

    $this->get(route('support.product-returns.index', ['page' => 2]))
        ->assertViewHas('productReturns', fn ($productReturns) => $productReturns->count() === 1);
});

it('filters product returns', function (array $filters, array $matching, array $other) {
    $expected = ProductReturn::factory()->triaged()->create($matching);
    ProductReturn::factory()->triaged()->create($other);
    ProductReturn::factory()->create();

    $response = $this->get(route('support.product-returns.index', $filters));

    $response->assertOk()->assertViewHas(
        'productReturns',
        fn ($productReturns) => $productReturns->pluck('id')->all() === [$expected->id],
    );
})->with([
    'category' => [
        ['category' => 'missing_parts'],
        ['category' => ReturnCategory::MissingParts],
        ['category' => ReturnCategory::Defective],
    ],
    'priority' => [
        ['priority' => 'urgent'],
        ['priority' => ReturnPriority::Urgent],
        ['priority' => ReturnPriority::Low],
    ],
    'requires human review' => [
        ['requires_human_review' => '1'],
        ['requires_human_review' => true],
        ['requires_human_review' => false],
    ],
    'does not require human review' => [
        ['requires_human_review' => '0'],
        ['requires_human_review' => false],
        ['requires_human_review' => true],
    ],
]);

it('rejects invalid filter values', function (string $filter, string $value) {
    $response = $this->get(route('support.product-returns.index', [$filter => $value]));

    $response->assertRedirect(route('support.product-returns.index'))
        ->assertSessionHasErrors([$filter]);
})->with([
    'category' => ['category', 'shipping_damage'],
    'priority' => ['priority', 'critical'],
    'requires human review' => ['requires_human_review', 'maybe'],
]);
