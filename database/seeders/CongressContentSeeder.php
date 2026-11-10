<?php

namespace Database\Seeders;

use App\Models\CongressDocument;
use App\Models\CongressSetting;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\NewsPost;
use App\Models\OrganizerMember;
use App\Models\RegistrationFee;
use App\Models\ScheduleItem;
use App\Models\Sponsor;
use App\Models\ThematicAxis;
use Illuminate\Database\Seeder;

class CongressContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Configuración general e institucional (RF-01 · editable para futuras ediciones ej. CICI 2028)
        $settings = [
            ['key' => 'event_name', 'label' => 'Nombre oficial del evento', 'value' => 'IV Congreso Internacional de Ciencia e Ingeniería', 'group' => 'general'],
            ['key' => 'event_year', 'label' => 'Año de la edición (ej. 2027 o 2028)', 'value' => '2027', 'group' => 'general'],
            ['key' => 'event_edition_badge', 'label' => 'Insignia de edición en portada (ej. IV edición · 2027)', 'value' => 'IV edición · 2027', 'group' => 'general'],
            ['key' => 'event_dates_label', 'label' => 'Fechas de realización', 'value' => '14, 15 y 16 de abril de 2027', 'group' => 'general'],
            ['key' => 'event_start_iso', 'label' => 'Fecha/hora de inicio para el reloj regresivo (formato ISO)', 'value' => '2027-04-14T00:00:00-05:00', 'group' => 'general'],
            ['key' => 'event_modality', 'label' => 'Modalidad del evento', 'value' => 'Modalidad mixta · Presencial y virtual', 'group' => 'general'],
            ['key' => 'event_logo_white', 'label' => 'Logo principal blanco (Banner y Pie de página)', 'value' => '', 'group' => 'identidad'],
            ['key' => 'event_logo_sigla', 'label' => 'Logo sigla barra superior e ícono', 'value' => '/images/cici-sigla.png', 'group' => 'identidad'],
            ['key' => 'contact_email', 'label' => 'Correo electrónico oficial', 'value' => 'congresoingenieria@umariana.edu.co', 'group' => 'contacto'],
            ['key' => 'contact_phone', 'label' => 'Teléfono de contacto', 'value' => '318 6063123', 'group' => 'contacto'],
            ['key' => 'contact_address', 'label' => 'Dirección física', 'value' => 'Calle 18 No. 34-104 · Campus Universitario Alvernia, San Juan de Pasto - Nariño, Colombia', 'group' => 'contacto'],
            ['key' => 'abstract_form_url', 'label' => 'Enlace del formulario oficial de envío de resúmenes (Google Forms)', 'value' => 'https://forms.gle/GoMs6wT4ceXXD4GV9', 'group' => 'enlaces'],
            ['key' => 'abstract_template_url', 'label' => 'Enlace del formato oficial de resumen (Google Docs)', 'value' => 'https://docs.google.com/document/d/1aC80AhrR2_4r7MohHf30_srPf1kNfsPF/edit?usp=drive_link', 'group' => 'enlaces'],
            ['key' => 'registration_url', 'label' => 'Portal de inscripciones y pagos (SGA Campus Universidad Mariana)', 'value' => 'https://fenix.umariana.edu.co/sgacampus/services/inscripciones/home', 'group' => 'enlaces'],
            ['key' => 'poster_template_url', 'label' => 'Enlace externo de plantilla de póster (dejar vacío mientras sea Próximamente)', 'value' => '', 'group' => 'enlaces'],
            ['key' => 'terms_url', 'label' => 'Enlace de convocatoria / términos de referencia (PDF oficial)', 'value' => '/documentos/terminos-de-referencia-cici-2027.pdf', 'group' => 'enlaces'],
        ];

        foreach ($settings as $setting) {
            CongressSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
        CongressSetting::clearCache();

        // 2. Tarifas oficiales de inscripción según cici.umariana.edu.co (RF-06)
        $fees = [
            [
                'profile'         => 'Estudiantes y profesionales',
                'description'     => 'Inscripción para asistentes estudiantes y profesionales.',
                'price_formatted' => '$ 50.000',
                'price_amount'    => 50000,
                'currency'        => 'COP',
                'includes'        => 'Acceso a conferencias magistrales, sesiones académicas y certificado de asistencia.',
                'sort_order'      => 1,
                'is_active'       => true,
            ],
            [
                'profile'         => 'Ponente nacional · póster',
                'description'     => 'Inscripción para ponentes nacionales con presentación en modalidad póster presencial.',
                'price_formatted' => '$ 250.000',
                'price_amount'    => 250000,
                'currency'        => 'COP',
                'includes'        => 'Presentación de póster presencial, hasta 7 autores por proyecto y certificado de ponente.',
                'sort_order'      => 2,
                'is_active'       => true,
            ],
            [
                'profile'         => 'Ponente nacional · oral',
                'description'     => 'Inscripción para ponentes nacionales con presentación oral, en modalidad presencial o virtual. Las ponencias virtuales están sujetas a cupos limitados.',
                'price_formatted' => '$ 350.000',
                'price_amount'    => 350000,
                'currency'        => 'COP',
                'includes'        => 'Presentación oral presencial o virtual, hasta 7 autores por proyecto y certificado de ponente.',
                'sort_order'      => 3,
                'is_active'       => true,
            ],
            [
                'profile'         => 'Ponente internacional',
                'description'     => 'Inscripción para ponentes internacionales, en modalidad presencial o virtual. Las ponencias virtuales están sujetas a cupos limitados.',
                'price_formatted' => '$ 500.000',
                'price_amount'    => 500000,
                'currency'        => 'COP',
                'includes'        => 'Presentación oral o póster internacional, hasta 7 autores por proyecto y certificado de ponente.',
                'sort_order'      => 4,
                'is_active'       => true,
            ],
        ];

        foreach ($fees as $fee) {
            RegistrationFee::updateOrCreate(['profile' => $fee['profile']], $fee);
        }

        // 3. Documentos y plantillas oficiales administrables (RF-07)
        $documents = [
            [
                'slug'         => 'material-difusion',
                'title'        => 'Material de difusión (banners oficiales)',
                'category'     => 'material_difusion',
                'description'  => 'Descarga las piezas gráficas oficiales del CICI 2027 para difusión institucional y redes sociales.',
                'external_url' => '/documentos/material-difusion',
                'file_path'    => null,
                'version'      => '2027.1',
                'status'       => 'disponible',
                'sort_order'   => 1,
            ],
            [
                'slug'         => 'convocatoria',
                'title'        => 'Convocatoria / Términos de referencia (PDF)',
                'category'     => 'convocatoria',
                'description'  => 'Documento oficial con las condiciones y orientaciones para participar en el Congreso.',
                'external_url' => '/documentos/terminos-de-referencia-cici-2027.pdf',
                'file_path'    => null,
                'version'      => '2027.1',
                'status'       => 'disponible',
                'sort_order'   => 2,
            ],
            [
                'slug'         => 'formato-resumen',
                'title'        => 'Formato de resumen',
                'category'     => 'formato_resumen',
                'description'  => 'Plantilla oficial para la preparación y presentación del resumen de la propuesta.',
                'external_url' => 'https://docs.google.com/document/d/1aC80AhrR2_4r7MohHf30_srPf1kNfsPF/edit?usp=drive_link',
                'file_path'    => null,
                'version'      => '2027.1',
                'status'       => 'disponible',
                'sort_order'   => 3,
            ],
            [
                'slug'         => 'plantilla-poster',
                'title'        => 'Plantilla de póster',
                'category'     => 'plantilla_poster',
                'description'  => 'Formato oficial para la presentación de trabajos mediante póster.',
                'external_url' => null,
                'file_path'    => null,
                'version'      => '2027.1',
                'status'       => 'proximamente',
                'sort_order'   => 4,
            ],
        ];

        foreach ($documents as $doc) {
            CongressDocument::updateOrCreate(['slug' => $doc['slug']], $doc);
        }

        // 4. Programa y agenda académica oficial publicada en cici.umariana.edu.co (RF-04)
        ScheduleItem::whereIn('title', [
            'Instalación oficial del CICI 2027',
            'Conferencia Magistral: Inteligencia Artificial Generativa y Herramientas Emergentes',
            'Sesiones paralelas de ponencias orales y pósteres por ejes temáticos',
            'Panel de clausura y entrega de reconocimientos a mejores ponencias',
        ])->delete();
        $axes = ThematicAxis::orderBy('sort_order')->get();
        $schedule = [
            // --- MIÉRCOLES 14 DE ABRIL DE 2027 (JORNADA DE APERTURA) ---
            [
                'day_label'        => 'Miércoles 14 de abril de 2027 · Jornada de apertura',
                'event_date'       => '2027-04-14',
                'start_time'       => '08:00',
                'end_time'         => '09:00',
                'title'            => 'Acto protocolario de apertura',
                'description'      => 'Instalación oficial del IV Congreso Internacional de Ciencia e Ingeniería CICI 2027.',
                'speaker_name'     => 'Comité Directivo · Facultad de Ingeniería',
                'location'         => 'Auditorio Cámara de Comercio de Pasto',
                'activity_type'    => 'apertura',
                'thematic_axis_id' => null,
                'sort_order'       => 1,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Miércoles 14 de abril de 2027 · Jornada de apertura',
                'event_date'       => '2027-04-14',
                'start_time'       => '09:00',
                'end_time'         => '10:00',
                'title'            => 'Conferencia magistral: Inteligencia Artificial Aplicada e Innovación Digital',
                'description'      => 'Conferencia magistral del eje transversal de Inteligencia Artificial Aplicada e Innovación Digital.',
                'speaker_name'     => 'Dra. Cathy Pamela Guevara Vega (UTN, Ecuador)',
                'location'         => 'Auditorio Cámara de Comercio de Pasto',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(5)?->id,
                'sort_order'       => 2,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Miércoles 14 de abril de 2027 · Jornada de apertura',
                'event_date'       => '2027-04-14',
                'start_time'       => '10:00',
                'end_time'         => '10:30',
                'title'            => 'Coffee break',
                'description'      => 'Espacio de receso y networking académico.',
                'speaker_name'     => null,
                'location'         => 'Auditorio Cámara de Comercio de Pasto',
                'activity_type'    => 'cultural',
                'thematic_axis_id' => null,
                'sort_order'       => 3,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Miércoles 14 de abril de 2027 · Jornada de apertura',
                'event_date'       => '2027-04-14',
                'start_time'       => '10:30',
                'end_time'         => '13:00',
                'title'            => 'Ponencias del eje transversal: Inteligencia Artificial Aplicada e Innovación Digital',
                'description'      => 'Sesión de ponencias orales aprobadas en el eje transversal.',
                'speaker_name'     => 'Ponentes seleccionados',
                'location'         => 'Auditorio Cámara de Comercio de Pasto',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => $axes->get(5)?->id,
                'sort_order'       => 4,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Miércoles 14 de abril de 2027 · Jornada de apertura',
                'event_date'       => '2027-04-14',
                'start_time'       => '14:00',
                'end_time'         => '15:00',
                'title'            => 'Conferencia magistral: Ingeniería Civil + Presentación de pósteres y stands',
                'description'      => 'Conferencia magistral en el Auditorio Jesús de Nazareth y muestra simultánea de pósteres y stands de empresas patrocinadoras en el Pasillo de la cordialidad.',
                'speaker_name'     => 'Dr. Mauricio Alvarado Bueno (Universidad Santiago de Cali)',
                'location'         => 'Auditorio Jesús de Nazareth · Pasillo de la cordialidad',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(1)?->id,
                'sort_order'       => 5,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Miércoles 14 de abril de 2027 · Jornada de apertura',
                'event_date'       => '2027-04-14',
                'start_time'       => '15:00',
                'end_time'         => '16:00',
                'title'            => 'Ponencias: Diseño y construcción de obras de infraestructura + Ponencias virtuales + Pósteres',
                'description'      => 'Sesiones paralelas presenciales, virtuales y exhibición de pósteres científicos.',
                'speaker_name'     => 'Ponentes presenciales y virtuales',
                'location'         => 'Auditorio Jesús de Nazareth · Sala Shumacher · Pasillo de la cordialidad',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => $axes->get(1)?->id,
                'sort_order'       => 6,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Miércoles 14 de abril de 2027 · Jornada de apertura',
                'event_date'       => '2027-04-14',
                'start_time'       => '16:30',
                'end_time'         => '18:30',
                'title'            => 'Ponencias: Diseño y construcción de obras de infraestructura + Ponencias virtuales',
                'description'      => 'Cierre de sesiones académicas del primer día tras el receso de 16:00 a 16:30.',
                'speaker_name'     => 'Ponentes presenciales y virtuales',
                'location'         => 'Auditorio Jesús de Nazareth · Sala Shumacher',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => $axes->get(1)?->id,
                'sort_order'       => 7,
                'is_published'     => true,
            ],

            // --- JUEVES 15 DE ABRIL DE 2027 (JORNADA ACADÉMICA) ---
            [
                'day_label'        => 'Jueves 15 de abril de 2027 · Jornada académica',
                'event_date'       => '2027-04-15',
                'start_time'       => '08:00',
                'end_time'         => '09:00',
                'title'            => 'Conferencias magistrales simultáneas: Sistemas y Ambiental',
                'description'      => 'Conferencia magistral de Sistemas (Auditorio San José) y Conferencia magistral Ambiental (Auditorio Jesús de Nazareth).',
                'speaker_name'     => 'Dr. José Antonio Quiña Mera (UTN) · Dr. Samir Fernando Castilla Acevedo (Kansas State University)',
                'location'         => 'Auditorio San José · Auditorio Jesús de Nazareth',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(2)?->id,
                'sort_order'       => 8,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jueves 15 de abril de 2027 · Jornada académica',
                'event_date'       => '2027-04-15',
                'start_time'       => '09:00',
                'end_time'         => '13:00',
                'title'            => 'Ponencias: Ingeniería de Sistemas + Ambiente y Desarrollo Sostenible (incluye Coffee break 10:00–10:30)',
                'description'      => 'Sesiones paralelas de ponencias orales en los ejes de Ingeniería, Informática y Computación y Ambiente y Desarrollo Sostenible.',
                'speaker_name'     => 'Ponentes seleccionados',
                'location'         => 'Auditorio San José · Auditorio Jesús de Nazareth',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => $axes->get(3)?->id,
                'sort_order'       => 9,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jueves 15 de abril de 2027 · Jornada académica',
                'event_date'       => '2027-04-15',
                'start_time'       => '14:00',
                'end_time'         => '16:00',
                'title'            => 'Conferencia magistral: Ambiental + Ponencias Ingeniería de Sistemas + Ponencias virtuales',
                'description'      => 'Conferencia magistral del Dr. Marcial Reyes Cázarez en el Auditorio Jesús de Nazareth y ponencias simultáneas.',
                'speaker_name'     => 'Dr. Marcial Reyes Cázarez (TecNM Pátzcuaro, México)',
                'location'         => 'Auditorio Jesús de Nazareth · Auditorio San José · Sala Shumacher',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(3)?->id,
                'sort_order'       => 10,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jueves 15 de abril de 2027 · Jornada académica',
                'event_date'       => '2027-04-15',
                'start_time'       => '16:30',
                'end_time'         => '18:30',
                'title'            => 'Ponencias: Ingeniería de Sistemas + Ambiente y Desarrollo Sostenible + Ponencias virtuales',
                'description'      => 'Sesiones de cierre de la segunda jornada tras el receso de 16:00 a 16:30.',
                'speaker_name'     => 'Ponentes presenciales y virtuales',
                'location'         => 'Auditorio San José · Auditorio Jesús de Nazareth · Sala Shumacher y salas 1 y 2 de San Buenaventura',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => null,
                'sort_order'       => 11,
                'is_published'     => true,
            ],

            // --- VIERNES 16 DE ABRIL DE 2027 (JORNADA DE CIERRE) ---
            [
                'day_label'        => 'Viernes 16 de abril de 2027 · Jornada de cierre',
                'event_date'       => '2027-04-16',
                'start_time'       => '08:00',
                'end_time'         => '09:00',
                'title'            => 'Conferencias magistrales simultáneas: Mecatrónica y Procesos',
                'description'      => 'Conferencia magistral de Mecatrónica (Auditorio San José) y Conferencia magistral de Procesos (Auditorio Jesús de Nazareth).',
                'speaker_name'     => 'Dr. Luis Pérez Domínguez (UACJ, México) · Ing. César Augusto Patiño Suárez (Ecopetrol)',
                'location'         => 'Auditorio San José · Auditorio Jesús de Nazareth',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(4)?->id,
                'sort_order'       => 12,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Viernes 16 de abril de 2027 · Jornada de cierre',
                'event_date'       => '2027-04-16',
                'start_time'       => '09:00',
                'end_time'         => '13:00',
                'title'            => 'Ponencias: Sistemas Mecatrónicos + Diseño, Gestión, Innovación y Optimización de Procesos (incluye Coffee break 10:00–10:30)',
                'description'      => 'Sesiones académicas de ponencias orales en los ejes de Mecatrónica y Procesos.',
                'speaker_name'     => 'Ponentes seleccionados',
                'location'         => 'Auditorio San José · Auditorio Jesús de Nazareth',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => $axes->get(0)?->id,
                'sort_order'       => 13,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Viernes 16 de abril de 2027 · Jornada de cierre',
                'event_date'       => '2027-04-16',
                'start_time'       => '14:00',
                'end_time'         => '16:00',
                'title'            => 'Ponencias: Sistemas Mecatrónicos + Procesos + Ponencias virtuales',
                'description'      => 'Sesiones paralelas presenciales y virtuales de la tarde.',
                'speaker_name'     => 'Ponentes presenciales y virtuales',
                'location'         => 'Auditorio San José · Auditorio Jesús de Nazareth · Sala Shumacher · Sala 1 de San Buenaventura',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => null,
                'sort_order'       => 14,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Viernes 16 de abril de 2027 · Jornada de cierre',
                'event_date'       => '2027-04-16',
                'start_time'       => '16:30',
                'end_time'         => '18:30',
                'title'            => 'Clausura del evento',
                'description'      => 'Actividad conjunta de cierre oficial del IV Congreso Internacional de Ciencia e Ingeniería CICI 2027.',
                'speaker_name'     => 'Comité Organizador · Facultad de Ingeniería',
                'location'         => 'Madre Caridad · Actividad conjunta',
                'activity_type'    => 'cultural',
                'thematic_axis_id' => null,
                'sort_order'       => 15,
                'is_published'     => true,
            ],
        ];

        foreach ($schedule as $item) {
            ScheduleItem::updateOrCreate(
                [
                    'event_date' => $item['event_date'],
                    'start_time' => $item['start_time'],
                    'title'      => $item['title'],
                ],
                $item
            );
        }

        // 5. Noticias, novedades y avisos según cici.umariana.edu.co (RF-08)
        $news = [
            [
                'title'        => 'Convocatoria de resúmenes',
                'slug'         => 'convocatoria-de-resumenes',
                'category'     => 'CICI 2027',
                'summary'      => 'La recepción de resúmenes está programada para iniciar el 1 de octubre de 2026.',
                'content'      => 'La Facultad de Ingeniería de la Universidad Mariana invita a investigadores, docentes y estudiantes a postular sus resúmenes en los 6 ejes temáticos del congreso. Los Términos de Referencia y el Formato Oficial de Resumen ya se encuentran disponibles.',
                'image_path'   => '/images/piezas/banner-general.png',
                'external_url' => 'https://forms.gle/GoMs6wT4ceXXD4GV9',
                'is_featured'  => true,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title'        => 'Ejes temáticos',
                'slug'         => 'ejes-tematicos',
                'category'     => 'CICI 2027',
                'summary'      => 'Consulta las áreas y subejes definidos para la cuarta edición del Congreso.',
                'content'      => 'El IV CICI 2027 articula seis ejes temáticos oficiales con 34 subtemas especializados, incluyendo el eje transversal de Inteligencia Artificial Aplicada e Innovación Digital.',
                'image_path'   => '/images/piezas/banner-ejes-tematicos.png',
                'external_url' => null,
                'is_featured'  => true,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title'        => 'Información de ponentes y agenda académica',
                'slug'         => 'informacion-de-ponentes',
                'category'     => 'Actualidad',
                'summary'      => 'La sección de ponentes y el programa del Congreso fueron actualizados con horarios, auditorios y enlaces a perfiles académicos.',
                'content'      => 'Conoce los perfiles académicos de los 7 ponentes invitados de Estados Unidos, México, Ecuador y Colombia, así como la programación detallada de los días 14, 15 y 16 de abril de 2027.',
                'image_path'   => '/images/umariana.jpg',
                'external_url' => '/documentos/terminos-de-referencia-cici-2027.pdf',
                'is_featured'  => false,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($news as $post) {
            NewsPost::updateOrCreate(['slug' => $post['slug']], $post);
        }

        // 6. Comité Organizador y Científico público (RF-09)
        $organizers = [
            [
                'name'            => 'Decanatura · Facultad de Ingeniería',
                'role'            => 'Dirección General del Congreso',
                'committee_type'  => 'directivo',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Facultad de Ingeniería',
                'email'           => 'congresoingenieria@umariana.edu.co',
                'sort_order'      => 1,
                'is_active'       => true,
            ],
            [
                'name'            => 'Liderazgo del Comité Científico · Ingeniería Civil',
                'role'            => 'Coordinación de Arbitraje y Evaluación de Resúmenes',
                'committee_type'  => 'cientifico',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería Civil',
                'email'           => 'congresoingenieria@umariana.edu.co',
                'sort_order'      => 2,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería Ambiental',
                'role'            => 'Coordinación Eje de Ambiente y Desarrollo Sostenible',
                'committee_type'  => 'cientifico',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería Ambiental',
                'email'           => null,
                'sort_order'      => 3,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería de Sistemas',
                'role'            => 'Coordinación Eje de Ingeniería, Informática, Computación e IA Aplicada',
                'committee_type'  => 'organizador',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería de Sistemas',
                'email'           => null,
                'sort_order'      => 4,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería Mecatrónica',
                'role'            => 'Coordinación Eje de Desarrollo Mecatrónico',
                'committee_type'  => 'organizador',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería Mecatrónica',
                'email'           => null,
                'sort_order'      => 5,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería de Procesos',
                'role'            => 'Coordinación Eje de Diseño, Gestión, Innovación y Optimización de Procesos',
                'committee_type'  => 'organizador',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería de Procesos',
                'email'           => null,
                'sort_order'      => 6,
                'is_active'       => true,
            ],
        ];

        foreach ($organizers as $org) {
            OrganizerMember::updateOrCreate(['name' => $org['name']], $org);
        }

        // 7. Galería e histórico de ediciones anteriores (RF-10)
        $gallery = [
            [
                'edition'            => 'I CICI',
                'year'               => 2021,
                'title'              => 'I Congreso Internacional de Ciencia e Ingeniería',
                'description'        => 'Primera edición institucional que consolidó el espacio de diálogo científico entre los programas de la Facultad de Ingeniería e investigadores invitados de Iberoamérica.',
                'image_path'         => 'images/umariana.jpg',
                'participants_count' => '+450 asistentes',
                'papers_count'       => '+60 ponencias',
                'sort_order'         => 1,
                'is_published'       => true,
            ],
            [
                'edition'            => 'II CICI',
                'year'               => 2023,
                'title'              => 'II Congreso Internacional de Ciencia e Ingeniería',
                'description'        => 'Fortalecimiento de redes internacionales de cooperación científica en sostenibilidad territorial, infraestructura resiliente y transformación tecnológica.',
                'image_path'         => 'images/piezas/banner-general.png',
                'participants_count' => '+600 asistentes',
                'papers_count'       => '+85 trabajos arbitrados',
                'sort_order'         => 2,
                'is_published'       => true,
            ],
            [
                'edition'            => 'III CICI',
                'year'               => 2025,
                'title'              => 'III Congreso Internacional de Ciencia e Ingeniería',
                'description'        => 'Integración multidisciplinar de grupos y semilleros de investigación con conferencias magistrales internacionales, sesiones de pósteres y memorias académicas.',
                'image_path'         => 'images/piezas/banner-ejes-tematicos.png',
                'participants_count' => '+800 participantes',
                'papers_count'       => '+110 ponencias y pósteres',
                'sort_order'         => 3,
                'is_published'       => true,
            ],
        ];

        foreach ($gallery as $g) {
            GalleryItem::updateOrCreate(['edition' => $g['edition']], $g);
        }

        // 8. Aliados e instituciones participantes actualizadas según cici.umariana.edu.co (RF-11)
        Sponsor::whereIn('name', [
            'Gobernación de Nariño / Alcaldía de Pasto',
            'Red Colombiana de Facultades de Ingeniería (ACOFI)',
            'Kansas State University · Olathe Horticulture Center',
        ])->delete();
        $sponsors = [
            [
                'name'        => 'Universidad Mariana · Facultad de Ingeniería',
                'category'    => 'organizador',
                'country'     => 'Colombia',
                'logo_path'   => 'images/logo-umariana.png',
                'website_url' => 'https://www.umariana.edu.co',
                'description' => 'Institución organizadora y sede oficial del IV CICI 2027 en San Juan de Pasto, Nariño.',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'name'        => 'Universidad Técnica del Norte (UTN)',
                'category'    => 'aliado_academico',
                'country'     => 'Ecuador',
                'logo_path'   => null,
                'website_url' => 'https://www.utn.edu.ec',
                'description' => 'Participación de conferencistas magistrales en Inteligencia Artificial e Ingeniería de Software.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'name'        => 'Kansas State University',
                'category'    => 'aliado_academico',
                'country'     => 'Estados Unidos',
                'logo_path'   => null,
                'website_url' => 'https://www.k-state.edu',
                'description' => 'Participación magistral internacional en Ambiente y Desarrollo Sostenible.',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'name'        => 'Universidad Autónoma de Ciudad Juárez (UACJ)',
                'category'    => 'aliado_academico',
                'country'     => 'México',
                'logo_path'   => null,
                'website_url' => 'https://www.uacj.mx',
                'description' => 'Participación magistral en Desarrollo Mecatrónico e Inteligencia Artificial.',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'name'        => 'Tecnológico Nacional de México · ITS Pátzcuaro',
                'category'    => 'aliado_academico',
                'country'     => 'México',
                'logo_path'   => null,
                'website_url' => 'https://www.tecnm.mx',
                'description' => 'Participación académica internacional en el eje de Ambiente y Desarrollo Sostenible.',
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'name'        => 'Universidad Santiago de Cali (USC)',
                'category'    => 'aliado_academico',
                'country'     => 'Colombia',
                'logo_path'   => null,
                'website_url' => 'https://www.usc.edu.co',
                'description' => 'Participación magistral en Diseño y construcción de obras de infraestructura.',
                'sort_order'  => 6,
                'is_active'   => true,
            ],
        ];

        foreach ($sponsors as $s) {
            Sponsor::updateOrCreate(['name' => $s['name']], $s);
        }

        // 9. Preguntas frecuentes - FAQ (RF-12)
        $faqs = [
            [
                'question'     => '¿Cómo postulo mi resumen al IV CICI 2027?',
                'answer'       => 'Debes consultar primero los Términos de Referencia (PDF) y descargar el Formato de resumen disponibles en la sección de Documentos, diligenciar tu propuesta y enviarla a través del formulario oficial antes del 26 de febrero de 2027.',
                'category'     => 'resumenes',
                'sort_order'   => 1,
                'is_published' => true,
            ],
            [
                'question'     => '¿Cómo consulto si mi resumen fue aprobado para Ponencia Oral o Póster?',
                'answer'       => 'No necesitas crear una cuenta ni recordar contraseña. Ingresa a la opción "Consultar estado con cédula" (/estado-resumen) y escribe tu número de documento de identidad. El sistema te mostrará el dictamen del Comité Científico, las observaciones y la modalidad asignada.',
                'category'     => 'resumenes',
                'sort_order'   => 2,
                'is_published' => true,
            ],
            [
                'question'     => '¿Cuántos autores se permiten por proyecto registrado?',
                'answer'       => 'De acuerdo con los Términos de Referencia oficiales del congreso, el pago de inscripción se realiza por proyecto y cada proyecto podrá estar integrado por un máximo de 7 autores.',
                'category'     => 'inscripciones',
                'sort_order'   => 3,
                'is_published' => true,
            ],
            [
                'question'     => '¿Dónde realizo la inscripción y pago una vez aprobado mi trabajo o como asistente?',
                'answer'       => 'El proceso de inscripción financiera se realiza directamente en el portal oficial SGA Campus de la Universidad Mariana (https://fenix.umariana.edu.co/sgacampus/services/inscripciones/home).',
                'category'     => 'inscripciones',
                'sort_order'   => 4,
                'is_published' => true,
            ],
            [
                'question'     => '¿Dónde descargo la plantilla oficial si mi trabajo fue aprobado en modalidad Póster?',
                'answer'       => 'Una vez sea publicada oficialmente por el Comité Organizador, podrás descargarla tanto en la sección de Documentos del sitio principal como directamente desde la pantalla de resultados al consultar tu estado por cédula.',
                'category'     => 'modalidades',
                'sort_order'   => 5,
                'is_published' => true,
            ],
            [
                'question'     => '¿El congreso entrega certificado y memorias académicas?',
                'answer'       => 'Sí. Todos los participantes inscritos que cumplan con la asistencia reciben certificado institucional, y los trabajos aprobados y sustentados se integran en las memorias académicas del congreso.',
                'category'     => 'certificados',
                'sort_order'   => 6,
                'is_published' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
