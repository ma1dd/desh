// Модуль для работы с корзиной
const Cart = {
    items: [],
    total: 0,

    add(item) {
        this.items.push(item);
        this.updateTotal();
        this.updateUI();
    },

    remove(id) {
        this.items = this.items.filter(item => item.id !== id);
        this.updateTotal();
        this.updateUI();
    },

    updateTotal() {
        this.total = this.items.reduce((sum, item) => sum + item.price, 0);
    },

    updateUI() {
        const counter = document.querySelector('.header__cart .header__counter');
        const total = document.querySelector('.header__cart .header__action-text');
        if (counter) counter.textContent = this.items.length;
        if (total) total.textContent = `${this.total} руб.`;
    }
};

// Модуль для работы со сравнением
const Compare = {
    items: [],

    add(item) {
        if (!this.items.find(i => i.id === item.id)) {
            this.items.push(item);
            this.updateUI();
        }
    },

    remove(id) {
        this.items = this.items.filter(item => item.id !== id);
        this.updateUI();
    },

    updateUI() {
        const counter = document.querySelector('.header__compare .header__counter');
        if (counter) counter.textContent = this.items.length;
    }
};

// Модуль для работы с модальными окнами
const Modal = {
    open(content) {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal__overlay"></div>
            <div class="modal__content">
                <button class="modal__close">&times;</button>
                ${content}
            </div>
        `;

        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        modal.querySelector('.modal__close').addEventListener('click', () => this.close(modal));
        modal.querySelector('.modal__overlay').addEventListener('click', () => this.close(modal));
    },

    close(modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
};

// Инициализация при загрузке документа
document.addEventListener('DOMContentLoaded', () => {
    // Активация текущего пункта меню
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.header__link');
    
    menuLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('header__link--active');
        }
    });

    // Мобильное меню (если есть)
    const mobileMenuButton = document.querySelector('.header__mobile-menu');
    const mobileMenu = document.querySelector('.header__nav');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('header__nav--active');
            mobileMenuButton.classList.toggle('header__mobile-menu--active');
        });
    }

    // Плавная прокрутка для якорных ссылок
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Обработка кнопки "Заказать звонок"
    const callbackBtn = document.querySelector('.button--callback');
    if (callbackBtn) {
        callbackBtn.addEventListener('click', () => {
            Modal.open(`
                <form class="callback-form">
                    <h3 class="callback-form__title">Заказать звонок</h3>
                    <input type="text" class="callback-form__input" placeholder="Ваше имя" required>
                    <input type="tel" class="callback-form__input" placeholder="Ваш телефон" required>
                    <button type="submit" class="button button--primary">Отправить</button>
                </form>
            `);
        });
    }

    // Обработка кнопок добавления в корзину
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-add-to-cart')) {
            const productCard = e.target.closest('.product-card');
            if (productCard) {
                const item = {
                    id: productCard.dataset.id,
                    name: productCard.querySelector('.product-card__title').textContent,
                    price: parseFloat(productCard.querySelector('.price').textContent),
                };
                Cart.add(item);
            }
        }
    });

    // Обработка кнопок добавления к сравнению
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-add-to-compare')) {
            const productCard = e.target.closest('.product-card');
            if (productCard) {
                const item = {
                    id: productCard.dataset.id,
                    name: productCard.querySelector('.product-card__title').textContent,
                };
                Compare.add(item);
            }
        }
    });

    // Инициализация слайдера новостей
    const newsSlider = document.querySelector('.news__slider');
    if (newsSlider) {
        let currentSlide = 0;
        const slides = newsSlider.querySelectorAll('.news__slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(slide => slide.style.display = 'none');
            slides[index].style.display = 'block';
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        // Автоматическая прокрутка слайдов
        setInterval(nextSlide, 5000);

        // Добавление кнопок навигации
        const prevButton = document.createElement('button');
        prevButton.className = 'news__nav news__nav--prev';
        prevButton.innerHTML = '←';
        prevButton.addEventListener('click', prevSlide);

        const nextButton = document.createElement('button');
        nextButton.className = 'news__nav news__nav--next';
        nextButton.innerHTML = '→';
        nextButton.addEventListener('click', nextSlide);

        newsSlider.appendChild(prevButton);
        newsSlider.appendChild(nextButton);

        // Показываем первый слайд
        showSlide(0);
    }
}); 