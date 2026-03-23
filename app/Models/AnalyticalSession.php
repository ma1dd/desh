<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticalSession extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'title',
        'description',
        'parameters',
        'results',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'parameters' => 'array',
        'results' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
