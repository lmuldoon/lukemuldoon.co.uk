import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.querySelectorAll('.stat-item__main').forEach(function (el) {
    var raw    = el.textContent.trim();
    var prefix = raw.match(/^[^0-9]*/)[0];   // e.g. "<"
    var number = parseFloat(raw.replace(/[^0-9.]/g, ''));

    if (isNaN(number)) return;

    // Set to zero immediately so there's no flash of the final value.
    el.textContent = prefix + '0';

    var proxy = { val: 0 };

    gsap.to(proxy, {
        val: number,
        duration: 1.8,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: el,
            start: 'top 85%',
            once: true,
        },
        onUpdate: function () {
            var display = Number.isInteger(number)
                ? Math.round(proxy.val)
                : proxy.val.toFixed(1);
            el.textContent = prefix + display;
        },
        onComplete: function () {
            el.textContent = raw; // restore exact original value
        },
    });
});
