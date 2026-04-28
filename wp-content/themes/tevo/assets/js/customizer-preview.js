/**
 * Tevo Customizer Live Preview
 * @package Tevo
 */
(function ($) {
	'use strict';

	// Colors.
	wp.customize('tevo_primary_color', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--tevo-primary', to);
		});
	});
	wp.customize('tevo_secondary_color', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--tevo-secondary', to);
		});
	});
	wp.customize('tevo_accent_color', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--tevo-accent', to);
		});
	});
	wp.customize('tevo_text_color', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--tevo-text', to);
		});
	});
	wp.customize('tevo_heading_color', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--tevo-heading', to);
		});
	});

	// Top bar text.
	wp.customize('tevo_topbar_phone', function (value) {
		value.bind(function (to) {
			$('.tevo-topbar__item:has([href^="tel:"])').find('span').text(to);
		});
	});
	wp.customize('tevo_topbar_email', function (value) {
		value.bind(function (to) {
			$('.tevo-topbar__item:has([href^="mailto:"])').find('span').text(to);
		});
	});
	wp.customize('tevo_header_cta_text', function (value) {
		value.bind(function (to) {
			$('.tevo-header__cta').text(to);
		});
	});
	wp.customize('tevo_footer_copyright', function (value) {
		value.bind(function (to) {
			$('.tevo-footer__copyright').html(to);
		});
	});

})(jQuery);
