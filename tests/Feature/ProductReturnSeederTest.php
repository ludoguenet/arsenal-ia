<?php

use App\Models\ProductReturn;
use Database\Seeders\ProductReturnSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds the demonstration scenario once with empty triage fields', function () {
    $this->seed(ProductReturnSeeder::class);
    $this->seed(ProductReturnSeeder::class);

    $scenario = ProductReturn::query()
        ->where('customer_email', ProductReturnSeeder::DEMO_CUSTOMER_EMAIL)
        ->sole();

    expect($scenario->customer_name)->toBe('Sophie Martin')
        ->and($scenario->reason)->toBe('Product damaged on arrival')
        ->and($scenario->product->name)->toBe('Casque à réduction de bruit')
        ->and($scenario->product->is_active)->toBeTrue()
        ->and(ProductReturn::query()->count())->toBe(6 + ProductReturnSeeder::GENERATED_RETURNS_COUNT)
        ->and(ProductReturn::query()->whereNotNull('category')->orWhereNotNull('damage_level')->orWhereNotNull('priority')
            ->orWhereNotNull('triage_summary')->orWhereNotNull('recommended_action')
            ->orWhereNotNull('requires_human_review')->orWhereNotNull('triaged_at')->exists())->toBeFalse();
});
