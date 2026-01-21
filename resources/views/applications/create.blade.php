@extends('layouts.base')

@section('content')
    <div class="card">
        <h2>Формирование заявки</h2>

        <form action="{{ route('applications.store') }}" method="POST" style="margin-top: 15px;">
            @csrf

            <div class="form-group">
                <label for="course_name">Наименование курса</label>
                <input type="text" id="course_name" name="course_name" required>
            </div>

            <div class="form-group">
                <label for="start_date">Желаемая дата начала обучения</label>
                <input type="date" id="start_date" name="start_date">
            </div>

            <div class="form-group">
                <label for="payment_method">Способ оплаты</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="cash">Наличными</option>
                    <option value="phone">Перевод по номеру телефона</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
@endsection


