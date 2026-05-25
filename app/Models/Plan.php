<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'policy_type',
        'base_premium',
        'coverage_amount',
        'min_age',
        'max_age',
        'max_family_members',
        'covered_countries',
        'benefits',
        'add_ons',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_premium' => 'decimal:2',
            'coverage_amount' => 'decimal:2',
            'covered_countries' => 'array',
            'benefits' => 'array',
            'add_ons' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }
}
