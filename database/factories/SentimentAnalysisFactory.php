<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class SentimentAnalysisFactory extends Factory
{
    public function definition(): array
    {
        $score = fake()->randomFloat(2, -1, 1);

        $label = 'neutral';
        if ($score > 0.2) {
            $label = 'positive';
        } elseif ($score < -0.2) {
            $label = 'negative';
        }

        return [
            'review_id' => Review::inRandomOrder()->value('id'),
            'score' => $score,
            'label' => $label,
            'confidence' => fake()->randomFloat(2, 0.5, 1),
            'analyzed_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}