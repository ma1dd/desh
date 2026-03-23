<?php

namespace Database\Seeders;

use App\Models\AnalyticalSession;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\SentimentAnalysis;
use App\Models\Source;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class JsonDataSeeder extends Seeder
{
    public function run(): void
    {
        $basePath = base_path('data');

        $this->seedUsers($basePath . DIRECTORY_SEPARATOR . 'users.json');
        $this->seedProductsAndRelated($basePath . DIRECTORY_SEPARATOR . 'products.json');
        $this->seedSessions($basePath . DIRECTORY_SEPARATOR . 'sessions.json');
        $this->seedDemoReviews();
    }

    protected function seedUsers(string $path): void
    {
        if (! File::exists($path)) {
            return;
        }

        $raw = File::get($path);
        $items = json_decode($raw, true) ?? [];

        foreach ($items as $item) {
            $roleName = match (mb_strtolower($item['роль'])) {
                'администратор' => 'admin',
                'аналитик' => 'analyst',
                'руководитель отдела' => 'leader',
                'менеджер по продукту', 'маркетолог' => 'manager',
                default => 'analyst',
            };

            $role = \App\Models\Role::firstOrCreate(
                ['name' => $roleName],
                ['title' => match ($roleName) {
                    'admin' => 'Администратор',
                    'analyst' => 'Аналитик',
                    'leader' => 'Руководитель',
                    'manager' => 'Менеджер / Сотрудник',
                    default => ucfirst($roleName),
                }]
            );

            // Важно: сохраняем те же ID, что в users.json, чтобы sessions.json (userId) совпадал с users.id
            User::updateOrCreate(
                ['id' => $item['id']],
                [
                    'role_id' => $role->id,
                    'name' => trim($item['фамилия'] . ' ' . $item['имя'] . ' ' . $item['отчество']),
                    'login' => $item['логин'],
                    'email' => $item['email'],
                    'phone' => $item['телефон'] ?? null,
                    'avatar' => $item['аватар'] ?? null,
                    'password' => Hash::make($item['пароль']),
                    'is_active' => true,
                    'last_seen_at' => now(),
                ]
            );
        }
    }

    protected function seedProductsAndRelated(string $path): void
    {
        if (! File::exists($path)) {
            return;
        }

        $raw = File::get($path);
        $items = json_decode($raw, true) ?? [];

        foreach ($items as $item) {
            // Категории
            $parentCategory = null;
            if (! empty($item['категория']['родительская_категория'])) {
                $parentCategory = Category::firstOrCreate(
                    ['name' => $item['категория']['родительская_категория']['название']],
                    [
                        'slug' => Str::slug($item['категория']['родительская_категория']['название'] . '-' . uniqid()),
                        'description' => null,
                        'parent_id' => null,
                    ]
                );
            }

            $category = Category::firstOrCreate(
                ['name' => $item['категория']['название']],
                [
                    'slug' => Str::slug($item['категория']['название'] . '-' . uniqid()),
                    'description' => null,
                    'parent_id' => $parentCategory?->id,
                ]
            );

            // Источники
            $sourceIds = [];
            foreach ($item['источники_продаж'] ?? [] as $sourceData) {
                // Сохраняем ID источников из products.json (там они фиксированы: 1,2,3...)
                $source = Source::updateOrCreate(
                    ['id' => $sourceData['id']],
                    [
                        'name' => $sourceData['название'],
                        'type' => $sourceData['тип'] ?? 'unknown',
                        'base_url' => $sourceData['api_адрес'] ?? null,
                        'settings' => [],
                        'is_active' => true,
                    ]
                );

                $sourceIds[] = $source->id;
            }

            // Топ-темы
            $topicIds = [];
            foreach ($item['статистика_отзывов']['топ_темы'] ?? [] as $topicData) {
                $topic = Topic::firstOrCreate(
                    ['name' => $topicData['название']],
                    [
                        'slug' => Str::slug($topicData['название'] . '-' . uniqid()),
                        'keywords' => null,
                    ]
                );

                $topicIds[] = $topic->id;
            }

            // Товар
            // Важно: сохраняем те же ID, что в products.json, чтобы sessions.json (productIds) совпадал с products.id
            $product = Product::updateOrCreate(
                ['id' => $item['id']],
                [
                    'category_id' => $category->id,
                    'name' => $item['название'],
                    'slug' => Str::slug($item['название'] . '-' . $item['id']),
                    'sku' => 'SKU-' . $item['id'],
                    'description' => $item['описание'] ?? null,
                    'price' => $item['цена'] ?? null,
                    'is_active' => true,
                ]
            );

            // Сохраняем подсказки для генерации демо-отзывов в metadata товара (без новой таблицы).
            $product->update([
                'description' => $product->description,
            ]);
        }
    }

    protected function seedSessions(string $path): void
    {
        if (! File::exists($path)) {
            return;
        }

        $raw = File::get($path);
        $items = json_decode($raw, true) ?? [];

        foreach ($items as $item) {
            // userId в sessions.json ссылается на users.json.id → users.id
            $user = User::find($item['userId']);

            if (! $user) {
                continue;
            }

            $parameters = [
                'period' => $item['period'] ?? null,
                'productIds' => $item['productIds'] ?? [],
                'comment' => $item['comment'] ?? null,
                'thoughts' => $item['thoughts'] ?? null,
                'notes' => $item['notes'] ?? null,
            ];

            $session = AnalyticalSession::updateOrCreate(
                ['id' => $item['id']],
                [
                    'user_id' => $user->id,
                    'name' => $item['title'],
                    'title' => $item['title'],
                    'description' => $item['description'] ?? null,
                    'parameters' => $parameters,
                    'started_at' => $item['createdAt'] ?? null,
                    'finished_at' => $item['updatedAt'] ?? null,
                    'created_at' => $item['createdAt'] ?? now(),
                    'updated_at' => $item['updatedAt'] ?? now(),
                ]
            );

            // Привязка товаров к сессии если есть связь (например, через pivot),
            // здесь можно будет донастроить, когда появится таблица.
        }
    }

    /**
     * Генерирует демо-отзывы/тональность/темы на основе импортированных сущностей.
     * Делает это детерминированно: уникальность задаётся комбинацией source_id + external_id.
     */
    protected function seedDemoReviews(): void
    {
        $products = Product::query()->where('is_active', true)->get();
        $sources = Source::query()->where('is_active', true)->get();
        $topics = Topic::query()->get();

        if ($products->isEmpty() || $sources->isEmpty()) {
            return;
        }

        $regions = ['Москва', 'Санкт-Петербург', 'Казань', 'Екатеринбург', 'Новосибирск'];
        $statuses = ['approved', 'new', 'moderation'];

        foreach ($products as $product) {
            foreach ($sources as $source) {
                // 3 отзыва на связку товар-источник
                for ($i = 1; $i <= 3; $i++) {
                    $externalId = "seed-{$product->id}-{$source->id}-{$i}";

                    $rating = (($product->id + $source->id + $i) % 5) + 1; // 1..5

                    $label = 'neutral';
                    if ($rating >= 4) {
                        $label = 'positive';
                    } elseif ($rating <= 2) {
                        $label = 'negative';
                    }

                    $score = match ($label) {
                        'positive' => 0.65,
                        'negative' => -0.65,
                        default => 0.05,
                    };

                    $review = Review::updateOrCreate(
                        ['source_id' => $source->id, 'external_id' => $externalId],
                        [
                            'product_id' => $product->id,
                            'author_name' => 'Покупатель',
                            'text' => $this->demoReviewText($label, $product->name),
                            'rating' => $rating,
                            'status' => $statuses[($product->id + $source->id + $i) % count($statuses)],
                            'region' => $regions[($product->id + $source->id + $i) % count($regions)],
                            // Дата публикации внутри диапазона 2025-07-01 — 2025-10-31,
                            // чтобы попадать в периоды сессий из sessions.json
                            'published_at' => fake()->dateTimeBetween('2025-07-01', '2025-10-31'),
                            'metadata' => [
                                'seed' => true,
                                'source_name' => $source->name,
                            ],
                        ]
                    );

                    SentimentAnalysis::updateOrCreate(
                        ['review_id' => $review->id],
                        [
                            'score' => $score,
                            'label' => $label,
                            'confidence' => 0.85,
                            'analyzed_at' => $review->published_at ?? now(),
                        ]
                    );

                    if ($topics->isNotEmpty()) {
                        $picked = $topics->shuffle()->take(3);
                        $syncData = [];
                        foreach ($picked as $t) {
                            $syncData[$t->id] = ['relevance' => round(0.3 + (($t->id % 70) / 100), 2)];
                        }
                        $review->topics()->syncWithoutDetaching($syncData);
                    }
                }
            }
        }
    }

    protected function demoReviewText(string $label, string $productName): string
    {
        return match ($label) {
            'positive' => "Отличный товар: {$productName}. Качество понравилось, доставка быстрая, упаковка целая.",
            'negative' => "Разочарован товаром: {$productName}. Были проблемы с доставкой/упаковкой, ожидал лучшее качество.",
            default => "Нормальный товар: {$productName}. В целом устраивает, но есть мелкие нюансы. Доставка стандартная.",
        };
    }
}

