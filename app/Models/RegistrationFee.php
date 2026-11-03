<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationFee extends Model
{
    protected $fillable = [
        'profile',
        'description',
        'price_formatted',
        'price_amount',
        'currency',
        'includes',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'price_amount' => 'integer',
        ];
    }
}
