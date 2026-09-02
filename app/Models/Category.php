<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'icon', 'color', 'sort_order', 'status'])]
class Category extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    public function mainFeatures(): HasMany
    {
        return $this->hasMany(Feature::class)->whereNull('parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
