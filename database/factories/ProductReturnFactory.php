<?php

namespace Database\Factories;

use App\Enums\DamageLevel;
use App\Enums\RecommendedAction;
use App\Enums\ReturnCategory;
use App\Enums\ReturnPriority;
use App\Models\Product;
use App\Models\ProductReturn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductReturn>
 */
class ProductReturnFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'reason' => fake()->sentence(),
            'customer_description' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the return has already been triaged.
     */
    public function triaged(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => ReturnCategory::DamagedOnArrival,
            'damage_level' => DamageLevel::Medium,
            'priority' => ReturnPriority::High,
            'triage_summary' => fake()->sentence(),
            'recommended_action' => RecommendedAction::OfferReplacement,
            'requires_human_review' => false,
            'triaged_at' => now(),
        ]);
    }
}
