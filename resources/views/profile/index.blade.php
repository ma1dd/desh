@extends('layouts.app')

@section('title', 'Профиль — SanStar Аналитика')

@push('styles')
    <style>
        .pedit-page { padding: 24px 0 40px; }
        .pedit-intro { margin-bottom: 16px; }
        .pedit-intro h1 { margin: 0 0 8px; font-size: 28px; font-weight: 800; }
        .pedit-intro p { margin: 0; color: #6b7280; font-size: 14px; }

        .pedit-layout { display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 16px; align-items: start; }
        .pedit-card-top { background: #355c7d; color: #fff; padding: 16px 18px; border-radius: 16px 16px 0 0; }
        .pedit-card-top h2 { margin: 0; font-size: 18px; font-weight: 700; }
        .pedit-form { padding: 18px; }

        .pedit-section + .pedit-section { margin-top: 22px; padding-top: 18px; border-top: 1px solid #eef2f6; }
        .pedit-section h3 { margin: 0 0 6px; font-size: 16px; font-weight: 700; }
        .pedit-section p { margin: 0 0 14px; color: #6b7280; font-size: 13px; }

        .pedit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .pedit-group { display: flex; flex-direction: column; gap: 8px; }
        .pedit-group label { font-size: 13px; font-weight: 700; }
        .pedit-group input {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #d8e0e8;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
        }
        .pedit-group input:focus {
            outline: none;
            border-color: #355c7d;
            box-shadow: 0 0 0 3px rgba(53, 92, 125, 0.12);
        }

        .pedit-actions { display: flex; gap: 12px; margin-top: 18px; flex-wrap: wrap; }
        .pedit-actions .btn { height: 46px; border-radius: 12px; }

        .pedit-info { padding: 16px; }
        .pedit-info h3 { margin: 0 0 12px; font-size: 16px; font-weight: 800; }
        .pedit-info ul { list-style: none; padding: 0; margin: 0; }
        .pedit-info li { display: flex; justify-content: space-between; gap: 10px; padding: 10px 0; border-bottom: 1px solid #eef2f6; font-size: 13px; }
        .pedit-info li:last-child { border-bottom: none; }
        .pedit-info span { color: #6b7280; }

        @media (max-width: 1024px) { .pedit-layout { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            .pedit-grid { grid-template-columns: 1fr; }
            .pedit-actions { flex-direction: column; }
            .pedit-actions .btn { width: 100%; }
        }
    </style>
@endpush

@section('content')
    <div class="pedit-page">
        <section class="pedit-intro">
            <h1>Профиль</h1>
            <p>Управляйте личными данными и безопасностью аккаунта. Онлайн-статус определяется автоматически по активности.</p>
        </section>

        <section class="pedit-layout">
            <div>
                <div class="card" style="margin-bottom: 16px;">
                    <div class="pedit-card-top">
                        <h2>Личные данные</h2>
                    </div>

                    <form class="pedit-form" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="pedit-section">
                            <h3>Основная информация</h3>
                            <p>Контактные данные и логин для входа.</p>

                            <div class="pedit-grid">
                                <div class="pedit-group">
                                    <label for="name">Имя</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}">
                                </div>

                                <div class="pedit-group">
                                    <label for="login">Логин</label>
                                    <input id="login" type="text" name="login" value="{{ old('login', $user->login) }}">
                                </div>

                                <div class="pedit-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
                                </div>

                                <div class="pedit-group">
                                    <label for="phone">Телефон</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                        </div>

                        <div class="pedit-actions">
                            <button class="btn" type="submit">Сохранить профиль</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="pedit-card-top">
                        <h2>Безопасность</h2>
                    </div>

                    <form class="pedit-form" method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="pedit-section">
                            <h3>Смена пароля</h3>
                            <p>Для смены пароля укажите текущий пароль и задайте новый.</p>

                            <div class="pedit-grid">
                                <div class="pedit-group">
                                    <label for="current_password">Текущий пароль</label>
                                    <input id="current_password" type="password" name="current_password">
                                </div>

                                <div class="pedit-group">
                                    <label for="password">Новый пароль</label>
                                    <input id="password" type="password" name="password">
                                </div>

                                <div class="pedit-group">
                                    <label for="password_confirmation">Подтверждение пароля</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="pedit-actions">
                            <button class="btn" type="submit">Сменить пароль</button>
                        </div>
                    </form>
                </div>
            </div>

            <aside>
                <div class="card pedit-info">
                    <h3>Информация</h3>
                    <ul>
                        <li><span>ID:</span> {{ $user->id }}</li>
                        <li><span>Роль:</span> {{ $user->role->title ?? $user->role->name ?? '—' }}</li>
                        <li><span>Аккаунт:</span> {{ $user->is_active ? 'Активен' : 'Заблокирован' }}</li>
                        <li><span>В сети:</span> {{ $user->isOnline() ? 'Да' : 'Нет' }}</li>
                        <li><span>Последняя активность:</span> {{ optional($user->last_seen_at)->format('d.m.Y H:i') ?? '—' }}</li>
                        <li><span>Регистрация:</span> {{ optional($user->created_at)->format('d.m.Y') ?? '—' }}</li>
                    </ul>
                </div>
            </aside>
        </section>

        <section class="sessions" style="margin-top: 24px;">
            <div class="sessions__top">
                <h2 class="sessions__title">Мои аналитические сессии</h2>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Дата</th>
                        <th style="width: 1%;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($user->analyticalSessions as $session)
                        <tr>
                            <td><a href="{{ route('sessions.show', $session) }}">{{ $session->title }}</a></td>
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
                        <tr><td colspan="3">Сессий пока нет.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

