<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CongressDocument extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'category',
        'description',
        'external_url',
        'file_path',
        'version',
        'status',
        'sort_order',
    ];

    public function isAvailable(): bool
    {
        return $this->status === 'disponible' && ($this->file_path || $this->external_url);
    }

    public function downloadUrl(): ?string
    {
        if ($this->file_path) {
            return Storage::disk('public')->url($this->file_path);
        }

        if ($this->external_url) {
            return $this->external_url;
        }

        return null;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'disponible'   => 'Disponible',
            'proximamente' => 'Próximamente',
            'oculto'       => 'Oculto',
            default        => ucfirst($this->status),
        };
    }
}
