<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_committee',
        'is_committee_leader',
        'specialty',
        'thematic_axis_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'is_admin'            => 'boolean',
            'is_committee'        => 'boolean',
            'is_committee_leader' => 'boolean',
        ];
    }

    public function axis(): BelongsTo
    {
        return $this->belongsTo(ThematicAxis::class, 'thematic_axis_id');
    }

    /** Puede revisar resúmenes: miembros del comité científico y administradores. */
    public function canReviewSubmissions(): bool
    {
        return (bool) ($this->is_admin || $this->is_committee || $this->is_committee_leader);
    }

    public function roleLabel(): string
    {
        if ($this->is_admin) {
            return 'Administrador';
        }
        if ($this->is_committee_leader) {
            return 'Líder del Comité Científico';
        }
        if ($this->is_committee) {
            return 'Comité Científico';
        }

        return 'Usuario';
    }
}
