@extends('layouts.base')

@section('content')
    <div class="card">
        <h2>Регистрация</h2>

        <div class="slider" style="margin-top: 15px;">
            <div class="slider-track">
                <div class="slide">1. Зарегистрируйтесь на портале и получите доступ к онлайн-курсам.</div>
                <div class="slide">2. Заполните заявку на обучение и выберите удобный способ оплаты.</div>
                <div class="slide">3. После прохождения курса оставьте отзыв о качестве образовательных услуг.</div>
            </div>
            <div class="slider-controls">
                <button type="button" class="slider-btn slider-prev">&lt;</button>
                <button type="button" class="slider-btn slider-next">&gt;</button>
            </div>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="login">Логин (латиница и цифры, не менее 6 символов)</label>
                <input type="text" id="login" name="login" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль (минимум 8 символов)</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="full_name">ФИО (кириллица и пробелы)</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="phone">Телефон (формат: 8(XXX)XXX-XX-XX)</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" id="email" name="email" required>
            </div>

            <button type="submit" class="btn btn-primary">Создать пользователя</button>
        </form>

        <p class="link" style="margin-top: 10px;">
            Уже зарегистрированы?
            <a href="{{ route('login.form') }}">Вход</a>
        </p>
    </div>
@endsection


