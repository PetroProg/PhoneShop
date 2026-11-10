document.addEventListener("DOMContentLoaded", () => {

    // --- 1. Восстановление скролла ---
    const scrollPosition = sessionStorage.getItem('sidebar_scroll');
    if (scrollPosition) {
        // Задержка в один кадр, чтобы страница успела отрисоваться
        requestAnimationFrame(() => {
            window.scrollTo({
                top: parseInt(scrollPosition, 10),
                behavior: 'auto'
            });
            sessionStorage.removeItem('sidebar_scroll');
        });
    }

    // --- 2. Сохранение скролла при добавлении в корзину ---
    document.querySelectorAll('.js-add-to-cart').forEach(button => {
        button.addEventListener('click', () => {
            sessionStorage.setItem('sidebar_scroll', window.scrollY);
        });
    });

    // --- 3. Логика Sidebar (мобильное меню) ---
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('fixed');
            sidebar.classList.toggle('inset-0');
            sidebar.classList.toggle('z-50');
        });
    }

    // --- 4. Кнопка наверх ---
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");
    if (scrollToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                scrollToTopBtn.classList.remove("opacity-0", "pointer-events-none");
                scrollToTopBtn.classList.add("opacity-100");
            } else {
                scrollToTopBtn.classList.add("opacity-0", "pointer-events-none");
                scrollToTopBtn.classList.remove("opacity-100");
            }
        });
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --- 5. Логика переключения форм ---
    const formRegistry = document.getElementById("form-registry");
    const formLogin = document.getElementById("form-login");
    const changeToRegistry = document.getElementById("change-to-registry");
    const changeToLogin = document.getElementById("change-to-login");

    if (changeToRegistry && changeToLogin && formRegistry && formLogin) {
        changeToRegistry.addEventListener('click', () => {
            sessionStorage.setItem('activeForm', 'registry');
            formLogin.classList.add('hidden');
            formRegistry.classList.remove('hidden');
        });
        changeToLogin.addEventListener('click', () => {
            sessionStorage.setItem('activeForm', 'login');
            formRegistry.classList.add('hidden');
            formLogin.classList.remove('hidden');
        });
    }
    window.addEventListener('DOMContentLoaded', () => {
        const getform = sessionStorage.getItem('activeForm');
        if (getform === 'registry') {
            formLogin.classList.add('hidden');
            formRegistry.classList.remove('hidden');
            formRegistry.classList.add('flex')
        } else if (getform === 'login') {
            formRegistry.classList.add('hidden');
            formLogin.classList.remove('hidden');
            formLogin.classList.add('flex');
        }
    });

    const range = document.getElementById('priceRange');
    const display = document.getElementById('rangeValue');
    range.addEventListener('input', () => {
        display.innerText = range.value;
    });
});

