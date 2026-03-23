@extends('layouts.app')

@section('title', 'Товары — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Товары (администрирование)</h2>
            <div class="sessions__controls">
                <a class="btn" href="{{ route('admin.products.create') }}">добавить товар</a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Артикул</th>
                    <th style="width: 1%;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>{{ $product->sku ?? '—' }}</td>
                        <td class="more-cell">
                            <a class="btn btn--small" href="{{ route('admin.products.edit', $product) }}">изменить</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline" data-confirm="Удалить товар?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn--small" type="submit">удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Товаров не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $products->links() }}
        </div>
    </section>
@endsection

