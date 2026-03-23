@extends('layouts.app')

@section('title', 'Создание темы — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="card">
        <div class="card__header">Создание темы</div>
        <div class="card__body">
            <form method="POST" action="{{ route('admin.topics.store') }}">
                @csrf

                <div class="form-row">
                    <label for="name">Название</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}">
                </div>

                <div class="form-row">
                    <label for="keywords">Ключевые слова</label>
                    <textarea id="keywords" name="keywords" rows="3" placeholder="через запятую">{{ old('keywords') }}</textarea>
                </div>

                <div class="form-row" style="margin-top: 24px;">
                    <button type="submit" class="btn">создать</button>
                </div>
            </form>
        </div>
    </section>
@endsection

