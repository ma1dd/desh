@extends('layouts.app')

@section('title', 'Аналитические сессии — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Аналитические сессии</h2>

            <div class="sessions__controls">
                <form class="search" method="GET" action="{{ route('sessions.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="поиск по сессиям...">
                    <button type="submit" aria-label="Поиск">
                        <svg viewBox="0 0 24 24">
                            <path d="M10 2a8 8 0 1 0 5.293 14l4.353 4.354 1.414-1.414-4.354-4.353A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/>
                        </svg>
                    </button>
                </form>

                @can('create', \App\Models\AnalyticalSession::class)
                    <a href="{{ route('sessions.create') }}" class="btn">создать сессию</a>
                @endcan
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Пользователь</th>
                    <th>Дата</th>
                    <th style="width: 1%;">действие</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sessions as $session)
                    <tr>
                        <td><a href="{{ route('sessions.show', $session) }}">{{ $session->title }}</a></td>
                        <td>{{ $session->user->name ?? '—' }}</td>
                        <td class="date-cell">{{ optional($session->created_at)->format('d.m.Y') }}</td>
                        <td class="more-cell">
                            <div class="table-menu">
                                <button type="button" class="table-menu__toggle" aria-label="Действия по сессии">
                                    ⋮
                                </button>
                                <div class="table-menu__dropdown">
                                    <a href="{{ route('sessions.show', $session) }}">Открыть</a>
                                    @can('rerun', $session)
                                        <form method="POST" action="{{ route('sessions.rerun', $session) }}">
                                            @csrf
                                            <button type="submit">Перезапустить</button>
                                        </form>
                                    @endcan
                                    @can('delete', $session)
                                        <form method="POST" action="{{ route('sessions.destroy', $session) }}" data-confirm="Удалить сессию?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Удалить</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Сессий не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="custom-pagination">
            <div class="custom-pagination__info">
                @if($sessions->total() > 0)
                    Показано {{ $sessions->firstItem() }}–{{ $sessions->lastItem() }} из {{ $sessions->total() }} сессий
                @else
                    Нет записей для отображения
                @endif
            </div>

            <div class="custom-pagination__controls">
                {{-- Назад --}}
                @if ($sessions->onFirstPage())
                    <button class="pagination-btn" disabled>←</button>
                @else
                    <a href="{{ $sessions->previousPageUrl() }}" class="pagination-btn">←</a>
                @endif

                {{-- Форма перехода --}}
                <form method="GET" action="{{ url()->current() }}" class="pagination-form">
                    @foreach(request()->except('page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <input
                        type="number"
                        name="page"
                        min="1"
                        max="{{ $sessions->lastPage() }}"
                        value="{{ $sessions->currentPage() }}"
                        class="pagination-input"
                    >

                    <span class="pagination-total">из {{ $sessions->lastPage() }}</span>

                    <button type="submit" class="pagination-go">Перейти</button>
                </form>

                {{-- Вперед --}}
                @if ($sessions->hasMorePages())
                    <a href="{{ $sessions->nextPageUrl() }}" class="pagination-btn">→</a>
                @else
                    <button class="pagination-btn" disabled>→</button>
                @endif
            </div>
        </div>
    </section>
@endsection

