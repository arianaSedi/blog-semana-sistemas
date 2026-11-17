<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class post_image_model extends Model
{
    protected $table = 'post_images';

    protected $fillable = ['post_id', 'path', 'caption'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(post_model::class, 'post_id');
    }
}