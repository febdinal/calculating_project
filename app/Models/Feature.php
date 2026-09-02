<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'slug', 'description', 'icon', 'is_infrastructure', 'sort_order', 'status'])]
class Feature extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_infrastructure' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(FeaturePrice::class);
    }

    public function defaultPrice(): ?FeaturePrice
    {
        return $this->prices()->where('is_default', true)->where('status', 'active')->first();
    }

    public function packageFeatures(): HasMany
    {
        return $this->hasMany(PackageFeature::class);
    }

    /**
     * Features that this feature depends on (requires).
     */
    public function dependencies(): HasMany
    {
        return $this->hasMany(FeatureDependency::class, 'feature_id');
    }

    /**
     * Features that require this feature.
     */
    public function dependents(): HasMany
    {
        return $this->hasMany(FeatureDependency::class, 'required_feature_id');
    }

    public function requiredFeatures(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_dependencies',
            'feature_id',
            'required_feature_id'
        );
    }
}
