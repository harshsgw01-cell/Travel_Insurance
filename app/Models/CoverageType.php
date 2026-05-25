<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverageType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'default_limit', 'is_add_on', 'is_active'];

    protected function casts(): array
    {
        return [
            'default_limit' => 'decimal:2',
            'is_add_on' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
