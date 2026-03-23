@extends('layouts.app')

@section('title', 'Профиль — SanStar Аналитика')

@section('content')
    <section class="card">
        <div class="card__header">Профиль пользователя</div>
        <div class="card__body">
            <div class="stats-list">
                <div class="stats-row">
                    <span>Имя</span>
                    <strong>{{ auth()->user()->name ?? 'Имя пользователя' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Логин</span>
                    <strong>{{ auth()->user()->login ?? 'login' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Email</span>
                    <strong>{{ auth()->user()->email ?? 'user@example.com' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Телефон</span>
                    <strong>{{ auth()->user()->phone ?? '+7 900 000 00 00' }}</strong>
                </div>
                <div class="stats-row">
                    <span>Роль</span>
                    <strong>{{ optional(auth()->user()->role)->title ?? 'роль' }}</strong>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <button class="btn btn--small" type="button">редактировать профиль</button>
                <button class="btn btn--small" type="button">сменить пароль</button>
            </div>
        </div>
    </section>
@endsection

