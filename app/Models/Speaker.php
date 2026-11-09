<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Speaker extends Model
{
    protected $fillable = [
        'thematic_axis_id', 'name', 'photo', 'job_title', 'specialty',
        'institution', 'city', 'country', 'bio', 'talk_title', 'sort_order',
    ];

    public function axis(): BelongsTo
    {
        return $this->belongsTo(ThematicAxis::class, 'thematic_axis_id');
    }

    /**
     * Resuelve la URL de la fotografía del ponente soportando:
     * 1. Enlaces directos en la nube (Firebase Storage, Google Cloud, AWS S3, Cloudflare R2).
     * 2. Imágenes oficiales incluidas en public/images/ponentes/ (no dependen de storage:link).
     * 3. Disco configurado en Laravel Storage (s3 o public) para nuevos ponentes subidos desde el panel.
     */
    public function photoUrl(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        if (str_starts_with($this->photo, 'images/') || str_starts_with($this->photo, '/images/')) {
            return asset(ltrim($this->photo, '/'));
        }

        $disk = config('cici.uploads_disk', 'public');
        if ($disk === 's3') {
            return Storage::disk('s3')->url($this->photo);
        }

        $fallbackFile = 'images/ponentes/' . basename($this->photo);
        if (file_exists(public_path($fallbackFile))) {
            return asset($fallbackFile);
        }

        return asset('storage/' . ltrim($this->photo, '/'));
    }
}
