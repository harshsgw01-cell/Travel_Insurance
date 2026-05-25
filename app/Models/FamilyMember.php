<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'relationship',
        'first_name',
        'last_name',
        'dob',
        'passport_no',
        'gender',
        'dependent',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'dependent' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
