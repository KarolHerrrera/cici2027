<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'category',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'resumenes'     => 'Envío y Evaluación de Resúmenes',
            'inscripciones' => 'Inscripción y Pagos',
            'modalidades'   => 'Modalidades (Oral y Póster)',
            'certificados'  => 'Certificados y Memorias',
            default         => 'Información General',
        };
    }
}
