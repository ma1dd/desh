<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корочки.есть</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Простые самописные стили без фреймворков */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .page-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 10px 20px;
            margin-bottom: 20px;
        }
        header h1 {
            margin: 0;
            font-size: 22px;
        }
        nav a {
            color: #ecf0f1;
            text-decoration: none;
            margin-right: 15px;
            font-size: 14px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 12px;
        }
        label {
            display: block;
            margin-bottom: 4px;
            font-size: 14px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 3px;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #3498db;
            color: #fff;
        }
        .btn-secondary {
            background-color: #95a5a6;
            color: #fff;
        }
        .btn-danger {
            background-color: #e74c3c;
            color: #fff;
        }
        .link {
            font-size: 13px;
        }
        .link a {
            color: #3498db;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
        }
        .status-new {
            background-color: #f1c40f;
            color: #000;
        }
        .status-progress {
            background-color: #3498db;
            color: #fff;
        }
        .status-done {
            background-color: #2ecc71;
            color: #fff;
        }
        .message-error {
            color: #e74c3c;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .slider {
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .slider-track {
            display: flex;
            transition: transform 0.5s ease;
        }
        .slide {
            min-width: 100%;
            box-sizing: border-box;
            padding: 16px;
            background: linear-gradient(90deg, #3498db, #9b59b6);
            color: #fff;
            font-size: 16px;
        }
        .slider-controls {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            padding: 0 10px;
        }
        .slider-btn {
            background-color: rgba(0,0,0,0.4);
            border: none;
            color: #fff;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <header>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Портал «Корочки.есть»</h1>
            <nav>
                @auth
                    <a href="{{ route('applications.index') }}">Мои заявки</a>
                    <a href="{{ route('applications.create') }}">Новая заявка</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.panel') }}">Админ-панель</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="font-size: 12px;">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login.form') }}">Вход</a>
                    <a href="{{ route('register.form') }}">Регистрация</a>
                @endauth
            </nav>
        </div>
    </header>

    @yield('content')
</div>

<script>
    // Простой слайдер на чистом JS
    (function () {
        var slider = document.querySelector('.slider');
        if (!slider) return;

        var track = slider.querySelector('.slider-track');
        var slides = slider.querySelectorAll('.slide');
        var index = 0;

        function showSlide(i) {
            index = (i + slides.length) % slides.length;
            track.style.transform = 'translateX(' + (-index * 100) + '%)';
        }

        var prevBtn = slider.querySelector('.slider-prev');
        var nextBtn = slider.querySelector('.slider-next');

        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', function () {
                showSlide(index - 1);
            });
            nextBtn.addEventListener('click', function () {
                showSlide(index + 1);
            });
        }

        // Автопрокрутка
        setInterval(function () {
            showSlide(index + 1);
        }, 5000);
    })();
</script>
</body>
</html>


