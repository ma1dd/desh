@extends('layouts.app')

@section('title', 'Пользователи — Админ-панель — SanStar Аналитика')

@section('content')
    <section class="sessions">
        <div class="sessions__top">
            <h2 class="sessions__title">Пользователи</h2>

            <div class="sessions__controls">
                <form class="search" method="GET" action="{{ route('admin.users.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="поиск...">
                    <button type="submit" aria-label="Поиск">
                        <svg viewBox="0 0 24 24">
                            <path d="M10 2a8 8 0 1 0 5.293 14l4.353 4.354 1.414-1.414-4.354-4.353A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/>
                        </svg>
                    </button>
                </form>
                <a href="{{ route('admin.users.create') }}" class="btn">добавить пользователя</a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Имя</th>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Роль</th>
                    <th>Статус</th>
                    <th style="width: 1%;">действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->login }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '—' }}</td>
                        <td>{{ $user->role->title ?? $user->role->name ?? '—' }}</td>
                        <td style="white-space: nowrap;">
                            @if(!$user->is_active)
                                Заблокирован
                            @else
                                Активен · {{ $user->isOnline() ? 'в сети' : 'не в сети' }}
                            @endif
                        </td>
                        <td class="more-cell">
                            <div style="display:flex; gap: 8px; align-items:center; justify-content:flex-end; flex-wrap:nowrap;">
                                <a class="btn btn--small" href="{{ route('admin.users.edit', $user) }}">изменить</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin:0;" data-confirm="Удалить пользователя?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn--small" type="submit">удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">Пользователей не найдено.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $users->links() }}
        </div>
    </section>
@endsection

