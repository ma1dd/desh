@extends('layouts.app')

@section('title', $session->title . ' — аналитическая сессия')

@section('content')
    {{-- 1) Заголовок страницы --}}
    <section class="card" style="margin-bottom: 16px;">
        <div class="card__body">
            <div style="display:flex; gap: 12px; align-items:flex-start; justify-content:space-between; flex-wrap:wrap;">
                <div style="min-width: 280px;">
                    <div style="font-size: 20px; font-weight: 700;">{{ $session->title }}</div>
                    @if($session->description)
                        <div style="color:#7a7a7a; margin-top: 4px;">Описание: {{ $session->description }}</div>
                    @endif
                    <div style="color:#7a7a7a; margin-top: 8px;">
                        Статус: <strong>завершена</strong> ·
                        Автор: <strong>{{ $session->user->name ?? '—' }}</strong> ·
                        Дата создания: <strong>{{ optional($session->created_at)->format('d.m.Y') }}</strong>
                    </div>
                </div>

                {{-- 2) Панель действий --}}
                <div style="display:flex; gap: 10px; flex-wrap:wrap; justify-content:flex-end;">
                    @can('export', $session)
                        <a class="btn btn--small" href="{{ route('sessions.export.csv', $session) }}">экспорт CSV</a>
                    @endcan
                    <button class="btn btn--small" type="button" disabled>экспорт PDF</button>
                    <button class="btn btn--small" type="button" disabled>экспорт Excel</button>

                    @can('rerun', $session)
                        <form method="POST" action="{{ route('sessions.rerun', $session) }}">
                            @csrf
                            <button class="btn btn--small" type="submit">перезапустить анализ</button>
                        </form>
                    @endcan

                    @can('delete', $session)
                        <form method="POST" action="{{ route('sessions.destroy', $session) }}" data-confirm="Удалить сессию?">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn--small" type="submit">удалить</button>
                        </form>
                    @endcan

                    <a class="btn btn--small" href="{{ route('sessions.index') }}">к списку</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 3) Карточка параметров сессии --}}
    <section class="card" style="margin-bottom: 16px;">
        <div class="card__header">Параметры аналитической сессии</div>
        <div class="card__body">
            <div class="stats-list">
                <div class="stats-row">
                    <span>Период анализа</span>
                    <strong>{{ $dateFrom ?? '—' }} — {{ $dateTo ?? '—' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Выбранные товары</span>
                    <strong>{{ $products->pluck('name')->implode(', ') ?: '—' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Гипотезы / ожидания</span>
                    <strong>{{ data_get($session->parameters, 'thoughts') ?: '—' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Комментарий для команды</span>
                    <strong>{{ data_get($session->parameters, 'comment') ?: data_get($session->parameters, 'period.comment') ?: '—' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Дата запуска</span>
                    <strong>{{ optional($session->started_at)->format('d.m.Y H:i') ?: '—' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Дата завершения</span>
                    <strong>{{ optional($session->finished_at)->format('d.m.Y H:i') ?: '—' }}</strong>
                </div>
            </div>
        </div>
    </section>

    {{-- 4) KPI-карточки --}}
    <section class="top-grid" style="margin-bottom: 16px;">
        <article class="card stats-card">
            <div class="card__header">KPI</div>
            <div class="card__body">
                <div class="stats-list">
                    <div class="stats-row"><span>Всего отзывов</span><strong>{{ $kpi['total'] }}</strong></div>
                    <div class="stats-row"><span>Позитивные</span><strong>{{ $kpi['positive'] }}</strong></div>
                    <div class="stats-row"><span>Негативные</span><strong>{{ $kpi['negative'] }}</strong></div>
                    <div class="stats-row"><span>Нейтральные</span><strong>{{ $kpi['neutral'] }}</strong></div>
                    <div class="stats-row"><span>Средний рейтинг</span><strong>{{ $kpi['avg_rating'] }} / 5</strong></div>
                    <div class="stats-row"><span>NPS</span><strong>{{ $kpi['nps'] }}</strong></div>
                    <div class="stats-row"><span>Доля негатива</span><strong>{{ $kpi['neg_share'] }}%</strong></div>
                    <div class="stats-row"><span>Уникальных тем</span><strong>{{ $kpi['unique_topics'] }}</strong></div>
                </div>
            </div>
        </article>

        <article class="card chart-card">
            <div class="card__header">Графики динамики</div>
            <div class="card__body">
                <div class="chart-wrapper" style="min-height: 240px;">
                    <div id="sessionToneChart" class="d3-chart"></div>
                </div>
                <div class="chart-wrapper" style="margin-top: 16px; min-height: 240px;">
                    <div id="sessionRatingChart" class="d3-chart"></div>
                </div>
            </div>
        </article>
    </section>

    {{-- 5) Сравнение товаров --}}
    <section class="sessions" style="margin-top: 0;">
        <div class="sessions__top">
            <h2 class="sessions__title">Сравнение товаров</h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Отзывы</th>
                    <th>Средний рейтинг</th>
                    <th>Позитивные</th>
                    <th>Негативные</th>
                    <th>Нейтральные</th>
                    <th>Топ-тема</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $p)
                    @php($row = $byProduct->get($p->id) ?? null)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $row['total'] ?? 0 }}</td>
                        <td>{{ $row['avg'] ?? '—' }}</td>
                        <td>{{ $row['positive'] ?? 0 }}</td>
                        <td>{{ $row['negative'] ?? 0 }}</td>
                        <td>{{ $row['neutral'] ?? 0 }}</td>
                        <td>{{ $row['top_topic'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7">Товары не выбраны.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- 6) Темы и тональность --}}
    <section class="top-grid" style="margin-top: 16px;">
        <article class="card stats-card">
            <div class="card__header">Топ тем</div>
            <div class="card__body">
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Тема</th>
                            <th>Упоминаний</th>
                            <th>Доля негатива</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($topicStats as $t)
                            <tr>
                                <td>{{ $t['name'] }}</td>
                                <td>{{ $t['count'] }}</td>
                                <td>{{ $t['neg_share'] }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="3">Темы не найдены.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>

        <article class="card chart-card">
            <div class="card__header">Распределение тональности</div>
            <div class="card__body">
                <div class="stats-list">
                    <div class="stats-row"><span>Позитив</span><strong>{{ $kpi['positive'] }}</strong></div>
                    <div class="stats-row"><span>Негатив</span><strong>{{ $kpi['negative'] }}</strong></div>
                    <div class="stats-row"><span>Нейтрально</span><strong>{{ $kpi['neutral'] }}</strong></div>
                </div>
            </div>
        </article>
    </section>

    {{-- 7) Таблица отзывов --}}
    <section class="sessions" style="margin-top: 16px;">
        <div class="sessions__top">
            <h2 class="sessions__title">Отзывы (последние)</h2>
            <div class="sessions__controls">
                <a class="btn btn--small" href="{{ route('reviews.index', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}">открыть все отзывы</a>
            </div>
        </div>

        {{-- Фильтры отзывов (как на reviews.index) --}}
        <form method="GET" action="{{ route('sessions.show', $session) }}" class="filters-panel">
            <div class="filters-row">
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
                <input class="filter-control" type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}">
                <input class="filter-control" type="date" name="date_to" value="{{ request('date_to', $dateTo) }}">

                <div class="filter-actions">
                    <button class="btn btn--small" type="submit">применить</button>
                    <a class="btn btn--small" href="{{ route('sessions.show', $session) }}">сбросить</a>
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
                    <th style="width:1%;">действие</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reviews as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td class="date-cell">{{ optional($r->published_at)->format('d.m.Y') }}</td>
                        <td>{{ $r->product->name ?? '—' }}</td>
                        <td>{{ $r->source->name ?? '—' }}</td>
                        <td>{{ $r->rating ?? '—' }}</td>
                        <td>{{ $r->sentimentAnalysis?->label ?? '—' }}</td>
                        <td>{{ $r->topics->pluck('name')->take(3)->implode(', ') }}</td>
                        <td>{{ $r->region ?? '—' }}</td>
                        <td class="more-cell"><a href="{{ route('reviews.show', $r) }}">...</a></td>
                    </tr>
                @empty
                    <tr><td colspan="9">Отзывов не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="custom-pagination">
                <div class="custom-pagination__info">
                    @if($reviews->total() > 0)
                        Показано {{ $reviews->firstItem() }}–{{ $reviews->lastItem() }} из {{ $reviews->total() }} отзывов
                    @else
                        Нет отзывов для отображения
                    @endif
                </div>

                <div class="custom-pagination__controls">
                    {{-- Назад --}}
                    @if ($reviews->onFirstPage())
                        <button class="pagination-btn" disabled>←</button>
                    @else
                        <a href="{{ $reviews->previousPageUrl() }}" class="pagination-btn">←</a>
                    @endif

                    {{-- Форма перехода --}}
                    <form method="GET" action="{{ url()->current() }}" class="pagination-form">
                        @foreach(request()->except('reviewsPage') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <input
                            type="number"
                            name="reviewsPage"
                            min="1"
                            max="{{ $reviews->lastPage() }}"
                            value="{{ $reviews->currentPage() }}"
                            class="pagination-input"
                        >

                        <span class="pagination-total">из {{ $reviews->lastPage() }}</span>

                        <button type="submit" class="pagination-go">Перейти</button>
                    </form>

                    {{-- Вперед --}}
                    @if ($reviews->hasMorePages())
                        <a href="{{ $reviews->nextPageUrl() }}" class="pagination-btn">→</a>
                    @else
                        <button class="pagination-btn" disabled>→</button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- 8) Выводы и рекомендации --}}
    <section class="card" style="margin-top: 16px;">
        <div class="card__header">Выводы и рекомендации</div>
        <div class="card__body">
            <div>
                <strong>Выводы</strong>
                <ul>
                    @foreach($findings['summary'] as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
            </div>

            @if(!empty($findings['recommendations']))
                <div style="margin-top: 12px;">
                    <strong>Рекомендации</strong>
                    <ul>
                        @foreach($findings['recommendations'] as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    {{-- 9) Техническая информация о сессии --}}
    <section class="card" style="margin-top: 16px;">
        <div class="card__header">Техническая информация</div>
        <div class="card__body">
            <div class="stats-list">
                <div class="stats-row"><span>ID сессии</span><strong>{{ $session->id }}</strong></div>
                <div class="stats-row"><span>Создана</span><strong>{{ optional($session->created_at)->format('d.m.Y H:i') }}</strong></div>
                <div class="stats-row"><span>Обновлена</span><strong>{{ optional($session->updated_at)->format('d.m.Y H:i') }}</strong></div>
                <div class="stats-row"><span>Отзывов в выборке</span><strong>{{ $kpi['total'] }}</strong></div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        window.sessionDaily = @json($daily);
    </script>
    <script>
        (function () {
            if (typeof d3 === 'undefined') return;
            const data = window.sessionDaily || [];

            function renderMultiTone(containerId) {
                const el = document.getElementById(containerId);
                if (!el) return;
                el.innerHTML = '';

                const width = el.clientWidth || 600;
                const height = el.clientHeight || 240;
                const margin = { top: 10, right: 16, bottom: 30, left: 44 };
                const innerW = width - margin.left - margin.right;
                const innerH = height - margin.top - margin.bottom;

                const parsed = data.map(d => ({...d, _date: new Date(d.day)})).filter(d => !isNaN(d._date));
                if (!parsed.length) return;

                const svg = d3.select(el).append('svg').attr('viewBox', `0 0 ${width} ${height}`);
                const g = svg.append('g').attr('transform', `translate(${margin.left},${margin.top})`);

                const x = d3.scaleTime().domain(d3.extent(parsed, d => d._date)).range([0, innerW]);
                const maxY = d3.max(parsed, d => Math.max(+d.positive, +d.negative, +d.neutral)) || 1;
                const y = d3.scaleLinear().domain([0, maxY]).nice().range([innerH, 0]);

                g.append('g').attr('class', 'axis').attr('transform', `translate(0,${innerH})`).call(d3.axisBottom(x).ticks(5));
                g.append('g').attr('class', 'axis').call(d3.axisLeft(y).ticks(5));

                const mkLine = (key) => d3.line()
                    .x(d => x(d._date))
                    .y(d => y(+d[key]))
                    .curve(d3.curveMonotoneX);

                g.append('path').datum(parsed).attr('class', 'line-positive').attr('fill', 'none').attr('d', mkLine('positive'));
                g.append('path').datum(parsed).attr('class', 'line-negative').attr('fill', 'none').attr('d', mkLine('negative'));
                g.append('path').datum(parsed).attr('class', 'line-neutral').attr('fill', 'none').attr('d', mkLine('neutral'));
            }

            function renderLineChart(containerId, yKey, strokeClass, yDomain) {
                const el = document.getElementById(containerId);
                if (!el) return;
                el.innerHTML = '';

                const width = el.clientWidth || 600;
                const height = el.clientHeight || 240;
                const margin = { top: 10, right: 16, bottom: 30, left: 44 };
                const innerW = width - margin.left - margin.right;
                const innerH = height - margin.top - margin.bottom;

                const parsed = data.map(d => ({...d, _date: new Date(d.day)})).filter(d => !isNaN(d._date));
                if (!parsed.length) return;

                const svg = d3.select(el).append('svg').attr('viewBox', `0 0 ${width} ${height}`);
                const g = svg.append('g').attr('transform', `translate(${margin.left},${margin.top})`);

                const x = d3.scaleTime().domain(d3.extent(parsed, d => d._date)).range([0, innerW]);
                const max = d3.max(parsed, d => +d[yKey]) || 1;
                const y = d3.scaleLinear().domain(yDomain ?? [0, max]).nice().range([innerH, 0]);

                g.append('g').attr('class', 'axis').attr('transform', `translate(0,${innerH})`).call(d3.axisBottom(x).ticks(5));
                g.append('g').attr('class', 'axis').call(d3.axisLeft(y).ticks(5));

                const line = d3.line().x(d => x(d._date)).y(d => y(+d[yKey])).curve(d3.curveMonotoneX);
                g.append('path').datum(parsed).attr('class', strokeClass).attr('fill', 'none').attr('d', line);
            }

            // Тональность: 3 линии, рейтинг: 0..5
            renderMultiTone('sessionToneChart');
            renderLineChart('sessionRatingChart', 'avg_rating', 'line-positive', [0, 5]);
        })();
    </script>
@endpush

