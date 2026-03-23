<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = ucfirst(fake()->words(3, true));

        return [
            'category_id' => Category::inRandomOrder()->value('id'),
            'name' => $name,
            'slug' => Str::slug($name . '-' . fake()->unique()->numberBetween(1, 9999)),
            'sku' => strtoupper(fake()->bothify('SAN-###??')),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5000, 150000),
            'is_active' => true,
        ];
    }
}