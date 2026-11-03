<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sponsor extends Model
{
    protected $fillable = [
        'name',
        'category',
        'country',
        'logo_path',
        'website_url',
        'description',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function logoUrl(): ?string
    {
        if (! $this->logo_path) {
            return null;
        }
        if (str_starts_with($this->logo_path, 'http://') || str_starts_with($this->logo_path, 'https://') || str_starts_with($this->logo_path, '/')) {
            return $this->logo_path;
        }
        if (str_starts_with($this->logo_path, 'images/')) {
            return asset($this->logo_path);
        }
        return Storage::disk('public')->url($this->logo_path);
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'organizador'         => 'Institución Organizadora',
            'aliado_academico'    => 'Universidad / Red Académica Aliada',
            'patrocinador'        => 'Patrocinador Oficial',
            'apoyo_institucional' => 'Apoyo Institucional y Científico',
            default               => ucfirst($this->category),
        };
    }
}
