@extends('layouts.app')

@section('title', 'Создание товара — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="card">
        <div class="card__header">Создание товара</div>
        <div class="card__body">
            <form method="POST" action="{{ route('admin.products.store') }}">
                @csrf

                <div class="form-row">
                    <label for="name">Название</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}">
                </div>

                <div class="form-row">
                    <label for="category_id">Категория</label>
                    <select id="category_id" name="category_id">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <label for="sku">Артикул</label>
                    <input id="sku" type="text" name="sku" value="{{ old('sku') }}">
                </div>

                <div class="form-row">
                    <label for="price">Цена</label>
                    <input id="price" type="number" step="0.01" name="price" value="{{ old('price') }}">
                </div>

                <div class="form-row">
                    <label for="description">Описание</label>
                    <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
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

