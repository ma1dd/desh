<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentimentAnalysis extends Model
{
    protected $fillable = [
        'review_id',
        'score',
        'label',
        'confidence',
        'analyzed_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'confidence' => 'decimal:2',
        'analyzed_at' => 'datetime',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}