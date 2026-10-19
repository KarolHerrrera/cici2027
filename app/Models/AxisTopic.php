<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AxisTopic extends Model
{
    protected $fillable = ['thematic_axis_id', 'name', 'sort_order'];

    public function axis(): BelongsTo
    {
        return $this->belongsTo(ThematicAxis::class, 'thematic_axis_id');
    }
}