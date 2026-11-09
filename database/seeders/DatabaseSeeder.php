<?php

namespace Database\Seeders;

use App\Models\ThematicAxis;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear primero el usuario Administrador General para garantizar acceso inmediato en cualquier entorno
        User::updateOrCreate(
            ['email' => 'admin@cici.test'],
            [
                'name'     => 'Administrador CICI 2027',
                'password' => Hash::make('Cici2027*'),
                'is_admin' => true,
            ]
        );

        // 2. Sembrar ejes temáticos, ponentes, fechas y contenido oficial del portal
        $this->call([
            ThematicAxisSeeder::class,
            SpeakerSeeder::class,
            ImportantDateSeeder::class,
            CongressContentSeeder::class,
        ]);

        $civilAxis = ThematicAxis::where('name', 'like', '%infraestructura%')->first();
        $envAxis   = ThematicAxis::where('name', 'like', '%Ambiente%')->first();

        // 3. Líder del Comité Científico (Ingeniería Civil)
        User::updateOrCreate(
            ['email' => 'comite@cici.test'],
            [
                'name'                => 'Prof. Fabián · Líder Comité Científico',
                'password'            => Hash::make('Comite2027*'),
                'is_committee'        => true,
                'is_committee_leader' => true,
                'specialty'           => 'Ingeniería Civil',
                'thematic_axis_id'    => $civilAxis?->id,
            ]
        );

        // 4. Segundo miembro evaluador del Comité Científico
        User::updateOrCreate(
            ['email' => 'evaluador@cici.test'],
            [
                'name'                => 'Dra. Elena Evaluadora · Comité Científico',
                'password'            => Hash::make('Comite2027*'),
                'is_committee'        => true,
                'is_committee_leader' => false,
                'specialty'           => 'Ingeniería Ambiental',
                'thematic_axis_id'    => $envAxis?->id,
            ]
        );

        // 5. Resúmenes de prueba para consulta pública por cédula y evaluación
        $this->call([
            SubmissionSeeder::class,
        ]);
    }
}
