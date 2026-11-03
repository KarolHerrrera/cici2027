<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'committee_type',
        'institution',
        'program_or_unit',
        'email',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function committeeTypeLabel(): string
    {
        return match ($this->committee_type) {
            'directivo'   => 'Comité Directivo e Institucional',
            'cientifico'  => 'Comité Científico y Arbitraje',
            'organizador' => 'Comité Organizador Facultad de Ingeniería',
            'logistico'   => 'Comité de Comunicaciones y Logística',
            default       => ucfirst($this->committee_type),
        };
    }
}
