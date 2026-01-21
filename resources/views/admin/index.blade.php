@extends('layouts.base')

@section('content')
    <div class="card">
        <h2>Админ-панель: заявки пользователей</h2>

        @if($applications->isEmpty())
            <p style="margin-top: 10px;">Заявок пока нет.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Пользователь</th>
                    <th>Курс</th>
                    <th>Дата начала</th>
                    <th>Способ оплаты</th>
                    <th>Статус</th>
                    <th>Изменить статус</th>
                </tr>
                </thead>
                <tbody>
                @foreach($applications as $index => $application)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $application->user ? $application->user->full_name : '—' }}</td>
                        <td>{{ $application->course_name }}</td>
                        <td>{{ $application->start_date }}</td>
                        <td>
                            @if($application->payment_method === 'cash')
                                Наличными
                            @else
                                Перевод по номеру телефона
                            @endif
                        </td>
                        <td>{{ $application->status }}</td>
                        <td>
                            <form action="{{ route('admin.applications.status', $application) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <select name="status">
                                    <option value="Новая" @if($application->status === 'Новая') selected @endif>Новая</option>
                                    <option value="Идет обучение" @if($application->status === 'Идет обучение') selected @endif>Идет обучение</option>
                                    <option value="Обучение завершено" @if($application->status === 'Обучение завершено') selected @endif>Обучение завершено</option>
                                </select>
                                <button type="submit" class="btn btn-primary" style="margin-top: 4px;">Сохранить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection


