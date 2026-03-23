@extends('layouts.app')

@section('title', 'Создание пользователя — Админ-панель — SanStar Аналитика')

@push('styles')
    <style>
        .ucreate-page { padding: 24px 0 40px; }
        .ucreate-intro { margin-bottom: 16px; }
        .ucreate-intro h1 { margin: 0 0 8px; font-size: 28px; font-weight: 800; }
        .ucreate-intro p { margin: 0; color: #6b7280; font-size: 14px; }

        .ucreate-layout { display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 16px; align-items: start; }
        .ucreate-card-top { background: #355c7d; color: #fff; padding: 16px 18px; border-radius: 16px 16px 0 0; }
        .ucreate-card-top h2 { margin: 0; font-size: 18px; font-weight: 700; }
        .ucreate-form { padding: 18px; }

        .ucreate-section + .ucreate-section { margin-top: 22px; padding-top: 18px; border-top: 1px solid #eef2f6; }
        .ucreate-section h3 { margin: 0 0 6px; font-size: 16px; font-weight: 700; }
        .ucreate-section p { margin: 0 0 14px; color: #6b7280; font-size: 13px; }

        .ucreate-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .ucreate-group { display: flex; flex-direction: column; gap: 8px; }
        .ucreate-group label { font-size: 13px; font-weight: 700; }
        .ucreate-group input, .ucreate-group select {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #d8e0e8;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
        }
        .ucreate-group input:focus, .ucreate-group select:focus {
            outline: none;
            border-color: #355c7d;
            box-shadow: 0 0 0 3px rgba(53, 92, 125, 0.12);
        }

        .ucreate-actions { display: flex; gap: 12px; margin-top: 22px; flex-wrap: wrap; }
        .ucreate-actions .btn { height: 46px; border-radius: 12px; }
        .ucreate-actions .btn-secondary { background: #fff; border: 1px solid #d8e0e8; }

        .ucreate-info { padding: 16px; }
        .ucreate-info h3 { margin: 0 0 12px; font-size: 16px; font-weight: 800; }
        .ucreate-info ul { list-style: none; padding: 0; margin: 0; }
        .ucreate-info li { display: flex; justify-content: space-between; gap: 10px; padding: 10px 0; border-bottom: 1px solid #eef2f6; font-size: 13px; }
        .ucreate-info li:last-child { border-bottom: none; }
        .ucreate-info span { color: #6b7280; }

        @media (max-width: 1024px) { .ucreate-layout { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            .ucreate-grid { grid-template-columns: 1fr; }
            .ucreate-actions { flex-direction: column; }
            .ucreate-actions .btn { width: 100%; }
        }
    </style>
@endpush

@section('content')
    <div class="ucreate-page">
        <section class="ucreate-intro">
            <h1>Создание пользователя</h1>
            <p>Создайте аккаунт и назначьте роль. Новый пользователь создаётся активным, блокировка выполняется на странице редактирования.</p>
        </section>

        @if ($errors->any())
            <section class="card" style="margin-bottom: 16px;">
                <div class="ucreate-info">
                    <div class="alert-error">
                        <div class="alert-error-title">Ошибка</div>
                        <div>{{ $errors->first() }}</div>
                    </div>
                </div>
            </section>
        @endif

        <section class="ucreate-layout">
            <div>
                <div class="card">
                    <div class="ucreate-card-top">
                        <h2>Новый пользователь</h2>
                    </div>

                    <form class="ucreate-form" method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="ucreate-section">
                            <h3>Основная информация</h3>
                            <p>Личные и контактные данные пользователя.</p>

                            <div class="ucreate-grid">
                                <div class="ucreate-group">
                                    <label for="name">Имя</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}">
                                </div>

                                <div class="ucreate-group">
                                    <label for="login">Логин</label>
                                    <input id="login" type="text" name="login" value="{{ old('login') }}">
                                </div>

                                <div class="ucreate-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                                </div>

                                <div class="ucreate-group">
                                    <label for="phone">Телефон</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}">
                                </div>
                            </div>
                        </div>

                        <div class="ucreate-section">
                            <h3>Права доступа</h3>
                            <p>Роль определяет уровень доступа к разделам системы.</p>

                            <div class="ucreate-grid">
                                <div class="ucreate-group">
                                    <label for="role">Роль</label>
                                    <select id="role" name="role_id">
                                        <option value="">Выберите роль</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @selected((string)old('role_id') === (string)$role->id)>{{ $role->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="ucreate-group">
                                    <label>Статус аккаунта</label>
                                    <input type="text" value="Активен" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="ucreate-section">
                            <h3>Пароль</h3>
                            <p>Задайте пароль для нового пользователя.</p>

                            <div class="ucreate-grid">
                                <div class="ucreate-group">
                                    <label for="password">Пароль</label>
                                    <input id="password" type="password" name="password" placeholder="Введите пароль">
                                </div>
                                <div class="ucreate-group">
                                    <label for="password_confirmation">Подтверждение пароля</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Повторите пароль">
                                </div>
                            </div>
                        </div>

                        <div class="ucreate-actions">
                            <button type="submit" class="btn">Создать пользователя</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>

            <aside>
                <div class="card ucreate-info">
                    <h3>Информация</h3>
                    <ul>
                        <li><span>Статус:</span> Активен</li>
                        <li><span>Блокировка:</span> Через редактирование</li>
                        <li><span>Онлайн:</span> Определяется автоматически</li>
                    </ul>
                </div>
            </aside>
        </section>
    </div>
@endsection

