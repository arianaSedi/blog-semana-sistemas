<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class category_model extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'slug', 'description'];

    // Una categoria tiene muchas entradas
    public function posts(): HasMany
    {
        return $this->hasMany(post_model::class, 'category_id');
    }
}