<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'claim_number',
        'traveler_id',
        'claim_type',
        'incident_date',
        'amount_claimed',
        'amount_approved',
        'status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'amount_claimed' => 'decimal:2',
            'amount_approved' => 'decimal:2',
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
