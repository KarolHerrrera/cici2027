<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Página de inicio del CICI 2027.
     *
     * POR AHORA los datos están en arreglos para poder ver el diseño.
     * Más adelante cada arreglo se reemplaza por una consulta Eloquent,
     * por ejemplo: 'speakers' => Speaker::orderBy('name')->get()
     * y el contenido pasa a administrarse desde el panel.
     */
    public function index(): View
    {
        return view('home', [
            'evento' => [
                'nombre'    => 'IV Congreso Internacional de Ciencia e Ingeniería',
                'fechas'    => '14, 15 y 16 de abril de 2027',
                'modalidad' => 'Presencial y virtual',
                'inicio'    => '2027-04-14T00:00:00-05:00', // para la cuenta regresiva
                'lema'      => 'Un espacio para conectar conocimiento, investigación, innovación y territorio.',
            ],

            'pilares' => [
                ['titulo' => 'Conocimiento', 'lema' => 'Ideas que conectan'],
                ['titulo' => 'Innovación',   'lema' => 'Soluciones para el futuro'],
                ['titulo' => 'Territorio',   'lema' => 'Conexiones que trascienden'],
            ],

            'fechas' => [
                ['titulo' => 'Inicio de recepción de resúmenes', 'fecha' => '1 de octubre de 2026',   'destacada' => false],
                ['titulo' => 'Cierre de recepción de resúmenes', 'fecha' => '26 de febrero de 2027',  'destacada' => false],
                ['titulo' => 'Publicación de resultados',        'fecha' => '12 de marzo de 2027',    'destacada' => false],
                ['titulo' => 'Realización del congreso',         'fecha' => '14 al 16 de abril de 2027', 'destacada' => true],
            ],

            'ejes' => [
                ['titulo' => 'Diseño, Gestión, Innovación y Optimización de Procesos',                        'lineas' => 3, 'transversal' => false],
                ['titulo' => 'Diseño y construcción de obras de infraestructura para el desarrollo urbano y rural', 'lineas' => 7, 'transversal' => false],
                ['titulo' => 'Ingeniería, Informática y Computación',                                         'lineas' => 7, 'transversal' => false],
                ['titulo' => 'Ambiente y Desarrollo Sostenible',                                              'lineas' => 4, 'transversal' => false],
                ['titulo' => 'Desarrollo Mecatrónico',                                                        'lineas' => 5, 'transversal' => false],
                ['titulo' => 'Inteligencia Artificial Aplicada a la Educación',                               'lineas' => 4, 'transversal' => true],
            ],

            'ponentes' => [
                ['nombre' => 'Dr. Jesús Daniel Villalba Morales',  'area' => 'Ingeniería Civil',         'institucion' => 'Universidad Javeriana',                       'ciudad' => 'Bogotá',         'pais' => 'Colombia'],
                ['nombre' => 'Ing. César Augusto Patiño Suárez',   'area' => 'Ingeniería de Procesos',   'institucion' => 'Ecopetrol',                                   'ciudad' => 'Bogotá',         'pais' => 'Colombia'],
                ['nombre' => 'Dr. Luis Pérez Domínguez',           'area' => 'Ingeniería Mecatrónica',   'institucion' => 'Universidad Autónoma de Ciudad Juárez',       'ciudad' => 'Ciudad Juárez',  'pais' => 'México'],
                ['nombre' => 'Dr. Samir Fernando Castilla Acevedo','area' => 'Ingeniería Ambiental',     'institucion' => 'Universidad de Kansas',                       'ciudad' => 'Kansas City',    'pais' => 'Estados Unidos'],
                ['nombre' => 'Dr. José Antonio Quiña Mera',        'area' => 'Ingeniería de Sistemas',   'institucion' => 'Universidad Técnica del Norte',               'ciudad' => 'Ibarra',         'pais' => 'Ecuador'],
                ['nombre' => 'Dra. Cathy Pamela Guevara Vega',     'area' => 'Inteligencia Artificial',  'institucion' => 'Universidad Técnica del Norte',               'ciudad' => 'Ibarra',         'pais' => 'Ecuador'],
            ],

            'jornadas' => [
                ['dia' => 'Miércoles', 'numero' => '14', 'mes' => 'abril'],
                ['dia' => 'Jueves',    'numero' => '15', 'mes' => 'abril'],
                ['dia' => 'Viernes',   'numero' => '16', 'mes' => 'abril'],
            ],

            'tarifas' => [
                ['nombre' => 'Estudiantes y profesionales', 'detalle' => 'Asistente',          'valor' => 50000,  'destacada' => false],
                ['nombre' => 'Ponente nacional',            'detalle' => 'Modalidad póster',   'valor' => 250000, 'destacada' => false],
                ['nombre' => 'Ponente nacional',            'detalle' => 'Modalidad oral',     'valor' => 350000, 'destacada' => true],
                ['nombre' => 'Ponente internacional',       'detalle' => 'Oral o póster',      'valor' => 500000, 'destacada' => false],
            ],

            // url = null  →  se muestra "Próximamente".
            // TODO: reemplazar '#' por el enlace real cuando el Comité lo entregue.
            'documentos' => [
                ['titulo' => 'Convocatoria / Términos de referencia', 'tipo' => 'Convocatoria', 'url' => null],
                ['titulo' => 'Formato de resumen',                    'tipo' => 'Formato',      'url' => '#'],
                ['titulo' => 'Plantilla de póster',                   'tipo' => 'Plantilla',    'url' => null],
                ['titulo' => 'Material de difusión (banners)',        'tipo' => 'Difusión',     'url' => '#'],
            ],

            'enlaces' => [
                // TODO: pegar aquí el enlace real del formulario de Google Forms.
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
}
