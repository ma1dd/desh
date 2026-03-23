@extends('layouts.app')

@section('title', 'Категории товаров — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Категории товаров</h2>
            <div class="sessions__controls">
                <a class="btn" href="{{ route('admin.categories.create') }}">добавить категорию</a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Родительская категория</th>
                    <th style="width: 1%;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->parent->name ?? '—' }}</td>
                        <td class="more-cell">
                            <a class="btn btn--small" href="{{ route('admin.categories.edit', $category) }}">изменить</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline" data-confirm="Удалить категорию?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn--small" type="submit">удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Категорий не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $categories->links() }}
        </div>
    </section>
@endsection

