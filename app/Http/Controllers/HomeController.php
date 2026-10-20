<?php

namespace App\Http\Controllers;

use App\Models\ImportantDate;
use App\Models\Speaker;
use App\Models\ThematicAxis;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Página de inicio del CICI 2027.
     */
    public function index(): View
    {
        Carbon::setLocale('es');

        $fechas = ImportantDate::orderBy('date')->get()->map(fn ($d) => [
            'titulo'    => $d->title,
            'fecha'     => $this->fechaTexto($d),
            'destacada' => $d->is_featured,
        ])->all();

        $cierreResumenes = $fechas[1]['fecha'] ?? ($fechas[0]['fecha'] ?? '26 de febrero de 2027');

        $ejes = ThematicAxis::with('topics')->withCount('topics')->orderBy('sort_order')->get()->map(fn ($e) => [
            'titulo'      => $e->name,
            'lineas'      => $e->topics_count,
            'temas'       => $e->topics->pluck('name')->all(),
            'transversal' => $e->is_transversal,
        ])->all();

        $ponentes = Speaker::orderBy('sort_order')->get()->map(fn ($p) => [
            'nombre'      => $p->name,
            'foto'        => $p->photo,
            'cargo'       => $p->job_title,
            'area'        => $p->specialty,
            'institucion' => $p->institution,
            'ciudad'      => $p->city,
            'pais'        => $p->country,
            'charla'      => $p->talk_title,
        ])->all();

        return view('home', [
            'evento' => [
                'nombre'           => 'IV Congreso Internacional de Ciencia e Ingeniería',
                'fechas'           => '14, 15 y 16 de abril de 2027',
                'modalidad'        => 'Presencial y virtual',
                'inicio'           => '2027-04-14T00:00:00-05:00',
                'cierre_resumenes' => $cierreResumenes,
                'lema'             => 'Un espacio para conectar conocimiento, investigación, innovación y territorio.',
            ],

            'pilares' => [
                ['titulo' => 'Conocimiento', 'lema' => 'Ideas que conectan'],
                ['titulo' => 'Innovación',   'lema' => 'Soluciones para el futuro'],
                ['titulo' => 'Territorio',   'lema' => 'Conexiones que trascienden'],
            ],

            'fechas'   => $fechas,
            'ejes'     => $ejes,
            'ponentes' => $ponentes,

            'jornadas' => [
                ['dia' => 'Miércoles', 'numero' => '14', 'mes' => 'abril'],
                ['dia' => 'Jueves',    'numero' => '15', 'mes' => 'abril'],
                ['dia' => 'Viernes',   'numero' => '16', 'mes' => 'abril'],
            ],

            'tarifas' => [
                ['nombre' => 'Estudiantes y profesionales', 'detalle' => 'Asistente',        'valor' => 50000,  'destacada' => false],
                ['nombre' => 'Ponente nacional',            'detalle' => 'Modalidad póster', 'valor' => 250000, 'destacada' => false],
                ['nombre' => 'Ponente nacional',            'detalle' => 'Modalidad oral',   'valor' => 350000, 'destacada' => true],
                ['nombre' => 'Ponente internacional',       'detalle' => 'Oral o póster',    'valor' => 500000, 'destacada' => false],
            ],

            'documentos' => [
                [
                    'titulo' => 'Convocatoria / Términos de referencia',
                    'tipo'   => 'Convocatoria',
                    'url'    => route('documents.show', 'convocatoria'),
                ],
                [
                    'titulo' => 'Formato de resumen (Ponencia oral o Póster)',
                    'tipo'   => 'Formato',
                    'url'    => config('cici.abstract_template_url') ?: route('documents.show', 'formato-resumen'),
                ],
                [
                    'titulo' => 'Plantilla oficial de póster científico',
                    'tipo'   => 'Plantilla',
                    'url'    => config('cici.poster_template_url') ?: route('documents.show', 'plantilla-poster'),
                ],
                [
                    'titulo' => 'Guía y plantilla de ponencia oral',
                    'tipo'   => 'Plantilla',
                    'url'    => config('cici.oral_template_url') ?: route('documents.show', 'plantilla-oral'),
                ],
                [
                    'titulo' => 'Material de difusión (banners e identidad)',
                    'tipo'   => 'Difusión',
                    'url'    => route('documents.show', 'material-difusion'),
                ],
            ],

            'enlaces' => [
                'resumenes'     => config('cici.abstract_form_url') ?: route('submissions.public.create'),
                'inscripciones' => 'https://fenix.umariana.edu.co/sgacampus/services/inscripciones/home',
                'whatsapp'      => 'https://wa.me/573148521240',
            ],

            'contacto' => [
                'correo'   => 'congresoingenieria@umariana.edu.co',
                'telefono' => '314 852 1240',
                'web'      => 'cici.umariana.edu.co',
            ],
        ]);
    }

    /** Convierte una fecha (o rango) en texto: "14 al 16 de abril de 2027". */
    private function fechaTexto(ImportantDate $d): string
    {
        if (! $d->end_date) {
            return $d->date->translatedFormat('j \d\e F \d\e Y');
        }

        if ($d->date->format('Y-m') === $d->end_date->format('Y-m')) {
            return $d->date->format('j').' al '.$d->end_date->translatedFormat('j \d\e F \d\e Y');
        }

        return $d->date->translatedFormat('j \d\e F').' al '.$d->end_date->translatedFormat('j \d\e F \d\e Y');
    }
}
