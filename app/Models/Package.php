<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'price', 'billing_period', 'target_user', 'price_type', 'is_featured', 'sort_order', 'status'])]
class Package extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'package_features')
            ->withPivot('status', 'notes')
            ->withTimestamps();
    }

    public function packageFeatures(): HasMany
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function includedFeatures(): BelongsToMany
    {
        return $this->features()->wherePivot('status', 'included');
    }

    public function optionalFeatures(): BelongsToMany
    {
        return $this->features()->wherePivot('status', 'optional');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function isCustomPriced(): bool
    {
        return $this->price_type === 'custom';
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->isCustomPriced()) {
            return 'Custom';
        }

        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }
}
