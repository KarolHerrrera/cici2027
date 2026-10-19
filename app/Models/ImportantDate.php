<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantDate extends Model
{
    protected $fillable = ['title', 'date', 'end_date', 'is_featured'];

    protected $casts = [
        'date'        => 'date',
        'end_date'    => 'date',
        'is_featured' => 'boolean',
    ];
}