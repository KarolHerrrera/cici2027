<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class PortalCache
{
    public const HOME_CACHE_KEY     = 'cici_portal_home_data_v1';
    public const SETTINGS_CACHE_KEY = 'congress_settings_map';
    public const TTL_SECONDS        = 600; // 10 minutos

    /**
     * Limpia inmediatamente toda la caché del portal público tras cualquier cambio administrativo.
     */
    public static function flush(): void
    {
        Cache::forget(self::HOME_CACHE_KEY);
        Cache::forget(self::SETTINGS_CACHE_KEY);
    }
}
