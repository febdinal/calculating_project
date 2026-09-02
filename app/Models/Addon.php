<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * cost_price is admin-only and hidden from all serialization.
 */
#[Fillable(['name', 'slug', 'description', 'icon', 'category', 'price_type', 'cost_price', 'selling_price', 'price_min', 'price_max', 'sort_order', 'status'])]
#[Hidden(['cost_price'])]
class Addon extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'price_min' => 'decimal:2',
            'price_max' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Calculate profit (admin-only).
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
