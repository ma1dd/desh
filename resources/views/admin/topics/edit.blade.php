@extends('layouts.app')

@section('title', 'Редактирование темы — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="card">
        <div class="card__header">Редактирование темы</div>
        <div class="card__body">
            <form method="POST" action="{{ route('admin.topics.update', $topic) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <label for="name">Название</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $topic->name) }}">
                </div>

                <div class="form-row">
                    <label for="keywords">Ключевые слова</label>
                    <textarea id="keywords" name="keywords" rows="3">{{ old('keywords', $topic->keywords) }}</textarea>
                </div>

                <div class="form-row" style="margin-top: 24px;">
                    <button type="submit" class="btn">сохранить</button>
                </div>
            </form>
        </div>
    </section>
@endsection

