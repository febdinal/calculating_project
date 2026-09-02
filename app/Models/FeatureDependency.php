<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['feature_id', 'required_feature_id'])]
class FeatureDependency extends Model
{
    use HasFactory;

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function requiredFeature(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'required_feature_id');
    }
}
