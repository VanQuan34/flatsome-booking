/**
 * Tevo - Main Frontend JavaScript
 *
 * @package Tevo
 * @since   1.0.0
 */

(function ($) {
	'use strict';

	var Tevo = {

		init: function () {
			this.preloader();
			this.stickyHeader();
			this.mobileMenu();
			this.searchOverlay();
			this.scrollToTop();
			this.smoothScroll();
			this.counterAnimation();
		},

		/**
		 * Preloader
		 */
		preloader: function () {
			var $preloader = $('#tevo-preloader');
			if ($preloader.length) {
				$(window).on('load', function () {
					$preloader.fadeOut(400, function () {
						$(this).remove();
					});
				});
				// Fallback: remove preloader after 5s
				setTimeout(function () {
					$preloader.fadeOut(400, function () { $(this).remove(); });
				}, 5000);
			}
		},

		/**
		 * Sticky Header
		 */
		stickyHeader: function () {
			var $header = $('#tevo-header');
			var $body = $('body');

			if (!$body.hasClass('tevo-sticky-header')) {
				return;
			}

			var headerOffset = $header.offset().top + $header.outerHeight();

			$(window).on('scroll', function () {
				if ($(window).scrollTop() > headerOffset) {
					$header.addClass('tevo-header--stuck');
				} else {
					$header.removeClass('tevo-header--stuck');
				}
			});
		},

		/**
		 * Mobile Menu Toggle
		 */
		mobileMenu: function () {
			var $toggle = $('#tevo-mobile-toggle');
			var $nav = $('#tevo-nav');

			$toggle.on('click', function (e) {
				e.preventDefault();
				var expanded = $(this).attr('aria-expanded') === 'true';
				$(this).attr('aria-expanded', !expanded);
				$(this).toggleClass('tevo-mobile-toggle--active');
				$nav.toggleClass('tevo-nav--open');
				$('body').toggleClass('tevo-mobile-open');
			});

			// Submenu toggle on mobile.
			$nav.on('click', '.tevo-has-submenu > .tevo-menu-link .tevo-menu-arrow', function (e) {
				if ($(window).width() < 1024) {
					e.preventDefault();
					e.stopPropagation();
					$(this).closest('.tevo-has-submenu').toggleClass('tevo-submenu-open');
				}
			});
		},

		/**
		 * Search Overlay
		 */
		searchOverlay: function () {
			var $overlay = $('#tevo-search-overlay');
			var $toggleBtn = $('#tevo-search-toggle');
			var $closeBtn = $('#tevo-search-close');

			$toggleBtn.on('click', function (e) {
				e.preventDefault();
				$overlay.addClass('tevo-search-overlay--open');
				$overlay.attr('aria-hidden', 'false');
				$overlay.find('.tevo-search-form__input').focus();
			});

			$closeBtn.on('click', function (e) {
				e.preventDefault();
				$overlay.removeClass('tevo-search-overlay--open');
				$overlay.attr('aria-hidden', 'true');
			});

			$(document).on('keydown', function (e) {
				if (e.key === 'Escape') {
					$overlay.removeClass('tevo-search-overlay--open');
					$overlay.attr('aria-hidden', 'true');
				}
			});
		},

		/**
		 * Scroll to Top
		 */
		scrollToTop: function () {
			var $btn = $('#tevo-scroll-top');

			$(window).on('scroll', function () {
				if ($(window).scrollTop() > 500) {
					$btn.addClass('tevo-scroll-top--visible');
				} else {
					$btn.removeClass('tevo-scroll-top--visible');
				}
			});

			$btn.on('click', function (e) {
				e.preventDefault();
				$('html, body').animate({ scrollTop: 0 }, 600, 'swing');
			});
		},

		/**
		 * Smooth Scroll for anchor links
		 */
		smoothScroll: function () {
			$('a[href^="#"]:not([href="#"])').on('click', function (e) {
				var target = $($(this).attr('href'));
				if (target.length) {
					e.preventDefault();
					$('html, body').animate({
						scrollTop: target.offset().top - 80
					}, 600);
				}
			});
		},

		/**
		 * Counter Animation (IntersectionObserver)
		 */
		counterAnimation: function () {
			var counters = document.querySelectorAll('.tevo-counter');
			if (!counters.length || !('IntersectionObserver' in window)) {
				return;
			}

			var observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						var el = entry.target;
						var valueEl = el.querySelector('.tevo-counter__value');
						var target = parseInt(el.getAttribute('data-target'), 10) || 0;
						var duration = parseInt(el.getAttribute('data-duration'), 10) || 2000;
						var start = 0;
						var startTime = null;

						function animate(currentTime) {
							if (!startTime) startTime = currentTime;
							var progress = Math.min((currentTime - startTime) / duration, 1);
							var eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
							valueEl.textContent = Math.floor(eased * target);
							if (progress < 1) {
								requestAnimationFrame(animate);
							} else {
								valueEl.textContent = target;
							}
						}

						requestAnimationFrame(animate);
						observer.unobserve(el);
					}
				});
			}, { threshold: 0.3 });

			counters.forEach(function (counter) {
				observer.observe(counter);
			});
		}
	};

	$(document).ready(function () {
		Tevo.init();
	});

})(jQuery);
