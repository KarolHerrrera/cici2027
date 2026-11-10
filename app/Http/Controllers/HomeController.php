<?php

namespace App\Http\Controllers;

use App\Models\CongressDocument;
use App\Models\CongressSetting;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\ImportantDate;
use App\Models\NewsPost;
use App\Models\OrganizerMember;
use App\Models\RegistrationFee;
use App\Models\ScheduleItem;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Models\ThematicAxis;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Página de inicio dinámica del CICI 2027 (con caché inteligente para alta concurrencia).
     */
    public function index(): View
    {
        Carbon::setLocale('es');

        $viewData = \Illuminate\Support\Facades\Cache::remember(
            \App\Support\PortalCache::HOME_CACHE_KEY,
            \App\Support\PortalCache::TTL_SECONDS,
            fn () => $this->buildHomeData()
        );

        return view('home', $viewData);
    }

    private function buildHomeData(): array
    {
        // Si la base de datos en producción/Render está recién migrada y vacía, poblarla automáticamente:
        try {
            if (Schema::hasTable('speakers') && Speaker::count() === 0) {
                (new \Database\Seeders\DatabaseSeeder())->run();
            }
        } catch (\Throwable) {
        }

        $fechas = ImportantDate::orderBy('date')->get()->map(fn ($d) => [
            'titulo'    => $d->title,
            'fecha'     => $this->fechaTexto($d),
            'destacada' => $d->is_featured,
        ])->all();

        $cierreResumenes = $fechas[1]['fecha'] ?? ($fechas[0]['fecha'] ?? '26 de febrero de 2027');

        $ejes = ThematicAxis::with('topics')->withCount('topics')->orderBy('sort_order')->get()->map(fn ($e) => [
            'titulo'      => $e->name,
            'lineas'      => $e->topics_count,
            'temas'       => $e->topics->pluck('name')->all(),
            'transversal' => $e->is_transversal,
        ])->all();

        $ponentes = Speaker::orderBy('sort_order')->get()->map(fn ($p) => [
            'nombre'      => $p->name,
            'foto'        => $p->photo,
            'foto_url'    => $p->photoUrl(),
            'cargo'       => $p->job_title,
            'area'        => $p->specialty,
            'institucion' => $p->institution,
            'ciudad'      => $p->city,
            'pais'        => $p->country,
            'bio'         => $p->bio,
            'charla'      => $p->talk_title,
        ])->all();

        // Tarifas dinámicas desde BD o fallback oficial
        $tarifas = [];
        if (Schema::hasTable('registration_fees') && RegistrationFee::where('is_active', true)->exists()) {
            $tarifas = RegistrationFee::where('is_active', true)->orderBy('sort_order')->get()->map(fn (RegistrationFee $f) => [
                'nombre'    => $f->profile,
                'detalle'   => $f->description,
                'valor'     => $f->price_amount,
                'destacada' => $f->price_amount === 250000,
                'incluye'   => $f->includes,
            ])->all();
        } else {
            $tarifas = [
                [
                    'nombre'    => 'Estudiantes y profesionales',
                    'detalle'   => 'Inscripción para asistentes estudiantes y profesionales.',
                    'valor'     => 50000,
                    'destacada' => false,
                ],
                [
                    'nombre'    => 'Ponente nacional · póster',
                    'detalle'   => 'Inscripción para ponentes nacionales con presentación en modalidad póster presencial.',
                    'valor'     => 250000,
                    'destacada' => true,
                ],
                [
                    'nombre'    => 'Ponente nacional · oral',
                    'detalle'   => 'Inscripción para ponentes nacionales con presentación oral, en modalidad presencial o virtual. Las ponencias virtuales están sujetas a cupos limitados.',
                    'valor'     => 350000,
                    'destacada' => false,
                ],
                [
                    'nombre'    => 'Ponente internacional',
                    'detalle'   => 'Inscripción para ponentes internacionales, en modalidad presencial o virtual. Las ponencias virtuales están sujetas a cupos limitados.',
                    'valor'     => 500000,
                    'destacada' => false,
                ],
            ];
        }

        // Documentos dinámicos desde BD o fallback oficial
        $documentos = [];
        if (Schema::hasTable('congress_documents') && CongressDocument::where('status', '!=', 'oculto')->exists()) {
            $documentos = CongressDocument::where('status', '!=', 'oculto')->orderBy('sort_order')->get()->map(function (CongressDocument $doc) {
                $url = $doc->isAvailable() ? $doc->downloadUrl() : null;
                if (! $url && $doc->slug === 'convocatoria') {
                    $url = CongressSetting::getValue('terms_url', config('cici.terms_url')) ?: null;
                }
                if (! $url && $doc->slug === 'plantilla-poster') {
                    $url = CongressSetting::getValue('poster_template_url', config('cici.poster_template_url')) ?: null;
                }
                if (! $url && $doc->slug === 'formato-resumen') {
                    $url = CongressSetting::getValue('abstract_template_url', config('cici.abstract_template_url')) ?: null;
                }

                return [
                    'titulo'      => $doc->title,
                    'tipo'        => ucfirst(str_replace('_', ' ', $doc->category)),
                    'descripcion' => $doc->description,
                    'url'         => $url,
                    'externo'     => $url ? (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) : false,
                ];
            })->all();
        } else {
            $documentos = [
                [
                    'titulo'  => 'Material de difusión (banners oficiales)',
                    'tipo'    => 'Difusión',
                    'url'     => route('documents.show', 'material-difusion'),
                    'externo' => false,
                ],
                [
                    'titulo'  => 'Convocatoria / Términos de referencia',
                    'tipo'    => 'Convocatoria',
                    'url'     => config('cici.terms_url') ?: null,
                    'externo' => true,
                ],
                [
                    'titulo'  => 'Formato de resumen',
                    'tipo'    => 'Formato',
                    'url'     => config('cici.abstract_template_url') ?: null,
                    'externo' => true,
                ],
                [
                    'titulo'  => 'Plantilla de póster',
                    'tipo'    => 'Plantilla',
                    'url'     => config('cici.poster_template_url') ?: null,
                    'externo' => true,
                ],
            ];
        }

        // Noticias dinámicas desde BD o fallback oficial
        $noticias = [];
        if (Schema::hasTable('news_posts') && NewsPost::where('is_published', true)->exists()) {
            $noticias = NewsPost::where('is_published', true)->orderByDesc('published_at')->orderByDesc('id')->get()->map(fn (NewsPost $n) => [
                'etiqueta' => $n->category,
                'titulo'   => $n->title,
                'resumen'  => $n->summary,
                'contenido'=> $n->content,
                'imagen'   => $n->imageUrl(),
                'url'      => $n->external_url,
                'fecha'    => $n->published_at?->translatedFormat('j \d\e F \d\e Y'),
            ])->all();
        } else {
            $noticias = [
                [
                    'etiqueta' => 'CICI 2027',
                    'titulo'   => 'Convocatoria de resúmenes',
                    'resumen'  => 'La recepción de resúmenes está programada para iniciar el 1 de octubre de 2026.',
                ],
                [
                    'etiqueta' => 'CICI 2027',
                    'titulo'   => 'Ejes temáticos',
                    'resumen'  => 'Consulta las áreas y subejes definidos para la cuarta edición del Congreso.',
                ],
                [
                    'etiqueta' => 'Actualidad',
                    'titulo'   => 'Información de ponentes',
                    'resumen'  => 'La sección de ponentes fue actualizada con información académica, ponencias y perfiles.',
                ],
            ];
        }

        // Programa académico agrupado por jornada (RF-04)
        $programaPorDia = Schema::hasTable('schedule_items')
            ? ScheduleItem::with('axis')->where('is_published', true)->orderBy('event_date')->orderBy('sort_order')->orderBy('start_time')->get()->groupBy('day_label')
            : collect();

        // Comité organizador y científico público (RF-09)
        $comitePublico = Schema::hasTable('organizer_members')
            ? OrganizerMember::where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        // Galería e histórico de ediciones anteriores (RF-10)
        $galeria = Schema::hasTable('gallery_items')
            ? GalleryItem::where('is_published', true)->orderBy('sort_order')->get()
            : collect();

        // Aliados y patrocinadores (RF-11)
        $aliados = Schema::hasTable('sponsors')
            ? Sponsor::where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        // Preguntas frecuentes (RF-12)
        $faqs = Schema::hasTable('faqs')
            ? Faq::where('is_published', true)->orderBy('sort_order')->get()
            : collect();

        $abstractFormUrl = CongressSetting::getValue('abstract_form_url', config('cici.abstract_form_url'));
        $registrationUrl = CongressSetting::getValue('registration_url', config('cici.registration_url'));
        $contactEmail    = CongressSetting::getValue('contact_email', config('cici.contact_email'));
        $contactPhone    = CongressSetting::getValue('contact_phone', config('cici.contact_phone'));

        return [
            'evento' => [
                'nombre'           => CongressSetting::getValue('event_name', 'IV Congreso Internacional de Ciencia e Ingeniería'),
                'anio'             => CongressSetting::getValue('event_year', '2027'),
                'edicion'          => CongressSetting::getValue('event_edition_badge', 'IV edición · 2027'),
                'fechas'           => CongressSetting::getValue('event_dates_label', '14, 15 y 16 de abril de 2027'),
                'modalidad'        => CongressSetting::getValue('event_modality', 'Modalidad mixta · Presencial y virtual'),
                'inicio'           => CongressSetting::getValue('event_start_iso', '2027-04-14T00:00:00-05:00'),
                'logo_blanco'      => CongressSetting::getValue('event_logo_white', ''),
                'logo_sigla'       => CongressSetting::getValue('event_logo_sigla', '/images/cici-sigla.png'),
                'cierre_resumenes' => $cierreResumenes,
                'lema'             => 'Un espacio para conectar conocimiento, investigación, innovación y territorio.',
            ],

            'pilares' => [
                [
                    'titulo'      => 'Conocimiento',
                    'lema'        => 'Ideas que conectan',
                    'descripcion' => 'Un espacio para compartir conocimiento, investigación y experiencias entre la comunidad académica y profesional.',
                ],
                [
                    'titulo'      => 'Innovación',
                    'lema'        => 'Soluciones para el futuro',
                    'descripcion' => 'La ingeniería como punto de encuentro para nuevas tecnologías, procesos y soluciones frente a los retos actuales.',
                ],
                [
                    'titulo'      => 'Territorio',
                    'lema'        => 'Conexiones que trascienden',
                    'descripcion' => 'Una mirada multidisciplinaria que articula ciencia, ingeniería, sociedad, sostenibilidad y desarrollo territorial.',
                ],
            ],

            'fechas'   => $fechas,
            'ejes'     => $ejes,
            'ponentes' => $ponentes,

            'jornadas' => [
                ['dia' => 'Jornada 1 · Miércoles', 'numero' => '14', 'mes' => 'abril'],
                ['dia' => 'Jornada 2 · Jueves',    'numero' => '15', 'mes' => 'abril'],
                ['dia' => 'Jornada 3 · Viernes',   'numero' => '16', 'mes' => 'abril'],
            ],

            'tarifas'        => $tarifas,
            'documentos'     => $documentos,
            'noticias'       => $noticias,
            'programaPorDia' => $programaPorDia,
            'comitePublico'  => $comitePublico,
            'galeria'        => $galeria,
            'aliados'        => $aliados,
            'faqs'           => $faqs,

            'enlaces' => [
                'resumenes'         => $abstractFormUrl ?: route('submissions.public.create'),
                'resumenes_externo' => (bool) $abstractFormUrl,
                'inscripciones'     => $registrationUrl,
                'terminos'          => CongressSetting::getValue('terms_url', config('cici.terms_url')) ?: asset('documentos/terminos-de-referencia-cici-2027.pdf'),
                'whatsapp'          => config('cici.whatsapp_url'),
            ],

            'contacto' => [
                'correo'    => $contactEmail,
                'telefono'  => $contactPhone,
                'direccion' => CongressSetting::getValue('contact_address', 'Calle 18 No. 34-104 · Campus Universitario Alvernia, San Juan de Pasto - Nariño, Colombia'),
                'web'       => 'cici.umariana.edu.co',
            ],
        ];
    }

    /** Convierte una fecha (o rango) en texto: "14 al 16 de abril de 2027". */
    private function fechaTexto(ImportantDate $d): string
    {
        if (! $d->end_date) {
            return $d->date->translatedFormat('j \d\e F \d\e Y');
        }

        if ($d->date->format('Y-m') === $d->end_date->format('Y-m')) {
            return $d->date->format('j').' al '.$d->end_date->translatedFormat('j \d\e F \d\e Y');
        }

        return $d->date->translatedFormat('j \d\e F').' al '.$d->end_date->translatedFormat('j \d\e F \d\e Y');
    }
}
