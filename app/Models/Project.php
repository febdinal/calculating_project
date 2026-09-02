<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * total_cost_price and total_profit are admin-only fields.
 * total_selling_price is visible to authenticated users for their own projects.
 */
#[Fillable(['user_id', 'package_id', 'name', 'customer_name', 'customer_email', 'customer_phone', 'customer_company', 'notes', 'status', 'total_selling_price', 'total_cost_price', 'total_profit', 'package_price_snapshot'])]
#[Hidden(['total_cost_price', 'total_profit'])]
class Project extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'total_selling_price' => 'decimal:2',
            'total_cost_price' => 'decimal:2',
            'total_profit' => 'decimal:2',
            'package_price_snapshot' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function projectFeatures(): HasMany
    {
        return $this->hasMany(ProjectFeature::class);
    }

    public function projectAddons(): HasMany
    {
        return $this->hasMany(ProjectAddon::class);
    }

    public function quotation(): HasOne
    {
        return $this->hasOne(Quotation::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function calculateMarginPercentage(): ?float
    {
        if ((float) $this->total_selling_price === 0.0) {
            return null;
        }

        return round(((float) $this->total_profit / (float) $this->total_selling_price) * 100, 2);
    }
}
