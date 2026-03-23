@extends('layouts.app')

@section('title', 'Дашборд — SanStar Аналитика')

@section('content')
            <section class="top-grid">
                <article class="card stats-card">
                    <div class="card__header">Общая Статистика</div>
                    <div class="card__body">
                        <div class="stats-list">
                            <div class="stats-row">
                                <span>Всего отзывов</span>
                                <strong>{{ number_format($totalReviews ?? 0, 0, '.', ' ') }}</strong>
                            </div>
                            <div class="stats-row">
                                <span>Средний рейтинг</span>
                                <strong>{{ $averageRating ?? 0 }} / 5</strong>
                            </div>
                            <div class="stats-row">
                                <span>Процент позитивных отзывов</span>
                                <strong>{{ $positivePercent ?? 0 }}%</strong>
                            </div>
                            <div class="stats-row">
                                <span>Активных источников</span>
                                <strong>{{ $activeSources ?? 0 }}</strong>
                            </div>
                            <div class="stats-row">
                                <span>Новых продуктов</span>
                                <strong>{{ $newProducts ?? 0 }}</strong>
                            </div>
                        </div>

                        <div>
                            <a class="btn btn--small" href="{{ route('reviews.index') }}">к отзывам</a>
                        </div>
                    </div>
                </article>

                <article class="card chart-card">
                    <div class="card__header">Динамика Тональности</div>
                    <div class="card__body">
                        <div class="chart-legend">
                            <div class="legend-item legend-positive">
                                <span class="legend-line"></span>
                                <span>Процент позитивных отзывов</span>
                            </div>
                            <div class="legend-item legend-negative">
                                <span class="legend-line"></span>
                                <span>Процент негативных отзывов</span>
                            </div>
                        </div>

                        <div class="chart-wrapper">
                            <div id="toneChartD3" class="d3-chart"></div>
                            <div class="chart-tooltip" id="chartTooltip"></div>
                        </div>

                        <div class="chart-footer">
                            <div class="period-badge">за последние <span>7</span> дней</div>
                            <a class="btn btn--small" href="{{ route('reviews.index') }}">подробнее</a>
                        </div>
                    </div>
                </article>
            </section>

            <section class="sessions">
                <div class="sessions__top">
                    <h2 class="sessions__title">Последние аналитические сессии</h2>

                    <div class="sessions__controls">
                        <form class="search" method="GET" action="{{ route('sessions.index') }}">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="поиск...">
                            <button type="submit" aria-label="Поиск">
                                <svg viewBox="0 0 24 24">
                                    <path d="M10 2a8 8 0 1 0 5.293 14l4.353 4.354 1.414-1.414-4.354-4.353A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/>
                                </svg>
                            </button>
                        </form>

                        <a class="btn" href="{{ route('sessions.index') }}">все сессии</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 32%;">Пользователь</th>
                                <th>Анализ</th>
                                <th style="width: 12%;">Дата</th>
                                <th style="width: 4%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestSessions ?? [] as $session)
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <span class="user-icon">
                                                <svg viewBox="0 0 24 24">
                                                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5z"/>
                                                </svg>
                                            </span>
                                            <span>{{ $session->user->name ?? '—' }} ({{ $session->user->role->title ?? $session->user->role->name ?? 'роль' }})</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('sessions.show', $session) }}">{{ $session->title }}</a>
                                    </td>
                                    <td class="date-cell">{{ optional($session->created_at)->format('d.m.Y') }}</td>
                                    <td class="more-cell">
                                        <div class="table-menu">
                                            <button type="button" class="table-menu__toggle" aria-label="Действия по сессии">
                                                ⋮
                                            </button>
                                            <div class="table-menu__dropdown">
                                                <a href="{{ route('sessions.show', $session) }}">Открыть</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Сессий пока нет.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="sessions__bottom">
                    <a class="btn" href="{{ route('sessions.index') }}">перейти к списку</a>
                </div>
            </section>
@endsection

@push('scripts')
    <script>
        window.dashboardToneData = @json(($chartData ?? collect())->values());
    </script>
@endpush