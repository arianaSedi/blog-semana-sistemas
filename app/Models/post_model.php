<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class post_model extends Model
{
    protected $table = 'posts';

    public const STATUSES = [
        'borrador' => 'Borrador',
        'publicado' => 'Publicado',
    ];

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'activity_date',
        'excerpt', 'content', 'cover_image', 'status',
    ];

    protected function casts(): array
    {
        return ['activity_date' => 'date'];
    }

    // ---------- Relaciones ----------
    public function category(): BelongsTo
    {
        return $this->belongsTo(category_model::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Autor o autores (tabla post_user)
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id')->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(post_image_model::class, 'post_id');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(evidence_model::class, 'post_id');
    }

    // ---------- Solo publicadas---------
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'publicado');
    }

    public function isPublished(): bool
    {
        return $this->status === 'publicado';
    }

    // Resumen para el listado
    public function summary(int $length = 160): string
    {
        return $this->excerpt ?: Str::limit(strip_tags($this->content), $length);
    }

    // Genera una URL unica apartir del titulo
    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'entrada';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
