import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Prevent browser scroll restoration from causing elements to stay invisible.
// Skip the scroll reset if the URL has a hash — we're landing on a specific anchor.
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
if (!window.location.hash) {
    window.scrollTo(0, 0);
}

const SVG_NS    = 'http://www.w3.org/2000/svg';
const SVG_PATH  = 'M3,9 C50,11 90,4 150,7 C200,10 250,4 297,7';
const SVG_COLOR = '#9FE000';

// ── Accent underline ──────────────────────────────────────────────────────────

function injectUnderlineSVG(el) {
    if (el.classList.contains('has-inline-svg')) {
        var existing = el.querySelector('.accent-underline__svg path');
        if (!existing) return null;
        var existingLen = existing.getTotalLength();
        existing.setAttribute('stroke-dasharray', existingLen);
        existing.setAttribute('stroke-dashoffset', existingLen);
        return existing;
    }

    var svg  = document.createElementNS(SVG_NS, 'svg');
    var path = document.createElementNS(SVG_NS, 'path');

    svg.setAttribute('viewBox', '0 0 300 14');
    svg.setAttribute('preserveAspectRatio', 'none');
    svg.setAttribute('aria-hidden', 'true');
    svg.classList.add('accent-underline__svg');
    svg.style.visibility = 'hidden';

    path.setAttribute('d', SVG_PATH);
    path.setAttribute('stroke', SVG_COLOR);
    path.setAttribute('stroke-width', '4');
    path.setAttribute('fill', 'none');
    path.setAttribute('stroke-linecap', 'round');

    svg.appendChild(path);
    el.appendChild(svg);
    el.classList.add('has-inline-svg');

    var len = path.getTotalLength();
    path.setAttribute('stroke-dasharray', len);
    path.setAttribute('stroke-dashoffset', len);
    svg.style.visibility = '';

    return path;
}

// ── Countup (prefix + suffix support) ────────────────────────────────────────

function setupCountup(el) {
    var raw    = el.textContent.trim();
    var prefix = raw.match(/^[^0-9]*/)[0];
    var suffix = raw.match(/[^0-9]*$/)[0];
    var number = parseFloat(raw.replace(/[^0-9.]/g, ''));
    if (isNaN(number)) return null;
    el.textContent = prefix + '0' + suffix;
    return { el: el, raw: raw, prefix: prefix, suffix: suffix, number: number, proxy: { val: 0 } };
}

function buildCountupTween(data, duration) {
    duration = duration || 1.8;
    return gsap.to(data.proxy, {
        val: data.number,
        duration: duration,
        ease: 'power3.out',
        onUpdate: function () {
            var v = Number.isInteger(data.number)
                ? Math.round(data.proxy.val)
                : data.proxy.val.toFixed(1);
            data.el.textContent = data.prefix + v + data.suffix;
        },
        onComplete: function () {
            data.el.textContent = data.raw;
        },
    });
}

// ── Bounce chart ──────────────────────────────────────────────────────────────

function setupBounceChart(container) {
    var chart = container.querySelector('.bounce-chart');
    if (!chart) return null;

    var rows = Array.from(chart.querySelectorAll('.bounce-chart__row'));

    // Capture target bar values and reset to 0
    var barTargets = rows.map(function (row) {
        var target = parseFloat(row.style.getPropertyValue('--bar')) || 0;
        row.style.setProperty('--bar', 0);
        return target;
    });

    // Set up countup data for labels and values
    var labelData = rows.map(function (row) {
        return setupCountup(row.querySelector('.bounce-chart__label'));
    }).filter(Boolean);

    var valueData = rows.map(function (row) {
        return setupCountup(row.querySelector('.bounce-chart__value'));
    }).filter(Boolean);

    return { rows: rows, barTargets: barTargets, labelData: labelData, valueData: valueData };
}

function addBounceChartToTimeline(tl, chartData, startPosition) {
    var stagger = 0.12;

    tl.addLabel('bounceStart', startPosition);

    // Each row: bar + label + value all start together, staggered per row
    chartData.rows.forEach(function (row, i) {
        var t = 'bounceStart+=' + (i * stagger);
        tl.to(row, { '--bar': chartData.barTargets[i], duration: 1.1, ease: 'power2.out' }, t);
        if (chartData.labelData[i]) tl.add(buildCountupTween(chartData.labelData[i], 1.1), t);
        if (chartData.valueData[i]) tl.add(buildCountupTween(chartData.valueData[i], 1.1), t);
    });
}

// ── Score rings ───────────────────────────────────────────────────────────────

