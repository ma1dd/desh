@extends('layouts.app')

@section('title', 'Вход в систему — SanStar Аналитика')

@push('styles')
    <style>
        .auth-page { padding: 40px 0; display: flex; justify-content: center; }
        .auth-card { max-width: 420px; width: 100%; }
        .auth-header { padding: 20px 24px 12px; border-bottom: 1px solid #e5e7eb; }
        .auth-title { margin: 0 0 4px; font-size: 22px; font-weight: 700; }
        .auth-sub { margin: 0; font-size: 13px; color: #6b7280; }
        .auth-body { padding: 20px 24px 22px; }

        .alert-error {
            margin-bottom: 16px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
        }
        .alert-error-title { font-weight: 700; margin-bottom: 2px; }

        .auth-form-group { margin-bottom: 14px; display: flex; flex-direction: column; gap: 6px; }
        .auth-form-group label { font-size: 13px; font-weight: 600; }
        .auth-input {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }
        .auth-input:focus {
            outline: none;
            border-color: #355c7d;
            box-shadow: 0 0 0 3px rgba(53, 92, 125, 0.12);
        }
        .auth-submit { margin-top: 18px; width: 100%; height: 46px; border-radius: 12px; }
    </style>
@endpush

@section('content')
    <div class="auth-page">
        <section class="card auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Вход в систему</h1>
                <p class="auth-sub">Введите логин/email и пароль для доступа к аналитике.</p>
            </div>

            <div class="auth-body">
                @if ($errors->any())
                    <div class="alert-error">
                        <div class="alert-error-title">Ошибка авторизации</div>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf
                    <div class="auth-form-group">
                        <label for="login">Логин или email</label>
                        <input id="login" type="text" name="login" class="auth-input" value="{{ old('login') }}" placeholder="Введите логин или email">
                    </div>

                    <div class="auth-form-group">
                        <label for="password">Пароль</label>
                        <input id="password" type="password" name="password" class="auth-input" placeholder="Введите пароль">
                    </div>

                    <button type="submit" class="btn auth-submit">войти</button>
                </form>
            </div>
        </section>
    </div>
@endsection

