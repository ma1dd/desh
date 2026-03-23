import './bootstrap';
import './dashboard';

document.addEventListener('click', (event) => {
    const target = event.target;

    // клик по кнопке меню
    if (target instanceof HTMLElement && target.closest('.table-menu__toggle')) {
        const menu = target.closest('.table-menu');
        if (!menu) return;

        // закрываем другие меню
        document.querySelectorAll('.table-menu.table-menu--open').forEach((el) => {
            if (el !== menu) el.classList.remove('table-menu--open');
        });

        menu.classList.toggle('table-menu--open');
        return;
    }

    // клик вне меню — закрыть все
    if (!(target instanceof HTMLElement) || !target.closest('.table-menu')) {
        document.querySelectorAll('.table-menu.table-menu--open').forEach((el) => {
            el.classList.remove('table-menu--open');
        });
    }
});
