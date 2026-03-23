@extends('layouts.app')

@section('title', 'Создание категории — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="card">
        <div class="card__header">Создание категории</div>
        <div class="card__body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="form-row">
                    <label for="name">Название</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}">
                </div>

                <div class="form-row">
                    <label for="parent_id">Родительская категория</label>
                    <select id="parent_id" name="parent_id">
                        <option value="">— нет —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label for="description">Описание</label>
                    <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-row" style="margin-top: 24px;">
                    <button type="submit" class="btn">создать</button>
                </div>
            </form>
        </div>
    </section>
@endsection

