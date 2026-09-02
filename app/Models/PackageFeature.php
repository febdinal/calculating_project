<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['package_id', 'feature_id', 'status', 'notes'])]
class PackageFeature extends Model
{
    use HasFactory;

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    public function isIncluded(): bool
    {
        return $this->status === 'included';
    }

    public function isOptional(): bool
    {
        return $this->status === 'optional';
    }

    public function isNotAvailable(): bool
    {
        return $this->status === 'not_available';
    }
}