function setupScoreValues(container) {
    var items = Array.from(container.querySelectorAll('.score-list__value'));
    if (!items.length) return null;

    var targets = items.map(function (el) {
        var score = parseFloat(el.style.getPropertyValue('--score')) || 0;
        el.style.setProperty('--score', 0);
        return score;
    });

    var countups = items.map(function (el) {
        return setupCountup(el);
    });

    return { items: items, targets: targets, countups: countups };
}

function addScoreValuesToTimeline(tl, data, startPosition) {
    var stagger = 0.1;
    tl.addLabel('scoresStart', startPosition);

    data.items.forEach(function (el, i) {
        var t = 'scoresStart+=' + (i * stagger);
        tl.to(el, { '--score': data.targets[i], duration: 1.2, ease: 'power2.out' }, t);
        if (data.countups[i]) {
            tl.add(buildCountupTween(data.countups[i], 1.2), t);
        }
    });
}

// ── js-reveal containers ──────────────────────────────────────────────────────

document.querySelectorAll('.js-reveal').forEach(function (container) {
    var children = Array.from(container.children);

    var underlinePaths = Array.from(container.querySelectorAll('.accent-underline'))
        .map(injectUnderlineSVG)
        .filter(Boolean);

    var statCountups = Array.from(container.querySelectorAll('.stat-item__main'))
        .map(setupCountup)
        .filter(Boolean);

    var bounceChart  = setupBounceChart(container);
    var scoreValues  = setupScoreValues(container);

    var start = container.dataset.revealStart || 'top 80%';

    var tl = gsap.timeline({
        scrollTrigger: {
            trigger: container,
            start: start,
            once: true,
        },
    });

    // 1. Stagger children in
    tl.from(children, {
        y: 50,
        opacity: 0,
        duration: 0.55,
        stagger: 0.1,
        ease: 'power2.out',
        clearProps: 'all',
    });

    // 2. Draw underlines — overlap slightly with end of stagger
    underlinePaths.forEach(function (path, i) {
        tl.to(path, {
            strokeDashoffset: 0,
            duration: 0.9,
            ease: 'power2.inOut',
        }, i === 0 ? '>-0.3' : '<');
    });

    // 3. Stat countups — overlap slightly with end of stagger
    statCountups.forEach(function (data, i) {
        tl.add(buildCountupTween(data), i === 0 ? '>-0.3' : '<');
    });

    // 4. Bounce chart — bars grow + values count up after stagger
    if (bounceChart) {
        addBounceChartToTimeline(tl, bounceChart, '>-0.3');
    }

    // 5. Score rings — fill conic-gradient and count up numbers
    if (scoreValues) {
        addScoreValuesToTimeline(tl, scoreValues, '>-0.3');
    }

});

// ── Standalone fallbacks (not inside .js-reveal) ──────────────────────────────

document.querySelectorAll('.accent-underline').forEach(function (el) {
    if (el.closest('.js-reveal')) return;

    var path = injectUnderlineSVG(el);
    if (!path) return;

    ScrollTrigger.create({
        trigger: el,
        start: 'top 85%',
        once: true,
        onEnter: function () {
            gsap.to(path, { strokeDashoffset: 0, duration: 0.9, ease: 'power2.inOut' });
        },
    });
});

document.querySelectorAll('.stat-item__main').forEach(function (el) {
    if (el.closest('.js-reveal')) return;

    var data = setupCountup(el);
    if (!data) return;

    ScrollTrigger.create({
        trigger: el,
        start: 'top 85%',
        once: true,
        onEnter: function () { buildCountupTween(data); },
    });
});

// ── Hero score ring ───────────────────────────────────────────────────────────

(function () {
    var ringCircle = document.querySelector('.hero__ring-circle');
    var ringText   = document.querySelector('.hero__ring-text');
    if (!ringCircle || !ringText) return;

    var r             = parseFloat(ringCircle.getAttribute('r')) || 200;
    var circumference = 2 * Math.PI * r;
    var ringCountup   = setupCountup(ringText);

    gsap.set(ringCircle, { strokeDasharray: circumference, strokeDashoffset: circumference });

    var tl = gsap.timeline({ delay: 0.6 });

    tl.to(ringCircle, { strokeDashoffset: 0, duration: 2, ease: 'power2.inOut' });

    if (ringCountup) {
        tl.add(buildCountupTween(ringCountup, 2), '<');
    }
}());

// ── Standalone fallbacks (not inside .js-reveal) ──────────────────────────────

document.querySelectorAll('.bounce-chart').forEach(function (chart) {
    if (chart.closest('.js-reveal')) return;

    var container = chart.parentElement;
    var chartData = setupBounceChart(container);
    if (!chartData) return;

    ScrollTrigger.create({
        trigger: chart,
        start: 'top 85%',
        once: true,
        onEnter: function () {
            var tl = gsap.timeline();
            addBounceChartToTimeline(tl, chartData, '>');
        },
    });
});
