@extends('layouts.app')

@section('title', 'Источники отзывов — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Источники отзывов</h2>
            <div class="sessions__controls">
                <a class="btn" href="{{ route('admin.sources.create') }}">добавить источник</a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Название</th>
                    <th>URL</th>
                    <th style="width: 1%;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($sources as $source)
                    <tr>
                        <td>{{ $source->name }}</td>
                        <td>{{ $source->base_url ?? '—' }}</td>
                        <td class="more-cell">
                            <a class="btn btn--small" href="{{ route('admin.sources.edit', $source) }}">изменить</a>
                            <form method="POST" action="{{ route('admin.sources.destroy', $source) }}" style="display:inline" data-confirm="Удалить источник?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn--small" type="submit">удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Источников не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $sources->links() }}
        </div>
    </section>
@endsection

