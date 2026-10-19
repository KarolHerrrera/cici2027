<?php

namespace Database\Seeders;

use App\Models\ImportantDate;
use Illuminate\Database\Seeder;

class ImportantDateSeeder extends Seeder
{
    public function run(): void
    {
        $fechas = [
            ['title' => 'Inicio de recepción de resúmenes', 'date' => '2026-10-01', 'end_date' => null,         'is_featured' => false],
            ['title' => 'Cierre de recepción de resúmenes', 'date' => '2027-02-26', 'end_date' => null,         'is_featured' => false],
            ['title' => 'Publicación de resultados',        'date' => '2027-03-12', 'end_date' => null,         'is_featured' => false],
            ['title' => 'Realización del congreso',         'date' => '2027-04-14', 'end_date' => '2027-04-16', 'is_featured' => true],
        ];

        foreach ($fechas as $dato) {
            ImportantDate::updateOrCreate(['title' => $dato['title']], $dato);
        }
    }
}