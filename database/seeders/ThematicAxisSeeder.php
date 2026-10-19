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
            ['name' => 'Diseño, Gestión, Innovación y Optimización de Procesos', 'lineas' => 3, 'transversal' => false],
            ['name' => 'Diseño y construcción de obras de infraestructura para el desarrollo urbano y rural', 'lineas' => 7, 'transversal' => false],
            ['name' => 'Ingeniería, Informática y Computación', 'lineas' => 7, 'transversal' => false],
            ['name' => 'Ambiente y Desarrollo Sostenible', 'lineas' => 4, 'transversal' => false],
            ['name' => 'Desarrollo Mecatrónico', 'lineas' => 5, 'transversal' => false],
            ['name' => 'Inteligencia Artificial Aplicada a la Educación', 'lineas' => 4, 'transversal' => true],
        ];

        foreach ($ejes as $i => $dato) {
            $eje = ThematicAxis::updateOrCreate(
                ['name' => $dato['name']],
                ['is_transversal' => $dato['transversal'], 'sort_order' => $i + 1]
            );

            // Líneas de ejemplo: el administrador las renombrará desde el panel.
            for ($n = 1; $n <= $dato['lineas']; $n++) {
                AxisTopic::updateOrCreate(
                    ['thematic_axis_id' => $eje->id, 'name' => "Línea temática $n"],
                    ['sort_order' => $n]
                );
            }
        }
    }
}