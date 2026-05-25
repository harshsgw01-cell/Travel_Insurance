<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'traveler_id',
        'relationship',
        'coverage_amount',
        'premium',
    ];

    protected function casts(): array
    {
        return [
            'coverage_amount' => 'decimal:2',
            'premium' => 'decimal:2',
        ];
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }
}
