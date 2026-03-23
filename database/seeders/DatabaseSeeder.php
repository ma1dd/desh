<?php

namespace Database\Seeders;

use App\Models\AnalyticalSession;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Role;
use App\Models\SentimentAnalysis;
use App\Models\Source;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(JsonDataSeeder::class);

        // Остальное наполнение сейчас идёт из JSON, фабрики можно добавить при необходимости.
    }
}