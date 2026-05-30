import { gsap } from 'gsap';
import { disableBodyScroll, enableBodyScroll } from './body-scroll-lock-facade';

(function () {
    var toggle   = document.querySelector('.js-site-nav-toggle');
    var nav      = document.querySelector('.js-mobile-nav');
    var shade    = document.querySelector('.js-mobile-nav-shade');
    var closeBtn = document.querySelector('.js-close-menu');
    var items    = nav ? Array.from(nav.querySelectorAll('.mobile-nav__item')) : [];

    if (!toggle || !nav) return;

    // Set items to hidden state ready for open animation
    gsap.set(items, { opacity: 0, x: 24 });

    function openMenu() {
        nav.classList.add('is-open');
        shade && shade.classList.add('is-open');
        toggle.classList.add('is-active');
        toggle.setAttribute('aria-expanded', 'true');
        nav.setAttribute('aria-hidden', 'false');
        disableBodyScroll(nav);

        gsap.fromTo(items,
            { opacity: 0, x: 24 },
            {
                opacity: 1,
                x: 0,
                duration: 0.5,
                stagger: 0.07,
                ease: 'power3.out',
                delay: 0.15,
            }
        );

        if (closeBtn) closeBtn.focus();
    }

    function closeMenu() {
        gsap.to(items, {
            opacity: 0,
            x: 16,
            duration: 0.2,
            stagger: { each: 0.04, from: 'end' },
            ease: 'power2.in',
            onComplete: function () {
                gsap.set(items, { x: 24 });
            },
        });

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
