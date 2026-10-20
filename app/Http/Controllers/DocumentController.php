<?php

namespace App\Http\Controllers;

use App\Models\ImportantDate;
use App\Models\ThematicAxis;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function show(string $slug): View
    {
        $axes = ThematicAxis::with('topics')->orderBy('sort_order')->get();
        $dates = ImportantDate::orderBy('date')->get();

        $documents = [
            'convocatoria' => [
                'title'    => 'Convocatoria y Términos de Referencia · CICI 2027',
                'type'     => 'Convocatoria Oficial',
                'subtitle' => 'IV Congreso Internacional de Ciencia e Ingeniería · Facultad de Ingeniería, Universidad Mariana',
                'slug'     => 'convocatoria',
            ],
            'formato-resumen' => [
                'title'    => 'Formato Oficial de Resumen · CICI 2027',
                'type'     => 'Formato de Postulación',
                'subtitle' => 'Estructura y guía para la presentación de resúmenes de ponencia oral o póster',
                'slug'     => 'formato-resumen',
            ],
            'plantilla-poster' => [
                'title'    => 'Plantilla y Guía Oficial de Póster Científico · CICI 2027',
                'type'     => 'Plantilla para Trabajos Aprobados en Modalidad Póster',
                'subtitle' => 'Especificaciones técnicas, dimensiones (90 × 120 cm) y estructura visual para expositores en modalidad póster',
                'slug'     => 'plantilla-poster',
            ],
            'plantilla-oral' => [
                'title'    => 'Guía y Plantilla de Ponencia Oral · CICI 2027',
                'type'     => 'Plantilla para Trabajos Aprobados en Modalidad Ponencia Oral',
                'subtitle' => 'Lineamientos de presentación (15 min exposición + 5 min preguntas) y estructura de diapositivas',
                'slug'     => 'plantilla-oral',
            ],
            'material-difusion' => [
                'title'    => 'Material e Identidad Visual de Difusión · CICI 2027',
                'type'     => 'Material de Difusión',
                'subtitle' => 'Piezas gráficas, logos y recursos institucionales del IV Congreso Internacional de Ciencia e Ingeniería',
                'slug'     => 'material-difusion',
            ],
        ];

        abort_unless(isset($documents[$slug]), 404);

        return view('documents.show', [
            'doc'   => $documents[$slug],
            'axes'  => $axes,
            'dates' => $dates,
        ]);
    }
}
