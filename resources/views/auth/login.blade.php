@extends('layouts.base')

@section('content')
    <div class="card">
        <h2>Авторизация</h2>

        @if(session('error'))
            <div class="message-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Войти</button>
        </form>

        <p class="link" style="margin-top: 10px;">
            Еще не зарегистрированы?
            <a href="{{ route('register.form') }}">Регистрация</a>
        </p>
    </div>
@endsection


