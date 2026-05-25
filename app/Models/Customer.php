<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_code',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'dob',
        'gender',
        'passport_no',
        'nationality',
        'address',
        'kyc_status',
        'status',
    ];

    protected function casts(): array
    {
        return ['dob' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function travelers(): HasMany
    {
        return $this->hasMany(Traveler::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }
}
