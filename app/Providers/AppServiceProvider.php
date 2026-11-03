<?php

namespace App\Providers;

use App\Models\AxisTopic;
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
use App\Support\PortalCache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Optimización de concurrencia cuando se ejecuta sobre SQLite en archivo.
        try {
            if (config('database.default') === 'sqlite' && config('database.connections.sqlite.database') !== ':memory:') {
                DB::statement('PRAGMA journal_mode=WAL;');
                DB::statement('PRAGMA synchronous=NORMAL;');
                DB::statement('PRAGMA busy_timeout=5000;');
            }
        } catch (\Throwable) {
            // Ignorar si aún no existe el archivo de base de datos.
        }

        // Invalidación automática de la caché del portal cuando el Administrador edita cualquier módulo.
        $publicModels = [
            Speaker::class,
            ThematicAxis::class,
            AxisTopic::class,
            ImportantDate::class,
            RegistrationFee::class,
            CongressDocument::class,
            NewsPost::class,
            ScheduleItem::class,
            OrganizerMember::class,
            GalleryItem::class,
            Sponsor::class,
            Faq::class,
            CongressSetting::class,
        ];

        foreach ($publicModels as $modelClass) {
            $modelClass::saved(static fn () => PortalCache::flush());
            $modelClass::deleted(static fn () => PortalCache::flush());
        }
    }
}
