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
     *
     * Fechas, ejes y ponentes ya se leen de la base de datos (Eloquent).
     * El resto sigue en arreglos y se irá migrando de la misma forma.
     */
    public function index(): View
    {
        Carbon::setLocale('es');

        $fechas = ImportantDate::orderBy('date')->get()->map(fn ($d) => [
            'titulo'    => $d->title,
            'fecha'     => $this->fechaTexto($d),
            'destacada' => $d->is_featured,
        ])->all();

        $ejes = ThematicAxis::withCount('topics')->orderBy('sort_order')->get()->map(fn ($e) => [
            'titulo'      => $e->name,
            'lineas'      => $e->topics_count,
            'transversal' => $e->is_transversal,
        ])->all();

        $ponentes = Speaker::orderBy('sort_order')->get()->map(fn ($p) => [
            'nombre'      => $p->name,
            'area'        => $p->specialty,
            'institucion' => $p->institution,
            'ciudad'      => $p->city,
            'pais'        => $p->country,
        ])->all();

        return view('home', [
            'evento' => [
                'nombre'    => 'IV Congreso Internacional de Ciencia e Ingeniería',
                'fechas'    => '14, 15 y 16 de abril de 2027',
                'modalidad' => 'Presencial y virtual',
                'inicio'    => '2027-04-14T00:00:00-05:00',
                'lema'      => 'Un espacio para conectar conocimiento, investigación, innovación y territorio.',
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
                ['titulo' => 'Convocatoria / Términos de referencia', 'tipo' => 'Convocatoria', 'url' => null],
                ['titulo' => 'Formato de resumen',                    'tipo' => 'Formato',      'url' => '#'],
                ['titulo' => 'Plantilla de póster',                   'tipo' => 'Plantilla',    'url' => null],
                ['titulo' => 'Material de difusión (banners)',        'tipo' => 'Difusión',     'url' => '#'],
            ],

            'enlaces' => [
                'resumenes'     => '#',
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
            return $d->date->format('j') . ' al ' . $d->end_date->translatedFormat('j \d\e F \d\e Y');
        }

        return $d->date->translatedFormat('j \d\e F') . ' al ' . $d->end_date->translatedFormat('j \d\e F \d\e Y');
    }
}