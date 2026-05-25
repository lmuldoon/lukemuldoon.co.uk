import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const PATH_D  = 'M3,9 C50,11 90,4 150,7 C200,10 250,4 297,7';
const STROKE  = '#9FE000';

document.querySelectorAll('.accent-underline').forEach(function (el) {
    var ns   = 'http://www.w3.org/2000/svg';
    var svg  = document.createElementNS(ns, 'svg');
    var path = document.createElementNS(ns, 'path');

    svg.setAttribute('viewBox', '0 0 300 14');
    svg.setAttribute('preserveAspectRatio', 'none');
    svg.setAttribute('aria-hidden', 'true');
    svg.classList.add('accent-underline__svg');

    path.setAttribute('d', PATH_D);
    path.setAttribute('stroke', STROKE);
    path.setAttribute('stroke-width', '4');
    path.setAttribute('fill', 'none');
    path.setAttribute('stroke-linecap', 'round');

    svg.appendChild(path);
    el.appendChild(svg);

    // Mark so CSS hides the ::after pseudo-element
    el.classList.add('has-inline-svg');

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
