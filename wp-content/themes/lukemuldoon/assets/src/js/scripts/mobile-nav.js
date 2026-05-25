import { disableBodyScroll, enableBodyScroll } from './body-scroll-lock-facade';

(function () {
    var toggle   = document.querySelector('.js-site-nav-toggle');
    var nav      = document.querySelector('.js-mobile-nav');
    var shade    = document.querySelector('.js-mobile-nav-shade');
    var closeBtn = document.querySelector('.js-close-menu');

    if (!toggle || !nav) return;

    function openMenu() {
        nav.classList.add('is-open');
        shade && shade.classList.add('is-open');
        toggle.classList.add('is-active');
        toggle.setAttribute('aria-expanded', 'true');
        nav.setAttribute('aria-hidden', 'false');
        disableBodyScroll(nav);
        if (closeBtn) closeBtn.focus();
    }

    function closeMenu() {
        nav.classList.remove('is-open');
        shade && shade.classList.remove('is-open');
        toggle.classList.remove('is-active');
        toggle.setAttribute('aria-expanded', 'false');
        nav.setAttribute('aria-hidden', 'true');
        enableBodyScroll(nav);
        toggle.focus();
    }

    toggle.addEventListener('click', openMenu);

    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (shade)    shade.addEventListener('click', closeMenu);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && nav.classList.contains('is-open')) {
            closeMenu();
        }
    });

    nav.querySelectorAll('.mobile-nav__item').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });
}());
