<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThematicAxis extends Model
{
    protected $fillable = ['name', 'description', 'is_transversal', 'sort_order'];

    protected $casts = ['is_transversal' => 'boolean'];

    public function topics(): HasMany
    {
        return $this->hasMany(AxisTopic::class)->orderBy('sort_order');
    }

    public function speakers(): HasMany
    {
        return $this->hasMany(Speaker::class);
    }
}