<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'keywords',
    ];

    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'review_topic')
            ->withPivot('relevance')
            ->withTimestamps();
    }
}