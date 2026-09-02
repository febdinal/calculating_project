<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * cost_price is hidden from all serialization and is admin-only.
 * It is never included in API responses or frontend data.
 */
#[Fillable(['feature_id', 'complexity', 'price_type', 'cost_price', 'selling_price', 'price_min', 'price_max', 'is_default', 'status'])]
#[Hidden(['cost_price'])]
class FeaturePrice extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'price_min' => 'decimal:2',
            'price_max' => 'decimal:2',
            'is_default' => 'boolean',
        ];
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * Calculate profit margin (admin-only).
     * Returns null if cost or selling price is not set.
     */
    public function calculateProfit(): ?float
    {
        if ($this->cost_price === null || $this->selling_price === null) {
            return null;
        }

        return (float) $this->selling_price - (float) $this->cost_price;
    }

    /**
     * Calculate profit margin percentage (admin-only).
     * Returns null if selling price is zero or not set.
     */
    public function calculateMarginPercentage(): ?float
    {
        $profit = $this->calculateProfit();
        if ($profit === null || (float) $this->selling_price === 0.0) {
            return null;
        }

        return round(($profit / (float) $this->selling_price) * 100, 2);
    }

    public function getFormattedPriceAttribute(): string
    {
        return match ($this->price_type) {
            'range' => 'Rp '.number_format((float) $this->price_min, 0, ',', '.').' – Rp '.number_format((float) $this->price_max, 0, ',', '.'),
            'custom' => 'Hubungi Kami',
            default => $this->selling_price ? 'Rp '.number_format((float) $this->selling_price, 0, ',', '.') : 'Belum ditentukan',
        };
    }
}
