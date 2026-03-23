@extends('layouts.app')

@section('title', 'Создание источника — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="card">
        <div class="card__header">Создание источника</div>
        <div class="card__body">
            <form method="POST" action="{{ route('admin.sources.store') }}">
                @csrf

                <div class="form-row">
                    <label for="name">Название</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}">
                </div>

                <div class="form-row">
                    <label for="type">Тип</label>
                    <input id="type" type="text" name="type" value="{{ old('type') }}">
                </div>

                <div class="form-row">
                    <label for="base_url">Базовый URL</label>
                    <input id="base_url" type="url" name="base_url" value="{{ old('base_url') }}">
                </div>

                <div class="form-row">
                    <label>
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                        Активен
                    </label>
                </div>

                <div class="form-row" style="margin-top: 24px;">
                    <button type="submit" class="btn">создать</button>
                </div>
            </form>
        </div>
    </section>
@endsection

