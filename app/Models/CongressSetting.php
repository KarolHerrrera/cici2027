<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class CongressSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'label',
        'group',
    ];

    /**
     * Obtiene un valor de configuración desde la base de datos o usa el fallback de config/cici.php.
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        try {
            if (! Schema::hasTable('congress_settings')) {
                return $default;
            }

            $map = Cache::remember('congress_settings_map', 300, function () {
                return static::query()->pluck('value', 'key')->toArray();
            });

            if (array_key_exists($key, $map) && $map[$key] !== null && trim((string) $map[$key]) !== '') {
                return $map[$key];
            }
        } catch (\Throwable) {
            return $default;
        }

        return $default;
    }

    public static function clearCache(): void
    {
        \App\Support\PortalCache::flush();
    }
}
