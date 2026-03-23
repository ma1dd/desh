<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['new', 'approved', 'rejected', 'moderation'];

        return [
            'source_id' => Source::inRandomOrder()->value('id'),
            'product_id' => Product::inRandomOrder()->value('id'),
            'author_name' => fake()->name(),
            'external_id' => (string) fake()->unique()->numberBetween(100000, 999999),
            'text' => fake()->realText(300),
            'rating' => fake()->numberBetween(1, 5),
            'status' => fake()->randomElement($statuses),
            'rejection_reason' => null,
            'region' => fake()->city(),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'metadata' => [
                'language' => 'ru',
                'device' => fake()->randomElement(['desktop', 'mobile', 'tablet']),
            ],
        ];
    }
}