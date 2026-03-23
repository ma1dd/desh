@extends('layouts.app')

@section('title', 'Товары — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Товары</h2>

            <div class="sessions__controls">
                <form class="search" method="GET" action="{{ route('products.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="поиск по товарам...">
                    <button type="submit" aria-label="Поиск">
                        <svg viewBox="0 0 24 24">
                            <path d="M10 2a8 8 0 1 0 5.293 14l4.353 4.354 1.414-1.414-4.354-4.353A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/>
                        </svg>
                    </button>
                </form>

                <form method="GET" action="{{ route('products.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="category_id" onchange="this.form.submit()">
                        <option value="">все категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string)request('category_id') === (string)$category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Категория</th>
                    <th>Средняя оценка</th>
                    <th>Кол-во отзывов</th>
                    <th style="width: 1%;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                        </td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>{{ number_format((float) $product->reviews()->avg('rating'), 1) }}</td>
                        <td>{{ $product->reviews()->count() }}</td>
                        <td class="more-cell">...</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Товаров не найдено.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $products->links() }}
        </div>
    </section>
@endsection

