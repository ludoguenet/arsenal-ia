<?php

namespace App\Models;

use App\Enums\DamageLevel;
use App\Enums\RecommendedAction;
use App\Enums\ReturnCategory;
use App\Enums\ReturnPriority;
use Database\Factories\ProductReturnFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'customer_name', 'customer_email', 'reason', 'customer_description'])]
class ProductReturn extends Model
{
    /** @use HasFactory<ProductReturnFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => ReturnCategory::class,
            'damage_level' => DamageLevel::class,
            'priority' => ReturnPriority::class,
            'recommended_action' => RecommendedAction::class,
            'requires_human_review' => 'boolean',
            'triaged_at' => 'datetime',
        ];
    }

    /**
     * Get the product being returned.
     *
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
