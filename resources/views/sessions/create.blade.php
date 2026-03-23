@extends('layouts.app')

@section('title', 'Создание аналитической сессии — SanStar Аналитика')

@push('styles')
    <style>
        .page__intro {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
            margin-bottom: 24px;
        }

        .page__title h1 {
            margin: 0 0 10px;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 700;
        }

        .page__title p {
            margin: 0;
            max-width: 760px;
            font-size: 15px;
            line-height: 1.6;
            color: var(--muted);
        }

        .layout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 24px;
            align-items: start;
            margin-bottom: 24px;
        }

        .main-column, .side-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card__title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .card__desc {
            margin: 8px 0 0;
            font-size: 14px;
            line-height: 1.6;
            color: rgb(240, 240, 240);

        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            margin-top: 8px;
            font-size: 14px;
            font-weight: 700;
        }

        .input, .textarea {
            width: 100%;
            border: 1px solid #d8e0e8;
            border-radius: 10px;
            background: #fff;
            color: var(--text);
            font-family: inherit;
            font-size: 15px;
            transition: .2s ease;
        }

        .input {
            height: 46px;
            padding: 0 14px;
        }

        .textarea {
            min-height: 110px;
            padding: 12px 14px;
            resize: vertical;
        }

        .input:focus, .textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(53, 95, 120, 0.12);
        }

        .form-row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .quick-periods {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .chip {
            border: 1px solid #cfd8e3;
            background: #f8fafc;
            color: var(--accent);
            font-size: 13px;
            font-weight: 700;
            border-radius: 999px;
            padding: 8px 14px;
            cursor: pointer;
        }

        .chip:hover { background: #eef4f8; }

        .products-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .product-input-wrap {
            position: relative;
        }

        .product-input-wrap .input { padding-right: 42px; }

        .product-input-icon {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }

        .add-product-btn {
            margin-top: 8px;
            align-self: flex-start;
            height: 42px;
            padding: 0 16px;
            border-radius: 999px;
            border: 1px solid #cfd8e3;
            background: #f8fafc;
            color: var(--accent);
            font-weight: 700;
            cursor: pointer;
        }

        .add-product-btn:hover { background: #eef4f8; }

        .actions {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-top: 4px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-radius: 12px;
            height: 48px;
            padding: 0 20px;
            font-weight: 700;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #fff;
            color: var(--text);
            border: 1px solid #d8e0e8;
            border-radius: 12px;
            height: 48px;
            padding: 0 20px;
            font-weight: 700;
            cursor: pointer;
        }

        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 4px;
            margin-left: 10px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #edf1f5;
            font-size: 14px;
        }

        .summary-item:last-child { border-bottom: none; padding-bottom: 0; }
        .summary-item__label { color: var(--muted); }
        .summary-item__value { font-weight: 700; }

        .progress { margin-top: 16px; margin-left: 10px; }
        .progress__label { display:flex; justify-content:space-between; font-size: 13px; color: var(--muted); margin-bottom: 8px; }
        .progress__bar { width:100%; height:8px; background:#edf2f7; border-radius:999px; overflow:hidden; }
        .progress__fill { width:0%; height:100%; background: var(--accent); border-radius:999px; transition: width .2s ease; }

        .tips-list { margin:0; padding-left:18px; color: var(--muted); font-size: 14px; line-height: 1.7; }
        .tips-list li + li { margin-top: 8px; }

        @media (max-width: 1100px) {
            .page__intro, .layout-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .form-row2 { grid-template-columns: 1fr; }
            .actions { flex-direction: column; align-items: stretch; }
            .btn-primary, .btn-secondary { width: 100%; }
        }
    </style>
@endpush

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <div>
                <h2 class="sessions__title">Создание аналитической сессии</h2>
                <p style="margin: 8px 0 0; color: var(--muted); max-width: 760px;">
                    Выберите товары, задайте период исследования и зафиксируйте гипотезы.
                    После подтверждения система сформирует аналитическую сессию для дальнейшего сравнения.
                </p>
            </div>

            <div class="sessions__controls">
                <div class="card" style="display:flex; align-items:center; gap:14px; padding:12px 16px; min-width: 260px;">
                    @php($initials = collect(explode(' ', trim(auth()->user()->name ?? '')))->filter()->map(fn($p)=>mb_substr($p,0,1))->take(2)->implode(''))
                    <div style="width:42px;height:42px;border-radius:50%;background:#edf2f7;display:flex;align-items:center;justify-content:center;color:var(--accent);font-weight:800;">
                        {{ $initials ?: 'SA' }}
                    </div>
                    <div style="display:flex; flex-direction:column; gap:2px;">
                        <span style="font-size:12px;color:var(--muted);">Ответственный аналитик</span>
                        <div style="font-size:15px;font-weight:700;line-height:1.3;">{{ auth()->user()->name ?? '—' }}</div>
                        <span style="font-size:12px;color:var(--accent);font-weight:700;">{{ auth()->user()->role->title ?? auth()->user()->role->name ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <section class="layout-grid">
            <div class="main-column">

                @if ($errors->any())
                    <div class="card" style="margin-bottom: 16px;">
                        <div class="card__body">
                            <div class="alert-error">
                                <div class="alert-error-title">Ошибка</div>
                                <div>{{ $errors->first() }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="form" method="POST" action="{{ route('sessions.store') }}" id="sessionCreateForm">
                    @csrf

                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Основные параметры</h2>
                            <p class="card__desc">Укажите название аналитики, цель исследования и рабочие заметки для команды.</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="title">Название аналитики</label>
                            <input class="input" id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Например: сравнение зеркальных шкафов за Q3">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Описание / цель исследования</label>
                            <textarea class="textarea" id="description" name="description" placeholder="Кратко опишите, что хотите проверить">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="thoughts">Мысли, гипотезы, ожидания</label>
                            <textarea class="textarea" id="thoughts" name="thoughts" placeholder="Запишите свои предположения — к каким выводам должен привести анализ">{{ old('thoughts') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="comment">Комментарий для команды</label>
                            <textarea class="textarea" id="comment" name="comment" placeholder="Добавьте контекст для коллег или дополнительные вопросы">{{ old('comment') }}</textarea>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Период анализа</h2>
                            <p class="card__desc">Выберите диапазон дат, за который необходимо собрать и сравнить данные.</p>
                        </div>

                        <div class="form-row2">
                            <div class="form-group">
                                <label class="form-label" for="date_from">Дата от</label>
                                <input class="input" id="date_from" type="date" name="date_from" value="{{ old('date_from') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="date_to">Дата до</label>
                                <input class="input" id="date_to" type="date" name="date_to" value="{{ old('date_to') }}">
                            </div>
                        </div>

                        <div class="quick-periods">
                            <button type="button" class="chip" data-period="7">7 дней</button>
                            <button type="button" class="chip" data-period="30">30 дней</button>
                            <button type="button" class="chip" data-period="quarter">квартал</button>
                            <button type="button" class="chip" data-period="year">год</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Сравниваемые товары</h2>
                            <p class="card__desc">Выберите от 2 до 5 товаров для сравнительного анализа.</p>
                        </div>

                            <datalist id="productsDatalist">
                                @foreach($products as $p)
                                    <option value="{{ $p->name }}" data-id="{{ $p->id }}"></option>
                                @endforeach
                            </datalist>

                        <div class="products-list" id="productFields">
                            <div class="product-item">
                                <label class="form-label">Товар #1</label>
                                <div class="product-input-wrap">
                                    <input class="input product-name" type="text" list="productsDatalist" placeholder="Начните вводить название товара">
                                    <span class="product-input-icon">⌕</span>
                                </div>
                                <input type="hidden" name="products[]" class="product-id">
                            </div>

                            <div class="product-item">
                                <label class="form-label">Товар #2</label>
                                <div class="product-input-wrap">
                                    <input class="input product-name" type="text" list="productsDatalist" placeholder="Начните вводить название товара">
                                    <span class="product-input-icon">⌕</span>
                                </div>
                                <input type="hidden" name="products[]" class="product-id">
                            </div>
                        </div>

                        <button type="button" class="add-product-btn" id="addProductBtn">+ Добавить товар</button>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn-primary">Подтвердить и перейти к аналитике</button>
                        <a class="btn-secondary" href="{{ route('sessions.index') }}" style="display:inline-flex;align-items:center;justify-content:center;">Отменить</a>
                    </div>
                </form>
            </div>

            <aside class="side-column">
                <div class="card">
                    <div class="card__header">
                        <h2 class="card__title">Предпросмотр аналитики</h2>
                        <p class="card__desc">Заполните параметры сессии, чтобы увидеть её структуру перед запуском.</p>
                    </div>

                    <div class="summary-list">
                        <div class="summary-item">
                            <span class="summary-item__label">Период</span>
                            <span class="summary-item__value" id="previewPeriod">—</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-item__label">Товаров в сравнении</span>
                            <span class="summary-item__value" id="previewProductsCount">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-item__label">Заполнено параметров</span>
                            <span class="summary-item__value" id="previewFilled">0 из 5</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-item__label">Готовность к запуску</span>
                            <span class="summary-item__value" id="previewReady">неполная</span>
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress__label">
                            <span>Прогресс заполнения</span>
                            <span id="previewPercent">0%</span>
                        </div>
                        <div class="progress__bar">
                            <div class="progress__fill" id="previewBar"></div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card__header">
                        <h2 class="card__title">Рекомендации</h2>
                        <p class="card__desc">Короткие подсказки для корректного запуска аналитической сессии.</p>
                    </div>

                    <ul class="tips-list">
                        <li style="margin-left: 5px;">Выберите минимум два товара для сравнения.</li>
                        <li style="margin-left: 5px;">Укажите период анализа, чтобы система собрала данные корректно.</li>
                        <li style="margin-left: 5px;">Добавьте описание, чтобы сессию было легче найти в общем списке.</li>
                    </ul>
                </div>
            </aside>
        </section>

    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const form = document.getElementById('sessionCreateForm');
            if (!form) return;

            const datalist = document.getElementById('productsDatalist');
            const addBtn = document.getElementById('addProductBtn');
            const fieldsWrap = document.getElementById('productFields');

            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');
            const previewPeriod = document.getElementById('previewPeriod');
            const previewCount = document.getElementById('previewProductsCount');
            const title = document.getElementById('title');
            const description = document.getElementById('description');
            const previewFilled = document.getElementById('previewFilled');
            const previewReady = document.getElementById('previewReady');
            const previewPercent = document.getElementById('previewPercent');
            const previewBar = document.getElementById('previewBar');

            function getIdByName(name) {
                if (!datalist) return null;
                const opts = datalist.querySelectorAll('option');
                for (const opt of opts) {
                    if (opt.value === name) return opt.dataset.id || null;
                }
                return null;
            }

            function updatePreview() {
                const from = dateFrom?.value || '';
                const to = dateTo?.value || '';
                previewPeriod.textContent = (from && to) ? `${from} — ${to}` : (from || to || '—');

                const ids = Array.from(fieldsWrap.querySelectorAll('input.product-id'))
                    .map(i => i.value)
                    .filter(Boolean);
                previewCount.textContent = String(ids.length);

                // 5 параметров: title, description, date_from, date_to, products(>=2)
                const filled = [
                    (title?.value || '').trim().length > 0,
                    (description?.value || '').trim().length > 0,
                    !!from,
                    !!to,
                    ids.length >= 2,
                ].filter(Boolean).length;

                const percent = Math.round((filled / 5) * 100);
                if (previewFilled) previewFilled.textContent = `${filled} из 5`;
                if (previewPercent) previewPercent.textContent = `${percent}%`;
                if (previewBar) previewBar.style.width = `${percent}%`;

                const ready = filled === 5;
                if (previewReady) previewReady.textContent = ready ? 'готово' : 'неполная';
            }

            function bindRow(row) {
                const nameInput = row.querySelector('input.product-name');
                const idInput = row.querySelector('input.product-id');
                if (!nameInput || !idInput) return;

                nameInput.addEventListener('input', () => {
                    const id = getIdByName(nameInput.value);
                    idInput.value = id || '';
                    updatePreview();
                });
            }

            function addRow() {
                const rows = fieldsWrap.querySelectorAll('.product-item').length;
                if (rows >= 5) return;

                const idx = rows + 1;
                const row = document.createElement('div');
                row.className = 'product-item';
                row.innerHTML = `
                    <label class="form-label">Товар #${idx}</label>
                    <div class="product-input-wrap">
                        <input class="input product-name" type="text" list="productsDatalist" placeholder="Начните вводить название товара">
                        <span class="product-input-icon">⌕</span>
                    </div>
                    <input type="hidden" name="products[]" class="product-id">
                `;
                fieldsWrap.appendChild(row);
                bindRow(row);
                updatePreview();
            }

            // bind existing rows
            fieldsWrap.querySelectorAll('.product-item').forEach(bindRow);
            addBtn?.addEventListener('click', addRow);
            dateFrom?.addEventListener('change', updatePreview);
            dateTo?.addEventListener('change', updatePreview);
            title?.addEventListener('input', updatePreview);
            description?.addEventListener('input', updatePreview);

            // quick periods
            document.querySelectorAll('.chip[data-period]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const now = new Date();
                    const period = btn.getAttribute('data-period');
                    let from = new Date(now);

                    if (period === '7') from.setDate(now.getDate() - 7);
                    else if (period === '30') from.setDate(now.getDate() - 30);
                    else if (period === 'quarter') from.setMonth(now.getMonth() - 3);
                    else if (period === 'year') from.setFullYear(now.getFullYear() - 1);

                    const iso = (d) => d.toISOString().slice(0, 10);
                    if (dateFrom) dateFrom.value = iso(from);
                    if (dateTo) dateTo.value = iso(now);
                    updatePreview();
                });
            });

            // validate before submit (2..5)
            form.addEventListener('submit', (e) => {
                const ids = Array.from(fieldsWrap.querySelectorAll('input.product-id'))
                    .map(i => i.value)
                    .filter(Boolean);

                if (ids.length < 2 || ids.length > 5) {
                    e.preventDefault();
                    window.SanStarModal?.message('Нужно выбрать товары', 'Выберите минимум 2 и не более 5 товаров из списка перед запуском аналитики.');
                    return;
                }

                // очистим пустые hidden, чтобы не ломать required array
                fieldsWrap.querySelectorAll('input.product-id').forEach(i => {
                    if (!i.value) i.removeAttribute('name');
                });
            });

            updatePreview();
        })();
    </script>
@endpush

