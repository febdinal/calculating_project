<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'price', 'period', 'status', 'sort_order'])]
class Package extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'package_features')
            ->withTimestamps();
    }

    public function packageFeatures(): HasMany
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Hitung total harga paket dari seluruh fitur bawaannya.
     */
    public function getCalculatedPriceAttribute(): float
    {
        if ($this->slug === 'custom') {
            return 0.0;
        }

        $features = $this->relationLoaded('features')
            ? $this->features
            : $this->features()->with('subFeatures')->get();

        return (float) $features->sum(function ($feature) {
            if ($feature->relationLoaded('subFeatures') && $feature->subFeatures->isNotEmpty()) {
                return (float) $feature->subFeatures->sum('price');
            }

            if ($feature->subFeatures()->exists()) {
                return (float) $feature->subFeatures()->sum('price');
            }

            return (float) ($feature->price ?? 0);
        });
    }

    /**
     * Sinkronisasi nilai kolom price paket dari total harga fitur bawaan.
     */
    public function syncPriceFromFeatures(): void
    {
        $this->update(['price' => $this->calculated_price]);
    }
}
