<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Traveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'family_member_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'passport_no',
        'nationality',
        'visa_type',
        'emergency_contact',
    ];

    protected function casts(): array
    {
        return ['dob' => 'date'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }

    public function policyMembers(): HasMany
    {
        return $this->hasMany(PolicyMember::class);
    }
}
