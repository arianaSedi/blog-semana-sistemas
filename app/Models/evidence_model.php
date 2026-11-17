<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class evidence_model extends Model
{
    protected $table = 'evidences';

    public const TYPES = [
        'fotografia' => 'Fotografía',
        'constancia' => 'Constancia',
        'registro' => 'Registro de asistencia',
        'otro' => 'Otra prueba autorizada',
    ];

    protected $fillable = ['post_id', 'user_id', 'type', 'file', 'description', 'evidence_date'];

    protected function casts(): array
    {
        return ['evidence_date' => 'date'];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(post_model::class, 'post_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isImage(): bool
    {
        return $this->file && preg_match('/\.(jpe?g|png|webp|gif)$/i', $this->file);
    }
}