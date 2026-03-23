@extends('layouts.app')

@section('title', 'Редактирование пользователя — Админ-панель — SanStar Аналитика')

@push('styles')
    <style>
        .uedit-page { padding: 24px 0 40px; }
        .uedit-intro { margin-bottom: 16px; }
        .uedit-intro h1 { margin: 0 0 8px; font-size: 28px; font-weight: 800; }
        .uedit-intro p { margin: 0; color: #6b7280; font-size: 14px; }

        .uedit-layout { display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 16px; align-items: start; }
        .uedit-card-top { background: #355c7d; color: #fff; padding: 16px 18px; border-radius: 16px 16px 0 0; }
        .uedit-card-top h2 { margin: 0; font-size: 18px; font-weight: 700; }
        .uedit-form { padding: 18px; }

        .uedit-section + .uedit-section { margin-top: 22px; padding-top: 18px; border-top: 1px solid #eef2f6; }
        .uedit-section h3 { margin: 0 0 6px; font-size: 16px; font-weight: 700; }
        .uedit-section p { margin: 0 0 14px; color: #6b7280; font-size: 13px; }

        .uedit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .uedit-group { display: flex; flex-direction: column; gap: 8px; }
        .uedit-group label { font-size: 13px; font-weight: 700; }
        .uedit-group input, .uedit-group select {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #d8e0e8;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
        }
        .uedit-group input:focus, .uedit-group select:focus {
            outline: none;
            border-color: #355c7d;
            box-shadow: 0 0 0 3px rgba(53, 92, 125, 0.12);
        }

        .uedit-actions { display: flex; gap: 12px; margin-top: 22px; flex-wrap: wrap; }
        .uedit-actions .btn { height: 46px; border-radius: 12px; }
        .uedit-actions .btn-secondary { background: #fff; border: 1px solid #d8e0e8; }

        .uedit-info { padding: 16px; }
        .uedit-info h3 { margin: 0 0 12px; font-size: 16px; font-weight: 800; }
        .uedit-info ul { list-style: none; padding: 0; margin: 0; }
        .uedit-info li { display: flex; justify-content: space-between; gap: 10px; padding: 10px 0; border-bottom: 1px solid #eef2f6; font-size: 13px; }
        .uedit-info li:last-child { border-bottom: none; }
        .uedit-info span { color: #6b7280; }

        @media (max-width: 1024px) { .uedit-layout { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .uedit-grid { grid-template-columns: 1fr; } .uedit-actions { flex-direction: column; } .uedit-actions .btn { width: 100%; } }
    </style>
@endpush

@section('content')
    <div class="uedit-page">
        <section class="uedit-intro">
            <h1>Редактирование пользователя</h1>
            <p>Измените персональные данные, роль, статус блокировки и при необходимости задайте новый пароль.</p>
        </section>

        @if ($errors->any())
            <section class="card" style="margin-bottom: 16px;">
                <div class="uedit-info">
                    <div class="alert-error">
                        <div class="alert-error-title">Ошибка</div>
                        <div>{{ $errors->first() }}</div>
                    </div>
                </div>
            </section>
        @endif

        <section class="uedit-layout">
            <div>
                <div class="card">
                    <div class="uedit-card-top">
                        <h2>Редактирование пользователя</h2>
                    </div>

                    <form class="uedit-form" method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="uedit-section">
                            <h3>Основная информация</h3>
                            <p>Личные и контактные данные пользователя.</p>

                            <div class="uedit-grid">
                                <div class="uedit-group">
                                    <label for="name">Имя</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}">
                                </div>
                                <div class="uedit-group">
                                    <label for="login">Логин</label>
                                    <input id="login" type="text" name="login" value="{{ old('login', $user->login) }}">
                                </div>
                                <div class="uedit-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
                                </div>
                                <div class="uedit-group">
                                    <label for="phone">Телефон</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                        </div>

                        <div class="uedit-section">
                            <h3>Права доступа</h3>
                            <p>Роль определяет уровень доступа к разделам системы.</p>

                            <div class="uedit-grid">
                                <div class="uedit-group">
                                    <label for="role">Роль</label>
                                    <select id="role" name="role_id">
                                        <option value="">Выберите роль</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @selected((string)old('role_id', $user->role_id) === (string)$role->id)>{{ $role->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="uedit-group">
                                    <label for="status">Статус аккаунта</label>
                                    <select id="status" name="is_active">
                                        <option value="1" @selected((string)old('is_active', (int)$user->is_active) === '1')>Активен</option>
                                        <option value="0" @selected((string)old('is_active', (int)$user->is_active) === '0')>Заблокирован</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="uedit-section">
                            <h3>Смена пароля</h3>
                            <p>Оставьте поля пустыми, если пароль менять не требуется.</p>

                            <div class="uedit-grid">
                                <div class="uedit-group">
                                    <label for="password">Новый пароль</label>
                                    <input id="password" type="password" name="password" placeholder="Введите новый пароль">
                                </div>
                                <div class="uedit-group">
                                    <label for="password_confirmation">Подтверждение пароля</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Повторите пароль">
                                </div>
                            </div>
                        </div>

                        <div class="uedit-actions">
                            <button type="submit" class="btn">Сохранить изменения</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>

            <aside>
                <div class="card uedit-info">
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
    </div>
@endsection

