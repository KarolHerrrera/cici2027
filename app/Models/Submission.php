<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    public const STATUS_PENDING      = 'pending';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED     = 'approved';
    public const STATUS_REJECTED     = 'rejected';

    public const MODALITY_ORAL   = 'oral';
    public const MODALITY_POSTER = 'poster';

    protected $fillable = [
        'tracking_code',
        'document_type',
        'document',
        'name',
        'email',
        'phone',
        'institution',
        'city',
        'country',
        'coauthors',
        'title',
        'thematic_axis_id',
        'axis_topic_id',
        'requested_modality',
        'abstract_text',
        'keywords',
        'file_path',
        'file_original_name',
        'file_url',
        'status',
        'modality',
        'score',
        'review_comment',
        'internal_notes',
        'assigned_to',
        'reviewed_by',
        'reviewed_at',
        'notified_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'notified_at' => 'datetime',
        'score'       => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Submission $submission) {
            if (! $submission->document) {
                $submission->document = '';
            } else {
                $submission->document = self::normalizeDocument($submission->document);
            }

            if (empty($submission->tracking_code)) {
                $next = (int) static::max('id') + 1;
                do {
                    $code = sprintf('CICI-2027-%04d', $next);
                    $next++;
                } while (static::where('tracking_code', $code)->exists());

                $submission->tracking_code = $code;
            }
        });
    }

    public static function normalizeDocument(string $document): string
    {
        return mb_strtoupper((string) preg_replace('/[\s.\-]/', '', trim($document)));
    }

    public function axis(): BelongsTo
    {
        return $this->belongsTo(ThematicAxis::class, 'thematic_axis_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(AxisTopic::class, 'axis_topic_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED     => 'Aprobado',
            self::STATUS_REJECTED     => 'No aprobado',
            self::STATUS_UNDER_REVIEW => 'En evaluación por el comité',
            default                   => 'Recibido · Pendiente de evaluación',
        };
    }

    public function statusShortLabel(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED     => 'Aprobado',
            self::STATUS_REJECTED     => 'No aprobado',
            self::STATUS_UNDER_REVIEW => 'En evaluación',
            default                   => 'Pendiente',
        };
    }

    public function modalityLabel(): ?string
    {
        return match ($this->modality) {
            self::MODALITY_ORAL   => 'Ponencia oral',
            self::MODALITY_POSTER => 'Póster',
            default               => null,
        };
    }

    public function requestedModalityLabel(): string
    {
        return match ($this->requested_modality) {
            self::MODALITY_POSTER => 'Póster',
            default               => 'Ponencia oral',
        };
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPoster(): bool
    {
        return $this->status === self::STATUS_APPROVED && $this->modality === self::MODALITY_POSTER;
    }

    public function isOral(): bool
    {
        return $this->status === self::STATUS_APPROVED && $this->modality === self::MODALITY_ORAL;
    }

    /** Oculta parcialmente el correo en consultas públicas por cédula. */
    public function maskedEmail(): string
    {
        if (! str_contains($this->email, '@')) {
            return $this->email;
        }
        [$local, $domain] = explode('@', $this->email, 2);
        $visible = mb_substr($local, 0, min(2, mb_strlen($local)));

        return $visible.'***@'.$domain;
    }
}
