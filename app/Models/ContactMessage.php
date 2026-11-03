<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'institution',
        'category',
        'subject',
        'message',
        'status',
        'admin_notes',
        'responded_by',
        'responded_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'nuevo'      => 'Nuevo',
            'en_proceso' => 'En gestión',
            'respondido' => 'Atendido',
            default      => ucfirst($this->status),
        };
    }
}
