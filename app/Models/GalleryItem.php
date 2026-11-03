<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = [
        'edition',
        'year',
        'title',
        'description',
        'image_path',
        'participants_count',
        'papers_count',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'year'         => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function imageUrl(): string
    {
        if ($this->image_path) {
            if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://') || str_starts_with($this->image_path, '/')) {
                return $this->image_path;
            }
            if (str_starts_with($this->image_path, 'images/')) {
                return asset($this->image_path);
            }
            return Storage::disk('public')->url($this->image_path);
        }

        return asset('images/umariana.jpg');
    }
}
