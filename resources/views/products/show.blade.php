@extends('layouts.app')

@section('title', $product->name . ' — SanStar Аналитика')

@section('content')
    <section class="top-grid">
        <article class="card stats-card">
            <div class="card__header">Карточка товара</div>
            <div class="card__body">
                <div class="stats-list">
                    <div class="stats-row">
                        <span>Название</span>
                        <strong>{{ $product->name }}</strong>
                    </div>
                    <div class="stats-row">
                        <span>Категория</span>
                        <strong>{{ $product->category->name ?? '—' }}</strong>
                    </div>
                    <div class="stats-row">
                        <span>Артикул</span>
                        <strong>{{ $product->sku ?? '—' }}</strong>
                    </div>
                    <div class="stats-row">
                        <span>Цена</span>
                        <strong>{{ $product->price ? number_format((float)$product->price, 2, '.', ' ') : '—' }}</strong>
                    </div>
                </div>
            </div>
        </article>

        <article class="card chart-card">
            <div class="card__header">Статистика по отзывам</div>
            <div class="card__body">
                @php
                    $reviews = $product->reviews;
                    $avg = round((float) $reviews->avg('rating'), 1);
                    $total = $reviews->count();
                    $pos = $reviews->filter(fn($r) => $r->sentimentAnalysis?->label === 'positive')->count();
                    $neg = $reviews->filter(fn($r) => $r->sentimentAnalysis?->label === 'negative')->count();
                    $neu = $reviews->filter(fn($r) => $r->sentimentAnalysis?->label === 'neutral')->count();
                @endphp

                <div class="stats-list">
                    <div class="stats-row"><span>Всего отзывов</span><strong>{{ $total }}</strong></div>
                    <div class="stats-row"><span>Средний рейтинг</span><strong>{{ $avg }} / 5</strong></div>
                    <div class="stats-row"><span>Позитивные</span><strong>{{ $pos }}</strong></div>
                    <div class="stats-row"><span>Негативные</span><strong>{{ $neg }}</strong></div>
                    <div class="stats-row"><span>Нейтральные</span><strong>{{ $neu }}</strong></div>
                </div>

                <div style="margin-top: 16px;">
                    <a class="btn btn--small" href="{{ route('reviews.index', ['product_id' => $product->id]) }}">показать отзывы</a>
                </div>
            </div>
        </article>
    </section>

    <section class="sessions" style="margin-top: 32px;">
        <div class="sessions__top">
            <h2 class="sessions__title">Последние отзывы</h2>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Источник</th>
                    <th>Рейтинг</th>
                    <th>Тональность</th>
                    <th>Текст</th>
                    <th style="width: 1%;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($product->reviews->sortByDesc('published_at')->take(10) as $review)
                    <tr>
                        <td class="date-cell">{{ optional($review->published_at)->format('d.m.Y') }}</td>
                        <td>{{ $review->source->name ?? '—' }}</td>
                        <td>{{ $review->rating ?? '—' }}</td>
                        <td>{{ $review->sentimentAnalysis?->label ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($review->text, 80) }}</td>
                        <td class="more-cell"><a href="{{ route('reviews.show', $review) }}">...</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6">Отзывов пока нет.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

