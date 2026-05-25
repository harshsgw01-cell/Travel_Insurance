<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_number',
        'customer_id',
        'plan_id',
        'agent_id',
        'policy_type',
        'start_date',
        'end_date',
        'destination_country',
        'trip_type',
        'premium_amount',
        'tax_amount',
        'total_amount',
        'status',
        'payment_status',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'issued_at' => 'datetime',
            'premium_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(PolicyMember::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
}
