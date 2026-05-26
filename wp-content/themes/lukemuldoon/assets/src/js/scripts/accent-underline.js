import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.querySelectorAll('.accent-underline').forEach(function (el) {
    var path = el.querySelector('.accent-underline__svg path');
    if (!path) return;

    var length = path.getTotalLength();

    gsap.set(path, {
        strokeDasharray: length,
        strokeDashoffset: length,
    });

    ScrollTrigger.create({
        trigger: el,
        start: 'top 85%',
        once: true,
        onEnter: function () {
            gsap.to(path, {
                strokeDashoffset: 0,
                duration: 0.9,
                ease: 'power2.inOut',
            });
        },
    });
});
