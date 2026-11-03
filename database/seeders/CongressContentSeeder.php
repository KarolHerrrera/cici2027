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
            ['key' => 'contact_email', 'label' => 'Correo electrónico oficial', 'value' => 'cici@umariana.edu.co', 'group' => 'contacto'],
            ['key' => 'contact_phone', 'label' => 'Teléfono de contacto', 'value' => '318 6063123', 'group' => 'contacto'],
            ['key' => 'contact_address', 'label' => 'Dirección física', 'value' => 'Calle 18 No. 34-104 · Campus Universitario Alvernia, San Juan de Pasto - Nariño, Colombia', 'group' => 'contacto'],
            ['key' => 'abstract_form_url', 'label' => 'Enlace del formulario oficial de envío de resúmenes (Google Forms)', 'value' => 'https://forms.gle/GoMs6wT4ceXXD4GV9', 'group' => 'enlaces'],
            ['key' => 'abstract_template_url', 'label' => 'Enlace del formato oficial de resumen (Google Docs)', 'value' => 'https://docs.google.com/document/d/1aC80AhrR2_4r7MohHf30_srPf1kNfsPF/edit?usp=drive_link', 'group' => 'enlaces'],
            ['key' => 'registration_url', 'label' => 'Portal de inscripciones y pagos (SGA Campus Universidad Mariana)', 'value' => 'https://fenix.umariana.edu.co/sgacampus/services/inscripciones/home', 'group' => 'enlaces'],
            ['key' => 'poster_template_url', 'label' => 'Enlace externo de plantilla de póster (dejar vacío mientras sea Próximamente)', 'value' => '', 'group' => 'enlaces'],
            ['key' => 'terms_url', 'label' => 'Enlace externo de convocatoria / términos de referencia (dejar vacío mientras sea Próximamente)', 'value' => '', 'group' => 'enlaces'],
        ];

        foreach ($settings as $setting) {
            CongressSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
        CongressSetting::clearCache();

        // 2. Tarifas oficiales de inscripción según cici.umariana.edu.co (RF-06)
        RegistrationFee::query()->delete();
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
            RegistrationFee::create($fee);
        }

        // 3. Documentos y plantillas oficiales administrables (RF-07)
        $documents = [
            [
                'slug'         => 'material-difusion',
                'title'        => 'Material de difusión (banners oficiales)',
                'category'     => 'material_difusion',
                'description'  => 'Banners oficiales del IV CICI 2027 y de los ejes temáticos para difusión académica e institucional.',
                'external_url' => '/documentos/material-difusion',
                'file_path'    => null,
                'version'      => '2027.1',
                'status'       => 'disponible',
                'sort_order'   => 1,
            ],
            [
                'slug'         => 'convocatoria',
                'title'        => 'Convocatoria / Términos de referencia',
                'category'     => 'convocatoria',
                'description'  => 'Lineamientos generales de participación, criterios de arbitraje y condiciones de publicación del IV CICI 2027.',
                'external_url' => null,
                'file_path'    => null,
                'version'      => '2027.1',
                'status'       => 'proximamente',
                'sort_order'   => 2,
            ],
            [
                'slug'         => 'formato-resumen',
                'title'        => 'Formato de resumen',
                'category'     => 'formato_resumen',
                'description'  => 'Plantilla oficial en Google Docs con la estructura requerida para postular resúmenes de ponencia oral o póster.',
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
                'description'  => 'Formato oficial de diseño y dimensiones para los trabajos aprobados por el Comité Científico en modalidad Póster.',
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

        // 4. Programa y agenda académica (RF-04)
        ScheduleItem::query()->delete();
        $axes = ThematicAxis::orderBy('sort_order')->get();
        $schedule = [
            [
                'day_label'        => 'Jornada 1 · Miércoles 14 de abril de 2027',
                'event_date'       => '2027-04-14',
                'start_time'       => '07:30',
                'end_time'         => '08:30',
                'title'            => 'Acreditación de participantes y acto inaugural del IV CICI 2027',
                'description'      => 'Recepción de participantes y apertura institucional a cargo de las directivas de la Universidad Mariana y la Facultad de Ingeniería.',
                'speaker_name'     => 'Comité Directivo · Facultad de Ingeniería',
                'location'         => 'Auditorio Madre Caridad Brader · Universidad Mariana',
                'activity_type'    => 'apertura',
                'thematic_axis_id' => null,
                'sort_order'       => 1,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 1 · Miércoles 14 de abril de 2027',
                'event_date'       => '2027-04-14',
                'start_time'       => '08:30',
                'end_time'         => '09:45',
                'title'            => 'IA responsable en la ingeniería de software: un marco empírico humano-IA para el desarrollo guiado por pruebas',
                'description'      => 'Conferencia magistral de apertura sobre ingeniería de software empírica e inteligencia artificial.',
                'speaker_name'     => 'Dra. Cathy Pamela Guevara Vega (UTN, Ecuador)',
                'location'         => 'Auditorio Madre Caridad Brader',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(5)?->id,
                'sort_order'       => 2,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 1 · Miércoles 14 de abril de 2027',
                'event_date'       => '2027-04-14',
                'start_time'       => '10:15',
                'end_time'         => '11:30',
                'title'            => 'Optimización aplicada al diseño de estructuras',
                'description'      => 'Conferencia magistral sobre confiabilidad estructural, monitoreo de salud estructural y algoritmos metaheurísticos.',
                'speaker_name'     => 'Dr. Jesús Daniel Villalba Morales (Pontificia Universidad Javeriana)',
                'location'         => 'Auditorio Madre Caridad Brader',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(2)?->id,
                'sort_order'       => 3,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 1 · Miércoles 14 de abril de 2027',
                'event_date'       => '2027-04-14',
                'start_time'       => '14:00',
                'end_time'         => '17:30',
                'title'            => 'Sesiones paralelas de Ponencias Orales y muestra de Pósteres Científicos',
                'description'      => 'Presentación de trabajos arbitrados en los ejes temáticos del congreso.',
                'speaker_name'     => 'Ponentes seleccionados por el Comité Científico',
                'location'         => 'Salas Académicas · Campus Universitario',
                'activity_type'    => 'ponencia',
                'thematic_axis_id' => null,
                'sort_order'       => 4,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 2 · Jueves 15 de abril de 2027',
                'event_date'       => '2027-04-15',
                'start_time'       => '08:30',
                'end_time'         => '09:45',
                'title'            => 'El rastro oculto del progreso: contaminantes emergentes en el ciclo del agua y los desafíos tecnológicos para su eliminación',
                'description'      => 'Conferencia magistral sobre procesos de oxidación avanzada y sostenibilidad hídrica.',
                'speaker_name'     => 'Dr. Samir Fernando Castilla Acevedo (UMNG, Colombia)',
                'location'         => 'Auditorio Madre Caridad Brader',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(1)?->id,
                'sort_order'       => 5,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 2 · Jueves 15 de abril de 2027',
                'event_date'       => '2027-04-15',
                'start_time'       => '10:15',
                'end_time'         => '11:30',
                'title'            => 'Evaluación de la resistencia al daño por humedad en mezclas asfálticas utilizando el ensayo SATS',
                'description'      => 'Conferencia magistral sobre desempeño de pavimentos y materiales viales sostenibles.',
                'speaker_name'     => 'Ing. César Augusto Patiño Suárez (Universidad Mariana)',
                'location'         => 'Auditorio Madre Caridad Brader',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(2)?->id,
                'sort_order'       => 6,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 2 · Jueves 15 de abril de 2027',
                'event_date'       => '2027-04-15',
                'start_time'       => '14:00',
                'end_time'         => '16:30',
                'title'            => 'De la idea al artículo científico: IA generativa como copiloto de investigación responsable + Exhibición de Pósteres',
                'description'      => 'Conferencia magistral del Dr. José Antonio Quiña Mera seguida de la sesión interactiva de evaluación de pósteres científicos.',
                'speaker_name'     => 'Dr. José Antonio Quiña Mera (UTN, Ecuador)',
                'location'         => 'Auditorio Madre Caridad Brader y Hall de Ingeniería',
                'activity_type'    => 'poster',
                'thematic_axis_id' => $axes->get(5)?->id,
                'sort_order'       => 7,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 3 · Viernes 16 de abril de 2027',
                'event_date'       => '2027-04-16',
                'start_time'       => '08:30',
                'end_time'         => '10:00',
                'title'            => 'La ingeniería química y los sistemas bioinspirados para procesos sostenibles',
                'description'      => 'Conferencia magistral internacional sobre bioprocesos y valorización de residuos agroindustriales.',
                'speaker_name'     => 'Dr. Marcial Reyes Cázarez (CINVESTAV, México)',
                'location'         => 'Auditorio Madre Caridad Brader',
                'activity_type'    => 'conferencia',
                'thematic_axis_id' => $axes->get(3)?->id,
                'sort_order'       => 8,
                'is_published'     => true,
            ],
            [
                'day_label'        => 'Jornada 3 · Viernes 16 de abril de 2027',
                'event_date'       => '2027-04-16',
                'start_time'       => '10:30',
                'end_time'         => '12:00',
                'title'            => 'Toma de decisiones multicriterio e Industria 4.0 + Ceremonia de clausura',
                'description'      => 'Conferencia magistral de cierre y entrega de reconocimientos del IV CICI 2027.',
                'speaker_name'     => 'Dr. Luis Pérez Domínguez (UACJ, México)',
                'location'         => 'Auditorio Madre Caridad Brader',
                'activity_type'    => 'cultural',
                'thematic_axis_id' => $axes->get(4)?->id,
                'sort_order'       => 9,
                'is_published'     => true,
            ],
        ];

        foreach ($schedule as $item) {
            ScheduleItem::create($item);
        }

        // 5. Noticias, novedades y avisos según cici.umariana.edu.co (RF-08)
        NewsPost::query()->delete();
        $news = [
            [
                'title'        => 'Convocatoria de resúmenes',
                'slug'         => 'convocatoria-de-resumenes',
                'category'     => 'CICI 2027',
                'summary'      => 'La recepción de resúmenes está programada para iniciar el 1 de octubre de 2026.',
                'content'      => 'La Facultad de Ingeniería de la Universidad Mariana invita a investigadores, docentes y estudiantes a postular sus resúmenes en los 6 ejes temáticos del congreso. El formato oficial de resumen ya se encuentra disponible para descarga.',
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
                'content'      => 'El IV CICI 2027 articula seis ejes temáticos oficiales con 34 subtemas especializados, incluyendo el eje de Inteligencia Artificial Aplicada e Innovación Digital.',
                'image_path'   => '/images/piezas/banner-ejes-tematicos.png',
                'external_url' => null,
                'is_featured'  => true,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title'        => 'Información de ponentes',
                'slug'         => 'informacion-de-ponentes',
                'category'     => 'Actualidad',
                'summary'      => 'La sección de ponentes fue actualizada con información académica, ponencias y perfiles.',
                'content'      => 'Conoce los perfiles académicos y títulos de conferencias magistrales de los 7 ponentes nacionales e internacionales invitados de México, Ecuador y Colombia.',
                'image_path'   => '/images/umariana.jpg',
                'external_url' => null,
                'is_featured'  => false,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($news as $post) {
            NewsPost::create($post);
        }

        // 6. Comité Organizador y Científico público (RF-09)
        $organizers = [
            [
                'name'            => 'Decanatura · Facultad de Ingeniería',
                'role'            => 'Dirección General del Congreso',
                'committee_type'  => 'directivo',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Facultad de Ingeniería',
                'email'           => 'cici@umariana.edu.co',
                'sort_order'      => 1,
                'is_active'       => true,
            ],
            [
                'name'            => 'Liderazgo del Comité Científico · Ingeniería Civil',
                'role'            => 'Coordinación de Arbitraje y Evaluación de Resúmenes',
                'committee_type'  => 'cientifico',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería Civil',
                'email'           => 'cici@umariana.edu.co',
                'sort_order'      => 2,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería Ambiental',
                'role'            => 'Coordinación Eje de Gestión Ambiental y Cambio Climático',
                'committee_type'  => 'cientifico',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería Ambiental',
                'email'           => null,
                'sort_order'      => 3,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería de Sistemas',
                'role'            => 'Coordinación Eje de IA Aplicada, Innovación Digital y Plataforma Web',
                'committee_type'  => 'organizador',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería de Sistemas',
                'email'           => null,
                'sort_order'      => 4,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería Mecatrónica',
                'role'            => 'Coordinación Eje de Energías Renovables, Automatización y Robótica',
                'committee_type'  => 'organizador',
                'institution'     => 'Universidad Mariana',
                'program_or_unit' => 'Programa de Ingeniería Mecatrónica',
                'email'           => null,
                'sort_order'      => 5,
                'is_active'       => true,
            ],
            [
                'name'            => 'Comité Curricular y de Investigaciones · Ingeniería de Procesos',
                'role'            => 'Coordinación Eje de Biotecnología, Bioprocesos y Economía Circular',
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

        // 8. Aliados e instituciones participantes (RF-11)
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
                'description' => 'Participación de conferencistas magistrales e investigadores del grupo GIISE.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'name'        => 'CINVESTAV · Centro de Investigación y de Estudios Avanzados',
                'category'    => 'aliado_academico',
                'country'     => 'México',
                'logo_path'   => null,
                'website_url' => 'https://www.cinvestav.mx',
                'description' => 'Participación académica internacional en el eje de Biotecnología y Bioprocesos.',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'name'        => 'Universidad Autónoma de Ciudad Juárez (UACJ)',
                'category'    => 'aliado_academico',
                'country'     => 'México',
                'logo_path'   => null,
                'website_url' => 'https://www.uacj.mx',
                'description' => 'Participación magistral en Industria 4.0, optimización y toma de decisiones multicriterio.',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'name'        => 'Pontificia Universidad Javeriana',
                'category'    => 'aliado_academico',
                'country'     => 'Colombia',
                'logo_path'   => null,
                'website_url' => 'https://www.javeriana.edu.co',
                'description' => 'Participación magistral del Departamento de Ingeniería Civil en optimización estructural.',
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'name'        => 'Universidad Militar Nueva Granada (UMNG)',
                'category'    => 'aliado_academico',
                'country'     => 'Colombia',
                'logo_path'   => null,
                'website_url' => 'https://www.umng.edu.co',
                'description' => 'Participación magistral en gestión ambiental y tratamiento avanzado de aguas.',
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
                'answer'       => 'Debes descargar primero el Formato de resumen disponible en la sección de Documentos, diligenciarlo según los lineamientos del eje temático correspondiente y enviarlo a través del formulario oficial de recepción de resúmenes.',
                'category'     => 'resumenes',
                'sort_order'   => 1,
                'is_published' => true,
            ],
            [
                'question'     => '¿Cómo consulto si mi resumen fue aprobado para Ponencia Oral o Póster?',
                'answer'       => 'No necesitas crear una cuenta ni recordar contraseña. Ingresa a la opción "Consultar con mi cédula" (/estado-resumen) del menú superior y escribe tu número de documento de identidad. El sistema te mostrará el dictamen del Comité Científico, las observaciones y la modalidad asignada.',
                'category'     => 'resumenes',
                'sort_order'   => 2,
                'is_published' => true,
            ],
            [
                'question'     => '¿Cuántos autores se permiten por proyecto registrado?',
                'answer'       => 'De acuerdo con los lineamientos oficiales del congreso, el pago de ponencia se realiza por proyecto y permite un máximo de 7 personas registradas por proyecto, incluido el ponente.',
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
