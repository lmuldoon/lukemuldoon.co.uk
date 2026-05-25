import {debounce} from './__event-utilities';

(function ($) {
	function setUpHeader() {
		const $siteHeader = $('.site-header');

		function storeHeaderHeight() {
			$('html').css('--header-height', `${Math.ceil($siteHeader.outerHeight(true))}px`);
		}

		storeHeaderHeight();

		$(window).on('resize', debounce(storeHeaderHeight, 150));
	}
	setUpHeader();
}(jQuery));