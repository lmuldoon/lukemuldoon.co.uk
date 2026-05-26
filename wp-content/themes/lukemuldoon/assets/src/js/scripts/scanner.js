(function () {
    'use strict';

    const form  = document.querySelector('.scanner');
    const modal = document.getElementById('scanner-modal');
    if (!form || !modal) return;

    const backdrop  = modal.querySelector('.scanner-modal__backdrop');
    const loading   = modal.querySelector('.scanner-modal__loading');
    const results   = modal.querySelector('.scanner-modal__results');
    const urlSpan   = modal.querySelector('.scanner-modal__url');
    const siteName  = modal.querySelector('.scanner-modal__site-name');
    const closeBtn  = modal.querySelector('.scanner-modal__close');
    const ctaNote   = modal.querySelector('.scanner-modal__cta-note');
    const ctaLink   = modal.querySelector('.scanner-modal__cta');
    const scoreEls  = modal.querySelectorAll('[data-category]');

    // ── Open / close ──────────────────────────────────────────────────────────

    function openModal() {
        modal.removeAttribute('hidden');
        document.body.style.overflow = 'hidden';
        // Defer to next frame so the display change registers before the
        // CSS transition fires.
        requestAnimationFrame(function () {
            modal.classList.add('is-open');
        });
    }

    function closeModal() {
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
        modal.addEventListener('transitionend', function () {
            modal.setAttribute('hidden', '');
            resetModal();
        }, { once: true });
    }

    function resetModal() {
        loading.removeAttribute('hidden');
        results.setAttribute('hidden', '');
        ctaLink.removeAttribute('hidden');
        scoreEls.forEach(function (el) {
            el.textContent = '–'; // en dash placeholder
            el.style.setProperty('--score', '0');
            el.className = 'score-list__value';
        });
    }

    // ── Score helpers ─────────────────────────────────────────────────────────

    function scoreClass(score) {
        if (score >= 90) return 'score-list__value--good';
        if (score >= 50) return 'score-list__value--caution';
        return 'score-list__value--warning';
    }

    function ctaContent(scores) {
        var vals    = Object.values(scores);
        var avg     = Math.round(vals.reduce(function (a, b) { return a + b; }, 0) / vals.length);
        var allGood = vals.every(function (s) { return s >= 90; });

        if (allGood) {
            return {
                note   : "These are strong scores — this is where every site should be. If you want to know how I build to this standard consistently, get in touch.",
                button : "How do you build like this? →",
            };
        }
        if (avg < 40) {
            return {
                note   : "Scores this low are costing you customers every day. A site this slow loses roughly half its visitors before the page even loads.",
                button : "I need to fix this →",
            };
        }
        if (avg < 70) {
            return {
                note   : "Scores like these are holding your site back. Performance below 70 directly affects your Google rankings and how many visitors actually stay.",
                button : "Fix my scores →",
            };
        }
        return {
            note   : "There’s room to improve here. Scores in this range mean slower loads, lower Google rankings, and visitors leaving before your page renders.",
            button : "Let’s improve these scores →",
        };
    }

    // Animate the conic-gradient ring by incrementing --score 0 → target.
    function animateScore(el, target, delay) {
        setTimeout(function () {
            var start    = performance.now();
            var duration = 900;

            el.textContent = '';

            function step(now) {
                var progress = Math.min((now - start) / duration, 1);
                // Ease-out cubic.
                var eased = 1 - Math.pow(1 - progress, 3);
                el.style.setProperty('--score', Math.round(eased * target));
                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    el.textContent = target;
                }
            }

            requestAnimationFrame(step);
        }, delay);
    }

    // ── Show results ──────────────────────────────────────────────────────────

    function showResults(scores) {
        loading.setAttribute('hidden', '');
        results.removeAttribute('hidden');

        scoreEls.forEach(function (el, i) {
            var score = scores[el.dataset.category] !== undefined ? scores[el.dataset.category] : 0;
            el.classList.add(scoreClass(score));
            animateScore(el, score, i * 150);
        });

        var cta = ctaContent(scores);
        ctaNote.textContent    = cta.note;
        ctaLink.textContent    = cta.button;
    }

    function showError(msg) {
        loading.setAttribute('hidden', '');
        results.removeAttribute('hidden');
        ctaNote.textContent = msg;
        ctaLink.setAttribute('hidden', '');
        // Show a minimal results panel with no rings.
        scoreEls.forEach(function (el) {
            el.textContent = '–';
            el.style.setProperty('--score', '0');
        });
    }

    // ── Form submit ───────────────────────────────────────────────────────────

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var input  = form.querySelector('.scanner__input');
        var rawUrl = (input.value || '').trim();
        if (!rawUrl) return;

        // Prepend scheme if missing.
        if (!/^https?:\/\//i.test(rawUrl)) {
            rawUrl = 'https://' + rawUrl;
        }

        var displayHost = rawUrl.replace(/^https?:\/\//, '').replace(/\/$/, '');

        urlSpan.textContent  = displayHost;
        siteName.textContent = displayHost;

        openModal();

        var body = new URLSearchParams({
            action  : 'lm_scan',
            nonce   : (window.lmTheme && window.lmTheme.scanner_nonce) || '',
            url     : rawUrl,
            website : '', // honeypot — always empty from real users
        });

        var ajaxUrl = (window.lmTheme && window.lmTheme.ajaxurl) || '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, {
            method      : 'POST',
            credentials : 'same-origin',
            headers     : { 'Content-Type': 'application/x-www-form-urlencoded' },
            body        : body.toString(),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                showResults(data.data);
            } else {
                var msg = (data.data && data.data.message)
                    ? data.data.message
                    : 'Something went wrong. Please try again.';
                showError(msg);
            }
        })
        .catch(function () {
            showError('Network error. Please check your connection and try again.');
        });
    });

    // ── Close handlers ────────────────────────────────────────────────────────

    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });

    // Close modal when the CTA link is clicked (user is scrolling down to contact).
    if (ctaLink) {
        ctaLink.addEventListener('click', closeModal);
    }
}());
