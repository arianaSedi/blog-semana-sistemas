<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Entradas que se registro
    public function createdPosts(): HasMany
    {
        return $this->hasMany(post_model::class, 'user_id');
    }

    // Entradas donde figura como autora
    public function authoredPosts(): BelongsToMany
    {
        return $this->belongsToMany(post_model::class, 'post_user', 'user_id', 'post_id')->withTimestamps();
    }

    //evidencias de asistencia
    public function evidences(): HasMany
    {
        return $this->hasMany(evidence_model::class, 'user_id');
    }
}