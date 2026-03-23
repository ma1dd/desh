<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TopicFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name . '-' . fake()->unique()->numberBetween(1, 9999)),
            'keywords' => implode(', ', fake()->words(5)),
        ];
    }
}