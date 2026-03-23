# SanStar Website

Современная версия сайта мебельной фабрики SanStar, разработанная с использованием современных веб-технологий.

## Технологии

- HTML5
- SASS/SCSS
- JavaScript (Vanilla)
- БЭМ методология
- Flexbox/Grid для верстки
- Адаптивный дизайн

## Структура проекта

```
sait/
├── src/
│   ├── js/
│   │   ├── main.js
│   │   └── exchange.js
│   └── styles/
│       ├── blocks/
│       ├── layout/
│       └── utils/
├── styles/
│   └── main.css
└── pages/
    ├── index.html
    ├── about.html
    ├── exchange.html
    └── where-to-buy.html
```

## Установка и запуск

1. Клонируйте репозиторий:
```bash
git clone https://github.com/ma1dd/san-star.git
```

2. Установите зависимости:
```bash
npm install
```

3. Запустите проект:
```bash
npm start
```

Это запустит:
- Компиляцию SASS в режиме отслеживания изменений
- Локальный сервер с автоматической перезагрузкой

## Разработка

- `src/styles/main.scss` - главный файл стилей
- `src/js/main.js` - основной JavaScript файл
- `src/js/exchange.js` - JavaScript для страницы обмена и возврата

## Сборка

Для сборки проекта выполните:
```bash
npm run build
```

## Лицензия

MIT 