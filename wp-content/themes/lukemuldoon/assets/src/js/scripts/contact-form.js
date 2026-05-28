(function () {
    'use strict';

    var form = document.getElementById('contact-form');
    if (!form) return;

    var successEl = document.querySelector('.contact-form__success');
    var feedbackEl = form.querySelector('.contact-form__feedback');
    var submitBtn = form.querySelector('.contact-form__submit');

    function showFieldError(fieldName, msg) {
        var field = form.querySelector('[name="' + fieldName + '"]');
        if (!field) return;
        var errorEl = field.closest('.contact-form__field').querySelector('.contact-form__error');
        if (errorEl) errorEl.textContent = msg;
        field.setAttribute('aria-invalid', 'true');
    }

    function clearErrors() {
        form.querySelectorAll('.contact-form__error').forEach(function (el) {
            el.textContent = '';
        });
        form.querySelectorAll('[aria-invalid]').forEach(function (el) {
            el.removeAttribute('aria-invalid');
        });
        feedbackEl.hidden = true;
        feedbackEl.textContent = '';
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();

        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending…';

        var body = new URLSearchParams({
            action               : 'lm_contact',
            cf_name              : form.querySelector('[name="cf_name"]').value,
            cf_email             : form.querySelector('[name="cf_email"]').value,
            cf_budget            : form.querySelector('[name="cf_budget"]').value,
            cf_message           : form.querySelector('[name="cf_message"]').value,
            website              : form.querySelector('[name="website"]').value,
            form_time            : form.dataset.time || '',
            'h-captcha-response' : (window.hcaptcha ? hcaptcha.getResponse() : ''),
        });

        var ajaxUrl = (window.lmTheme && window.lmTheme.ajaxurl) || '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, {
            method  : 'POST',
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
            body    : body.toString(),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                form.hidden = true;
                successEl.hidden = false;
            } else {
                var d = data.data || {};
                if (d.field) {
                    showFieldError(d.field, d.message);
                } else {
                    feedbackEl.textContent = d.message || 'Something went wrong. Please try again.';
                    feedbackEl.hidden = false;
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Send message &#8594;';
                if (window.hcaptcha) hcaptcha.reset();
            }
        })
        .catch(function () {
            feedbackEl.textContent = 'Network error. Please check your connection and try again.';
            feedbackEl.hidden = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Send message &#8594;';
        });
    });
}());
