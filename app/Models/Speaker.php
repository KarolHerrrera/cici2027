<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Speaker extends Model
{
    protected $fillable = [
        'thematic_axis_id', 'name', 'photo', 'job_title', 'specialty',
        'institution', 'city', 'country', 'bio', 'talk_title', 'sort_order',
    ];

    public function axis(): BelongsTo
    {
        return $this->belongsTo(ThematicAxis::class, 'thematic_axis_id');
    }
}