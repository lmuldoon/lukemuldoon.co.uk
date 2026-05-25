import './scripts/__tools';
import './scripts/mobile-nav';
import './scripts/scanner';
import './scripts/animations';

(function ($) {
	$('a[href*="#"]')
		.not('[href="#"]')
		.not('[href="#0"]')
		.not('[role="tab"]')
		.not('.glightbox-inline')
		.on('click', function (event) {
			if (
				location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
				location.hostname === this.hostname
			) {
				let target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				if (!target.length) return;

				event.preventDefault();

				const $header = $('.site-header');
				const headerHeight = $header.length ? Math.ceil($header.outerHeight(true)) : 0;

				$('html, body').animate({
					scrollTop: target.offset().top - headerHeight + 1
				}, 400, function () {
					const heading = $(target[0]).find('h2').get(0);
					if (!heading) return;
					heading.focus({ preventScroll: true });
					if (heading !== document.activeElement) {
						heading.setAttribute('tabindex', '-1');
						heading.focus({ preventScroll: true });
					}
				});
			}
		});
    // Cross-page anchor: scroll to hash target with header offset after full page load.
    if (window.location.hash) {
        var $hashTarget = $(window.location.hash);
        if ($hashTarget.length) {
            $(window).on('load', function () {
                var headerHeight = Math.ceil($('.site-header').outerHeight(true)) || 0;
                $('html, body').scrollTop($hashTarget.offset().top - headerHeight + 1);
            });
        }
    }
})(jQuery);