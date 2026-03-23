<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    public function definition(): array
    {
        $types = ['api', 'parser', 'manual', 'form', 'marketplace', 'social'];

        return [
            'name' => fake()->company(),
            'type' => fake()->randomElement($types),
            'base_url' => fake()->url(),
            'settings' => [
                'sync_interval' => fake()->randomElement(['hourly', 'daily', 'weekly']),
                'enabled' => true,
            ],
            'is_active' => true,
        ];
    }
}