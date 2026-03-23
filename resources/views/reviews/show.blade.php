@extends('layouts.app')

@section('title', 'Отзыв #' . $review->id . ' — SanStar Аналитика')

@section('content')
    <section class="top-grid">
        <article class="card stats-card">
            <div class="card__header">Отзыв #{{ $review->id }}</div>
            <div class="card__body">
                <div class="stats-list">
                    <div class="stats-row"><span>Товар</span><strong>{{ $review->product->name ?? '—' }}</strong></div>
                    <div class="stats-row"><span>Категория</span><strong>{{ $review->product->category->name ?? '—' }}</strong></div>
                    <div class="stats-row"><span>Источник</span><strong>{{ $review->source->name ?? '—' }}</strong></div>
                    <div class="stats-row"><span>Дата</span><strong>{{ optional($review->published_at)->format('d.m.Y H:i') }}</strong></div>
                    <div class="stats-row"><span>Рейтинг</span><strong>{{ $review->rating ?? '—' }}</strong></div>
                    <div class="stats-row"><span>Статус</span><strong>{{ $review->status }}</strong></div>
                    <div class="stats-row"><span>Регион</span><strong>{{ $review->region ?? '—' }}</strong></div>
                </div>
            </div>
        </article>

        <article class="card chart-card">
            <div class="card__header">Результаты анализа</div>
            <div class="card__body">
                <div class="stats-list">
                    <div class="stats-row"><span>Тональность</span><strong>{{ $review->sentimentAnalysis?->label ?? '—' }}</strong></div>
                    <div class="stats-row"><span>Скор</span><strong>{{ $review->sentimentAnalysis?->score ?? '—' }}</strong></div>
                    <div class="stats-row"><span>Уверенность</span><strong>{{ $review->sentimentAnalysis?->confidence ?? '—' }}</strong></div>
                </div>

                <div style="margin-top: 12px;">
                    <strong>Темы:</strong>
                    <div>{{ $review->topics->pluck('name')->implode(', ') ?: '—' }}</div>
                </div>
            </div>
        </article>
    </section>

    <section class="card" style="margin-top: 32px;">
        <div class="card__header">Текст отзыва</div>
        <div class="card__body">
            <div style="white-space: pre-wrap;">{{ $review->text }}</div>
        </div>
    </section>

    <div style="margin-top: 16px;">
        <a class="btn btn--small" href="{{ route('reviews.index') }}">назад к списку</a>
        @if($review->product)
            <a class="btn btn--small" href="{{ route('products.show', $review->product) }}">к товару</a>
        @endif
    </div>
@endsection

