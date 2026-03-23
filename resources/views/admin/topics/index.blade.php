@extends('layouts.app')

@section('title', 'Темы анализа — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Темы анализа</h2>
            <div class="sessions__controls">
                <a class="btn" href="{{ route('admin.topics.create') }}">добавить тему</a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Название темы</th>
                    <th>Описание</th>
                    <th style="width: 1%;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($topics as $topic)
                    <tr>
                        <td>{{ $topic->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($topic->keywords ?? '', 80) ?: '—' }}</td>
                        <td class="more-cell">
                            <a class="btn btn--small" href="{{ route('admin.topics.edit', $topic) }}">изменить</a>
                            <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}" style="display:inline" data-confirm="Удалить тему?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn--small" type="submit">удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Тем не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $topics->links() }}
        </div>
    </section>
@endsection

