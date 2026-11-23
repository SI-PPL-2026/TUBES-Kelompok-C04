import './bootstrap';

const menuButton = document.querySelector('.menu-toggle');
const sidebar = document.querySelector('.sidebar');

menuButton?.addEventListener('click', () => {
    const isOpen = sidebar?.classList.toggle('open') ?? false;
    menuButton.setAttribute('aria-expanded', String(isOpen));
});

document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element) || !sidebar?.classList.contains('open')) {
        return;
    }

    if (!sidebar.contains(event.target) && !menuButton?.contains(event.target)) {
        sidebar.classList.remove('open');
        menuButton?.setAttribute('aria-expanded', 'false');
    }
});
