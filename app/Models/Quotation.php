<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_id', 'quotation_number', 'issued_at', 'valid_until', 'status', 'terms_conditions', 'notes', 'admin_notes', 'pdf_path'])]
class Quotation extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'valid_until' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        return $this->valid_until !== null && $this->valid_until->isPast();
    }

    /**
     * Generate a unique quotation number.
     */
    public static function generateQuotationNumber(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $lastQuotation = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderByDesc('id')
            ->first();

        $sequence = $lastQuotation ? (int) substr($lastQuotation->quotation_number, -4) + 1 : 1;

        return sprintf('QUO-%s%s-%04d', $year, $month, $sequence);
    }
}
