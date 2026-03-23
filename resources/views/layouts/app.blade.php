<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SanStar Аналитика')</title>

    <!-- <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <script src="https://d3js.org/d3.v7.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="container header__inner">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ Vite::asset('resources/views/layouts/img/SanStar.png') }}" alt="SanStar" class="logo__img" style="height: 34px; width: auto;">
                </a>
            </div>  

            <nav class="nav">
                <a href="{{ route('dashboard') }}" class="nav__link {{ request()->routeIs('dashboard') ? 'nav__link--active' : '' }}">главная</a>
                <a href="{{ route('products.index') }}" class="nav__link {{ request()->routeIs('products.*') ? 'nav__link--active' : '' }}">товары</a>
                <a href="{{ route('sessions.index') }}" class="nav__link {{ request()->routeIs('sessions.*') ? 'nav__link--active' : '' }}">аналитические сессии</a>
                @can('view-admin-panel')
                    <a href="{{ route('admin.users.index') }}" class="nav__link {{ request()->is('admin*') ? 'nav__link--active' : '' }}">панель администратора</a>
                @endcan
            </nav>

            @auth
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('profile.index') }}" class="profile-btn" title="Профиль">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn--small" type="submit">выйти</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="main">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- Глобальные модальные окна (подтверждения + сообщения) --}}
    <div id="appModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.45);z-index:999;align-items:center;justify-content:center;">
        <div class="card" style="max-width:520px;width:calc(100% - 32px);box-shadow:0 20px 45px rgba(15,23,42,0.3);">
            <div class="card__header">
                <h2 class="card__title" id="appModalTitle">Сообщение</h2>
                <p class="card__desc" id="appModalText"></p>
            </div>
            <div class="card__body" style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                <button type="button" class="btn btn--small" id="appModalCancel" style="display:none;">Отмена</button>
                <button type="button" class="btn" id="appModalOk">Ок</button>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        (function () {
            const modal = document.getElementById('appModal');
            const titleEl = document.getElementById('appModalTitle');
            const textEl = document.getElementById('appModalText');
            const okBtn = document.getElementById('appModalOk');
            const cancelBtn = document.getElementById('appModalCancel');

            let onOk = null;
            let onCancel = null;

            function openModal({ title, text, okText, cancelText, showCancel, onConfirm, onClose }) {
                if (!modal) return;
                titleEl.textContent = title || 'Сообщение';
                textEl.textContent = text || '';
                okBtn.textContent = okText || 'Ок';
                cancelBtn.textContent = cancelText || 'Отмена';
                cancelBtn.style.display = showCancel ? '' : 'none';
                modal.style.display = 'flex';

                onOk = () => { if (onConfirm) onConfirm(); closeModal(); };
                onCancel = () => { closeModal(); if (onClose) onClose(); };
            }

            function closeModal() {
                if (!modal) return;
                modal.style.display = 'none';
                onOk = null;
                onCancel = null;
            }

            okBtn?.addEventListener('click', () => onOk && onOk());
            cancelBtn?.addEventListener('click', () => onCancel && onCancel());
            modal?.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

            window.SanStarModal = {
                message(title, text) {
                    openModal({ title, text, showCancel: false });
                },
                confirm(title, text, onConfirm) {
                    openModal({ title, text, showCancel: true, okText: 'Подтвердить', cancelText: 'Отмена', onConfirm });
                }
            };

            // Подтверждения действий для форм/кнопок
            document.addEventListener('submit', (e) => {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                const msg = form.getAttribute('data-confirm');
                if (!msg) return;
                e.preventDefault();
                window.SanStarModal?.confirm('Подтвердите действие', msg, () => form.submit());
            }, true);

            // Флеш-сообщения (success/error) показываем модалкой
            const flashSuccess = @json(session('success'));
            const flashError = @json(session('error'));
            if (flashSuccess) window.SanStarModal?.message('Готово', flashSuccess);
            if (flashError) window.SanStarModal?.message('Ошибка', flashError);
        })();
    </script>
</body>
</html>

