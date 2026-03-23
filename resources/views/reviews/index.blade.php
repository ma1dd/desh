@extends('layouts.app')

@section('title', 'Отзывы — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Отзывы и детальный анализ</h2>

            <div class="sessions__controls">
                <form class="search" method="GET" action="{{ route('reviews.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="поиск по отзывам...">
                    <button type="submit" aria-label="Поиск">
                        <svg viewBox="0 0 24 24">
                            <path d="M10 2a8 8 0 1 0 5.293 14l4.353 4.354 1.414-1.414-4.354-4.353A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <form method="GET" action="{{ route('reviews.index') }}" class="filters-panel">
            <div class="filters-row">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <select class="filter-control" name="product_id" onchange="this.form.submit()">
                    <option value="">все товары</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected((string)request('product_id')===(string)$product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>

                <select class="filter-control" name="source_id" onchange="this.form.submit()">
                    <option value="">все источники</option>
                    @foreach($sources as $source)
                        <option value="{{ $source->id }}" @selected((string)request('source_id')===(string)$source->id)>{{ $source->name }}</option>
                    @endforeach
                </select>

                <select class="filter-control" name="sentiment" onchange="this.form.submit()">
                    <option value="">тональность: любая</option>
                    <option value="positive" @selected(request('sentiment')==='positive')>позитивная</option>
                    <option value="neutral" @selected(request('sentiment')==='neutral')>нейтральная</option>
                    <option value="negative" @selected(request('sentiment')==='negative')>негативная</option>
                </select>

                <select class="filter-control" name="topic_id" onchange="this.form.submit()">
                    <option value="">тема: любая</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" @selected((string)request('topic_id')===(string)$topic->id)>{{ $topic->name }}</option>
                    @endforeach
                </select>

                <select class="filter-control" name="rating" onchange="this.form.submit()">
                    <option value="">рейтинг: любой</option>
                    @for($r=5;$r>=1;$r--)
                        <option value="{{ $r }}" @selected((string)request('rating')===(string)$r)>{{ $r }}</option>
                    @endfor
                </select>

                <input class="filter-control" type="text" name="region" value="{{ request('region') }}" placeholder="регион">
                <input class="filter-control" type="date" name="date_from" value="{{ request('date_from') }}">
                <input class="filter-control" type="date" name="date_to" value="{{ request('date_to') }}">

                <div class="filter-actions">
                    <button class="btn btn--small" type="submit">применить</button>
                    <a class="btn btn--small" href="{{ route('reviews.index') }}">сбросить</a>
                </div>
            </div>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>Товар</th>
                    <th>Источник</th>
                    <th>Рейтинг</th>
                    <th>Тональность</th>
                    <th>Темы</th>
                    <th>Регион</th>
                    <th>Статус</th>
                    <th style="width: 1%;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td class="date-cell">{{ optional($review->published_at)->format('d.m.Y') }}</td>
                        <td>{{ $review->product->name ?? '—' }}</td>
                        <td>{{ $review->source->name ?? '—' }}</td>
                        <td>{{ $review->rating ?? '—' }}</td>
                        <td>{{ $review->sentimentAnalysis?->label ?? '—' }}</td>
                        <td>
                            {{ $review->topics->pluck('name')->take(3)->implode(', ') }}
                        </td>
                        <td>{{ $review->region ?? '—' }}</td>
                        <td>{{ $review->status }}</td>
                        <td class="more-cell"><a href="{{ route('reviews.show', $review) }}">...</a></td>
                    </tr>
                @empty
                    <tr><td colspan="10">Отзывов не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $reviews->links() }}
        </div>
    </section>
@endsection

