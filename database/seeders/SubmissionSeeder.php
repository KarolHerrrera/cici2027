<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\ThematicAxis;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $civilAxis = ThematicAxis::where('name', 'like', '%infraestructura%')->first();
        $compAxis  = ThematicAxis::where('name', 'like', '%Informática%')->first();
        $envAxis   = ThematicAxis::where('name', 'like', '%Ambiente%')->first();
        $leader    = User::where('email', 'comite@cici.test')->first();

        $ejemplos = [
            [
                'tracking_code'      => 'CICI-2027-0001',
                'document_type'      => 'CC',
                'document'           => '1085200101',
                'name'               => 'Ing. Camila Andrea Rosero Burbano',
                'email'              => 'camila.rosero@umariana.edu.co',
                'phone'              => '315 640 8821',
                'institution'        => 'Universidad Mariana',
                'city'               => 'Pasto',
                'country'            => 'Colombia',
                'coauthors'          => 'Dr. Fabián Andrés Muñoz, Ing. Carlos Solarte',
                'title'              => 'Evaluación mecánica de concretos permeables con agregados reciclados de demolición en zonas andinas',
                'thematic_axis_id'   => $civilAxis?->id,
                'axis_topic_id'      => $civilAxis?->topics()->first()?->id,
                'requested_modality' => Submission::MODALITY_ORAL,
                'abstract_text'      => 'Esta investigación evalúa el desempeño hidráulico y la resistencia a la compresión de mezclas de concreto permeable elaboradas con sustitución parcial (25%, 50% y 75%) de agregado grueso natural por concreto reciclado proveniente de residuos de construcción y demolición (RCD) en el municipio de Pasto. Los ensayos de permeabilidad e infiltración demuestran tasas superiores a 180 mm/min con resistencias aptas para sistemas urbanos de drenaje sostenible (SUDS).',
                'keywords'           => 'concreto permeable, agregados reciclados, drenaje urbano sostenible, ingeniería civil',
                'status'             => Submission::STATUS_APPROVED,
                'modality'           => Submission::MODALITY_POSTER,
                'score'              => 90,
                'review_comment'     => 'Trabajo pertinente y con sólidos resultados experimentales. El Comité Científico lo aprueba para presentación en Modalidad Póster con el fin de destacar las gráficas comparativas de permeabilidad y granulometría. Ya puedes descargar la plantilla oficial de póster.',
                'reviewed_by'        => $leader?->id,
                'reviewed_at'        => now()->subDays(2),
                'notified_at'        => now()->subDays(2),
            ],
            [
                'tracking_code'      => 'CICI-2027-0002',
                'document_type'      => 'CC',
                'document'           => '1085200202',
                'name'               => 'Mg. Juan Sebastián Delgado Pantoja',
                'email'              => 'jdelgado@udenar.edu.co',
                'phone'              => '316 422 1903',
                'institution'        => 'Universidad de Nariño',
                'city'               => 'Pasto',
                'country'            => 'Colombia',
                'coauthors'          => 'Dra. Laura Sofía Revelo',
                'title'              => 'Predicción temprana de deslizamientos en corredores viales de montaña mediante redes neuronales e IoT',
                'thematic_axis_id'   => $compAxis?->id,
                'axis_topic_id'      => $compAxis?->topics()->skip(1)->first()?->id,
                'requested_modality' => Submission::MODALITY_ORAL,
                'abstract_text'      => 'Se presenta una arquitectura de monitoreo geotécnico basada en nodos IoT de bajo consumo y modelos recurrentes LSTM para estimar la probabilidad de falla en taludes viales del suroccidente colombiano a partir de variables de humedad volumétrica del suelo y precipitación acumulada.',
                'keywords'           => 'IoT, aprendizaje profundo, alerta temprana, taludes, infraestructura vial',
                'status'             => Submission::STATUS_APPROVED,
                'modality'           => Submission::MODALITY_ORAL,
                'score'              => 95,
                'review_comment'     => 'Propuesta sobresaliente con alto impacto regional. Aprobada por el Comité Científico para Ponencia Oral en la sesión principal del eje.',
                'reviewed_by'        => $leader?->id,
                'reviewed_at'        => now()->subDay(),
                'notified_at'        => now()->subDay(),
            ],
            [
                'tracking_code'      => 'CICI-2027-0003',
                'document_type'      => 'CC',
                'document'           => '1085200303',
                'name'               => 'Ing. Diana Marcela Benavides',
                'email'              => 'dbenavides@unicauca.edu.co',
                'phone'              => '310 890 1144',
                'institution'        => 'Universidad del Cauca',
                'city'               => 'Popayán',
                'country'            => 'Colombia',
                'coauthors'          => null,
                'title'              => 'Biorreactores de membrana anaerobia para valorización energética de efluentes lácteos',
                'thematic_axis_id'   => $envAxis?->id,
                'axis_topic_id'      => $envAxis?->topics()->first()?->id,
                'requested_modality' => Submission::MODALITY_POSTER,
                'abstract_text'      => 'Se analiza la eficiencia de remoción de DQO y el potencial de producción de biogás en un biorreactor anaerobio de membrana a escala piloto operando con lactosuero ácido.',
                'keywords'           => 'biorreactor anaerobio, biogás, tratamiento de aguas, economía circular',
                'status'             => Submission::STATUS_UNDER_REVIEW,
                'modality'           => null,
                'score'              => null,
                'review_comment'     => null,
                'assigned_to'        => $leader?->id,
            ],
        ];

        foreach ($ejemplos as $item) {
            Submission::updateOrCreate(
                ['tracking_code' => $item['tracking_code']],
                $item
            );
        }
    }
}
