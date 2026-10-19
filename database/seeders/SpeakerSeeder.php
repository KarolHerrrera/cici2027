<?php

namespace Database\Seeders;

use App\Models\Speaker;
use Illuminate\Database\Seeder;

class SpeakerSeeder extends Seeder
{
    public function run(): void
    {
        $ponentes = [
            ['name' => 'Dr. Jesús Daniel Villalba Morales',   'specialty' => 'Ingeniería Civil',        'institution' => 'Universidad Javeriana',                 'city' => 'Bogotá',        'country' => 'Colombia'],
            ['name' => 'Ing. César Augusto Patiño Suárez',    'specialty' => 'Ingeniería de Procesos',  'institution' => 'Ecopetrol',                             'city' => 'Bogotá',        'country' => 'Colombia'],
            ['name' => 'Dr. Luis Pérez Domínguez',            'specialty' => 'Ingeniería Mecatrónica',  'institution' => 'Universidad Autónoma de Ciudad Juárez', 'city' => 'Ciudad Juárez', 'country' => 'México'],
            ['name' => 'Dr. Samir Fernando Castilla Acevedo', 'specialty' => 'Ingeniería Ambiental',    'institution' => 'Universidad de Kansas',                 'city' => 'Kansas City',   'country' => 'Estados Unidos'],
            ['name' => 'Dr. José Antonio Quiña Mera',         'specialty' => 'Ingeniería de Sistemas',  'institution' => 'Universidad Técnica del Norte',         'city' => 'Ibarra',        'country' => 'Ecuador'],
            ['name' => 'Dra. Cathy Pamela Guevara Vega',      'specialty' => 'Inteligencia Artificial', 'institution' => 'Universidad Técnica del Norte',         'city' => 'Ibarra',        'country' => 'Ecuador'],
        ];

        foreach ($ponentes as $i => $dato) {
            Speaker::updateOrCreate(
                ['name' => $dato['name']],
                $dato + ['sort_order' => $i + 1]
            );
        }
    }
}