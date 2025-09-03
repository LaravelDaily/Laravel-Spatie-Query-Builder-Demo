<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'status_id');
    }
}
