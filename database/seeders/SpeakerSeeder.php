<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\ThematicAxis;
use Illuminate\Database\Seeder;

/**
 * Ponentes invitados. Datos actualizados del sitio oficial https://cici.umariana.edu.co/
 */
class SpeakerSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar ponente anterior si existía de versiones previas
        Speaker::where('name', 'Dr. Jesús Daniel Villalba Morales')->delete();

        $ponentes = [
            [
                'axis'        => 6,
                'name'        => 'Dra. Cathy Pamela Guevara Vega',
                'photo'       => 'speakers/cathy-pamela-guevara-vega.png',
                'specialty'   => 'Inteligencia Artificial Aplicada e Innovación Digital',
                'institution' => 'Universidad Técnica del Norte - Ibarra',
                'city'        => 'Ibarra',
                'country'     => 'Ecuador',
                'bio'         => 'PhD y docente-investigadora de la Universidad Técnica del Norte, con trayectoria en ingeniería de software, educación en informática e investigación.',
                'talk_title'  => 'Conferencia magistral: Inteligencia Artificial Aplicada e Innovación Digital',
            ],
            [
                'axis'        => 1,
                'name'        => 'Ing. César Augusto Patiño Suárez',
                'photo'       => 'speakers/cesar-augusto-patino-suarez.png',
                'specialty'   => 'Diseño, Gestión, Innovación y Optimización de Procesos',
                'institution' => 'Ecopetrol',
                'city'        => 'Bogotá',
                'country'     => 'Colombia',
                'bio'         => 'Ingeniero de Petróleos, MBA y profesional de Ecopetrol con trayectoria en recobro mejorado, recursos energéticos del subsuelo, sostenibilidad y transición energética.',
                'talk_title'  => 'Calor, un recurso del subsuelo y superficie, integrado en procesos a los nuevos energéticos.',
            ],
            [
                'axis'        => 2,
                'name'        => 'Dr. Mauricio Alvarado Bueno',
                'photo'       => 'speakers/mauricio-alvarado-bueno.jpg',
                'specialty'   => 'Diseño y construcción de obras de infraestructura para el desarrollo urbano y rural',
                'institution' => 'Universidad Santiago de Cali (USC)',
                'city'        => 'Cali',
                'country'     => 'Colombia',
                'bio'         => 'Ingeniero Civil. Máster en Ingeniería del Terreno y Sísmica. Doctorado en Ingeniería Geotécnica.',
                'talk_title'  => 'Conferencia magistral: Ingeniería Civil y Geotecnia',
            ],
            [
                'axis'        => 3,
                'name'        => 'Dr. José Antonio Quiña Mera',
                'photo'       => 'speakers/jose-antonio-quina-mera.png',
                'specialty'   => 'Ingeniería, Informática y Computación',
                'institution' => 'Universidad Técnica del Norte - Ibarra',
                'city'        => 'Ibarra',
                'country'     => 'Ecuador',
                'bio'         => 'PhD y docente vinculado a la Universidad Técnica del Norte, con trayectoria en ingeniería de software, investigación y dirección de trabajos de titulación. Autor y coautor de publicaciones sobre gestión de proyectos de software, APIs y calidad de software.',
                'talk_title'  => 'Conferencia magistral: Ingeniería de Sistemas y Software',
            ],
            [
                'axis'        => 4,
                'name'        => 'Dr. Samir Fernando Castilla Acevedo',
                'photo'       => 'speakers/samir-fernando-castilla-acevedo.png',
                'specialty'   => 'Ambiente y Desarrollo Sostenible',
                'institution' => 'Kansas State University',
                'city'        => 'Manhattan, Kansas',
                'country'     => 'Estados Unidos',
                'bio'         => 'PhD en Ingeniería Química por la University of Kansas (2026). Su experiencia investigativa incluye tratamiento de aguas residuales, catálisis, química verde y procesos de oxidación avanzada.',
                'talk_title'  => 'Conferencia magistral: Ambiente y Desarrollo Sostenible',
            ],
            [
                'axis'        => 4,
                'name'        => 'Dr. Marcial Reyes Cázarez',
                'photo'       => 'speakers/marcial-reyes-cazarez.png',
                'specialty'   => 'Ambiente y Desarrollo Sostenible',
                'institution' => 'Tecnológico Nacional de México · Instituto Tecnológico Superior de Pátzcuaro',
                'city'        => 'Morelia, Michoacán',
                'country'     => 'México',
                'bio'         => 'Doctor en Género y Derecho, Maestro en Ciencias y Tecnología de la Madera e Ingeniero Forestal. Autor de artículos científicos sobre materia forestal, derecho a la propiedad agraria, recursos hídricos, legislación ambiental, gestión de proyectos, remediación de suelos y alternativas tecnológicas ambientales. Miembro del Sistema Nacional de Investigadoras e Investigadores, docente de educación superior con más de una década de experiencia e investigador en temas hidroforestales, servicios ecosistémicos, disponibilidad y calidad del agua y cambio de uso del suelo.',
                'talk_title'  => 'Conferencia magistral: Gestión Hidroforestal y Sostenibilidad Ambiental',
            ],
            [
                'axis'        => 5,
                'name'        => 'Dr. Luis Pérez Domínguez',
                'photo'       => 'speakers/luis-perez-dominguez.png',
                'specialty'   => 'Desarrollo Mecatrónico',
                'institution' => 'Universidad Autónoma de Ciudad Juárez',
                'city'        => 'Ciudad Juárez',
                'country'     => 'México',
                'bio'         => 'Doctor en Ciencias de la Ingeniería por la Universidad Autónoma de Ciudad Juárez. Profesor-investigador, con experiencia en toma de decisiones multicriterio, ciencia de datos, inteligencia artificial y aplicaciones en manufactura.',
                'talk_title'  => 'IA en el campo de la ingeniería, Diseño de partes mecatrónicas asistido por IA',
            ],
        ];

        foreach ($ponentes as $i => $dato) {
            $axis = ThematicAxis::where('sort_order', $dato['axis'])->first();
            unset($dato['axis']);

            Speaker::updateOrCreate(
                ['name' => $dato['name']],
                $dato + ['thematic_axis_id' => $axis?->id, 'sort_order' => $i + 1]
            );
        }
    }
}
