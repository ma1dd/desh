<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyticalSessionFactory extends Factory
{
    public function definition(): array
    {
        $started = fake()->dateTimeBetween('-3 months', 'now');
        $finished = (clone $started)->modify('+' . rand(5, 120) . ' minutes');

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'title' => 'Анализ: ' . fake()->sentence(3),
            'description' => fake()->sentence(),
            'parameters' => [
                'period' => fake()->randomElement(['7_days', '30_days', '90_days']),
                'sentiment' => fake()->randomElement(['all', 'positive', 'neutral', 'negative']),
                'region' => fake()->city(),
            ],
            'results' => [
                'total_reviews' => fake()->numberBetween(10, 1000),
                'avg_rating' => fake()->randomFloat(1, 1, 5),
                'positive_percent' => fake()->numberBetween(10, 95),
            ],
            'started_at' => $started,
            'finished_at' => $finished,
        ];
    }
}