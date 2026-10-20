<?php

namespace Database\Seeders;

use App\Models\AxisTopic;
use App\Models\ThematicAxis;
use Illuminate\Database\Seeder;

class ThematicAxisSeeder extends Seeder
{
    public function run(): void
    {
        $ejes = [
            [
                'name'        => 'Diseño, Gestión, Innovación y Optimización de Procesos',
                'transversal' => false,
                'topics'      => [
                    'Ingeniería de bioprocesos y agroindustria',
                    'Optimización energética y procesos químicos',
                    'Gestión de calidad, logística y cadena de suministro',
                ],
            ],
            [
                'name'        => 'Diseño y construcción de obras de infraestructura para el desarrollo urbano y rural',
                'transversal' => false,
                'topics'      => [
                    'Estructuras, sismorresistencia y materiales avanzados',
                    'Geotecnia y estabilidad de taludes andinos',
                    'Infraestructura vial, transporte y movilidad sostenible',
                    'Recursos hídricos, hidráulica y saneamiento básico',
                    'Construcción sostenible y economía circular en obra civil',
                    'Geomática, fotogrametría y modelado BIM',
                    'Gestión del riesgo de desastres e infraestructura resiliente',
                ],
            ],
            [
                'name'        => 'Ingeniería, Informática y Computación',
                'transversal' => false,
                'topics'      => [
                    'Ingeniería de software y arquitecturas en la nube',
                    'Ciencia de datos, analítica y aprendizaje automático',
                    'Ciberseguridad, redes e infraestructura crítica',
                    'Internet de las Cosas (IoT) y ciudades inteligentes',
                    'Sistemas de información geográfica y transformación digital',
                    'Interacción humano-computador y accesibilidad web',
                    'Computación de alto rendimiento y simulación',
                ],
            ],
            [
                'name'        => 'Ambiente y Desarrollo Sostenible',
                'transversal' => false,
                'topics'      => [
                    'Tratamiento de aguas, aire y biorremediación de suelos',
                    'Gestión integral de residuos sólidos y economía circular',
                    'Cambio climático, biodiversidad y servicios ecosistémicos',
                    'Energías renovables y transición energética regional',
                ],
            ],
            [
                'name'        => 'Desarrollo Mecatrónico',
                'transversal' => false,
                'topics'      => [
                    'Robótica móvil, industrial y de servicio',
                    'Automatización industrial e Industria 4.0',
                    'Sistemas embebidos, instrumentación y control automático',
                    'Diseño mecánico, manufactura aditiva y mecatrónica agrícola',
                    'Bioingeniería y dispositivos de rehabilitación',
                ],
            ],
            [
                'name'        => 'Inteligencia Artificial Aplicada a la Educación',
                'transversal' => true,
                'topics'      => [
                    'Sistemas de tutoría inteligente y aprendizaje adaptativo',
                    'IA generativa en la enseñanza de las ciencias y la ingeniería',
                    'Analítica del aprendizaje (Learning Analytics) y evaluación',
                    'Ética, gobernanza e inclusión digital con IA en el aula',
                ],
            ],
        ];

        foreach ($ejes as $i => $dato) {
            $eje = ThematicAxis::updateOrCreate(
                ['name' => $dato['name']],
                ['is_transversal' => $dato['transversal'], 'sort_order' => $i + 1]
            );

            $eje->topics()->delete();

            foreach ($dato['topics'] as $idx => $topicName) {
                AxisTopic::create([
                    'thematic_axis_id' => $eje->id,
                    'name'             => $topicName,
                    'sort_order'       => $idx + 1,
                ]);
            }
        }
    }
}
