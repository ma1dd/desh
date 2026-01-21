@extends('layouts.base')

@section('content')
    <div class="card">
        <h2>Мои заявки</h2>

        @if($applications->isEmpty())
            <p style="margin-top: 10px;">У вас пока нет заявок. <a href="{{ route('applications.create') }}">Создать заявку</a></p>
        @else
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Курс</th>
                    <th>Дата начала</th>
                    <th>Способ оплаты</th>
                    <th>Статус</th>
                    <th>Отзыв</th>
                </tr>
                </thead>
                <tbody>
                @foreach($applications as $index => $application)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $application->course_name }}</td>
                        <td>{{ $application->start_date }}</td>
                        <td>
                            @if($application->payment_method === 'cash')
                                Наличными
                            @else
                                Перевод по номеру телефона
                            @endif
                        </td>
                        <td>
                            @php
                                $class = 'status-new';
                                if ($application->status === 'Идет обучение') {
                                    $class = 'status-progress';
                                } elseif ($application->status === 'Обучение завершено') {
                                    $class = 'status-done';
                                }
                            @endphp
                            <span class="status-badge {{ $class }}">{{ $application->status }}</span>
                        </td>
                        <td>
                            <div>
                                @foreach($application->reviews as $review)
                                    <div style="font-size: 13px; margin-bottom: 4px;">{{ $review->content }}</div>
                                @endforeach
                            </div>
                            <form action="{{ route('applications.reviews.store', $application) }}" method="POST">
                                @csrf
                                <textarea name="content" rows="2" placeholder="Оставьте отзыв..." required></textarea>
                                <button type="submit" class="btn btn-secondary" style="margin-top: 4px;">Отправить отзыв</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection


