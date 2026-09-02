<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'parent_id', 'name', 'slug', 'description', 'icon', 'price', 'sort_order', 'status'])]
class Feature extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'parent_id');
    }

    public function subFeatures(): HasMany
    {
        return $this->hasMany(Feature::class, 'parent_id')->orderBy('sort_order');
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_features')
            ->withTimestamps();
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSub($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isMain(): bool
    {
        return is_null($this->parent_id);
    }

    public function isSub(): bool
    {
        return ! is_null($this->parent_id);
    }

    /**
     * Hitung total harga fitur dari sub-fiturnya jika ada.
     */
    public function getCalculatedPriceAttribute(): float
    {
        if ($this->isMain()) {
            if ($this->relationLoaded('subFeatures') && $this->subFeatures->isNotEmpty()) {
                return (float) $this->subFeatures->sum('price');
            }

            if ($this->subFeatures()->exists()) {
                return (float) $this->subFeatures()->sum('price');
            }
        }

        return (float) ($this->attributes['price'] ?? 0);
    }

    /**
     * Sinkronisasi nilai kolom price fitur utama dari total harga sub-fitur.
     */
    public function syncPriceFromSubFeatures(): void
    {
        if ($this->isMain() && $this->subFeatures()->exists()) {
            $this->update(['price' => (float) $this->subFeatures()->sum('price')]);
        }
    }
}
