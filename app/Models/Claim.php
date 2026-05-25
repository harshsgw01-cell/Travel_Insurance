<?php

namespace App\Models;

use App\Enums\ClaimStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
    use HasFactory, SoftDeletes, Auditable;

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
            'incident_date'   => 'date',
            'amount_claimed'  => 'decimal:2',
            'amount_approved' => 'decimal:2',
            'status'          => ClaimStatus::class,
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

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
