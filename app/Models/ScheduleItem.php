<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleItem extends Model
{
    protected $fillable = [
        'day_label',
        'event_date',
        'start_time',
        'end_time',
        'title',
        'description',
        'speaker_name',
        'location',
        'activity_type',
        'thematic_axis_id',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date'   => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function axis(): BelongsTo
    {
        return $this->belongsTo(ThematicAxis::class, 'thematic_axis_id');
    }

    public function activityTypeLabel(): string
    {
        return match ($this->activity_type) {
            'conferencia' => 'Conferencia Magistral',
            'ponencia'    => 'Sesión de Ponencias Orales',
            'poster'      => 'Exhibición de Pósteres',
            'panel'       => 'Foro / Panel Académico',
            'apertura'    => 'Acto Protocolario / Registro',
            'cultural'    => 'Actividad Institucional / Clausura',
            default       => ucfirst($this->activity_type),
        };
    }
}
