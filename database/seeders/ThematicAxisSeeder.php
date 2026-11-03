<?php

namespace Database\Seeders;

use App\Models\ThematicAxis;
use Illuminate\Database\Seeder;

/**
 * Ejes y líneas temáticas. Datos tomados del sitio oficial https://cici.umariana.edu.co/
 * Los ejes se identifican por su posición (sort_order) para poder renombrarlos sin duplicarlos.
 */
class ThematicAxisSeeder extends Seeder
{
    public function run(): void
    {
        $ejes = [
            [
                'name'        => 'Diseño, Gestión, Innovación y Optimización de Procesos',
                'transversal' => false,
                'topics'      => [
                    'Diseño, Gestión y Optimización de Procesos',
                    'Bioprocesos y Biorrefinerías',
                    'Desarrollo Sostenible en Procesos',
                ],
            ],
            [
                'name'        => 'Diseño y construcción de obras de infraestructura para el desarrollo urbano y rural',
                'transversal' => false,
                'topics'      => [
                    'Estructuras',
                    'Geotecnia',
                    'Agua y Medio Ambiente',
                    'Transporte y Vías',
                    'Métodos Numéricos en las Ciencias Básicas y la Ingeniería Civil',
                    'Administración y Construcción',
                    'Educación y Proyección Social en Ingeniería',
                ],
            ],
            [
                'name'        => 'Ingeniería, Informática y Computación',
                'transversal' => false,
                'topics'      => [
                    'Ingeniería de Software',
                    'Ciencia de Datos',
                    'Inteligencia de Negocios, Data Mining y Big Data',
                    'Inteligencia Artificial, Machine Learning y Blockchain',
                    'Realidad Aumentada, Virtual y Mixta',
                    'Educación en Ingeniería de Sistemas',
                    'Informática y Computación',
                ],
            ],
            [
                'name'        => 'Ambiente y Desarrollo Sostenible',
                'transversal' => false,
                'topics'      => [
                    'Energías Alternativas',
                    'Gestión Ambiental',
                    'Diagnóstico Ambiental',
                    'Control de la Contaminación',
                ],
            ],
            [
                'name'        => 'Desarrollo Mecatrónico',
                'transversal' => false,
                'topics'      => [
                    'Bioingeniería',
                    'Automatización y Control',
                    'Electrónica',
                    'Diseño Mecánico y Prototipado',
                    'Procesos Productivos',
                ],
            ],
            [
                'name'        => 'Inteligencia Artificial Aplicada e Innovación Digital',
                'transversal' => true,
                'topics'      => [
                    'Inteligencia Artificial en Educación y Aprendizaje',
                    'Inteligencia Artificial Generativa y Herramientas Emergentes',
                    'Inteligencia Artificial Aplicada a la Ciencia y la Investigación',
                    'Inteligencia Artificial en Ingeniería y Procesos Productivos',
                    'Inteligencia Artificial para la Salud y el Bienestar',
                    'Inteligencia Artificial para la Sostenibilidad y el Medio Ambiente',
                    'Transformación Digital, Emprendimiento y Productividad',
                    'Ética, Gobernanza y Uso Responsable de la Inteligencia Artificial',
                ],
            ],
        ];

        foreach ($ejes as $i => $dato) {
            $eje = ThematicAxis::updateOrCreate(
                ['sort_order' => $i + 1],
                ['name' => $dato['name'], 'is_transversal' => $dato['transversal']]
            );

            $eje->topics()->delete();

            foreach ($dato['topics'] as $idx => $topicName) {
                $eje->topics()->create(['name' => $topicName, 'sort_order' => $idx + 1]);
            }
        }
    }
}
