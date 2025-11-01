/**
 * Mobile-First Tabbed Slider JavaScript
 * Single visible tab with arrow navigation
 */

(function ($) {
	'use strict';

	function initTabbedSlider(selector, options = {}) {
		const $slider = $(selector);
		if (!$slider.length) return;

		// Configuration with defaults
		const config = {
			autoplay: options.autoplay === true, // Disabled by default
			autoplayDelay: options.autoplayDelay || 6000, // Configurable timeout in milliseconds
			...options
		};

		// Also check data attributes for configuration
		const dataAutoplay = $slider.data('autoplay');
		const dataDelay = $slider.data('autoplay-delay');

		if (dataAutoplay !== undefined) {
			config.autoplay = dataAutoplay === true || dataAutoplay === 'true' || dataAutoplay === 1 || dataAutoplay === '1';
		}
		if (dataDelay !== undefined) {
			config.autoplayDelay = parseInt(dataDelay, 10) || 6000;
		}

		const $tabTrack = $slider.find('.ts-tab-track');
		const $tabs = $slider.find('.ts-tab');
		const $contentSlides = $slider.find('.ts-content-slide');
		const $dots = $slider.find('.ts-dot');
		const $prevBtn = $slider.find('.ts-prev');
		const $nextBtn = $slider.find('.ts-next');

		let currentIndex = 0;
		const totalSlides = $tabs.length;
		let isTransitioning = false;
		let autoplayInterval = null;

		/**
		 * Update slider to show specific slide
		 */
		function goToSlide(index, direction = 'next') {
			if (index < 0 || index >= totalSlides || isTransitioning) return;
			if (index === currentIndex) return;

			isTransitioning = true;
			const prevIndex = currentIndex;
			currentIndex = index;

			// Update tab position
			const translateX = -(currentIndex * 100);
			$tabTrack.css('transform', `translateX(${translateX}%)`);

			// Update active states
			$tabs.removeClass('active').eq(currentIndex).addClass('active');
			$contentSlides.removeClass('active').eq(currentIndex).addClass('active');
			$dots.removeClass('active').attr('aria-selected', 'false')
				.eq(currentIndex).addClass('active').attr('aria-selected', 'true');

			// Update arrow states
			updateArrowStates();

			// Reset transition flag after animation
			setTimeout(() => {
				isTransitioning = false;
			}, 400);
		}

		/**
		 * Go to next slide
		 */
		function nextSlide() {
			const next = (currentIndex + 1) % totalSlides;
			goToSlide(next, 'next');
			resetAutoplay();
		}

		/**
		 * Go to previous slide
		 */
		function prevSlide() {
			const prev = (currentIndex - 1 + totalSlides) % totalSlides;
			goToSlide(prev, 'prev');
			resetAutoplay();
		}

		/**
		 * Update navigation arrow disabled states
		 */
		function updateArrowStates() {
			if (totalSlides <= 1) {
				$prevBtn.prop('disabled', true);
				$nextBtn.prop('disabled', true);
				return;
			}

			// Circular navigation - never disable
			$prevBtn.prop('disabled', false);
			$nextBtn.prop('disabled', false);
		}

		/**
		 * Start autoplay
		 */
		function startAutoplay() {
			if (!config.autoplay || autoplayInterval || totalSlides <= 1) return;

			autoplayInterval = setInterval(() => {
				nextSlide();
			}, config.autoplayDelay);
		}

		/**
		 * Stop autoplay
		 */
		function stopAutoplay() {
			if (autoplayInterval) {
				clearInterval(autoplayInterval);
				autoplayInterval = null;
			}
		}

		/**
		 * Reset autoplay
		 */
		function resetAutoplay() {
			stopAutoplay();
			startAutoplay();
		}

		/**
		 * Handle touch/swipe
		 */
		let touchStartX = 0;
		let touchEndX = 0;
		const swipeThreshold = 50;

		$slider.on('touchstart', function (e) {
			touchStartX = e.originalEvent.touches[0].clientX;
			if (config.autoplay) {
				stopAutoplay();
			}
		});

		$slider.on('touchend', function (e) {
			touchEndX = e.originalEvent.changedTouches[0].clientX;
			const diff = touchStartX - touchEndX;

			if (Math.abs(diff) > swipeThreshold) {
				if (diff > 0) {
					nextSlide();
				} else {
					prevSlide();
				}
			} else if (config.autoplay) {
				startAutoplay();
			}
		});

		/**
		 * Keyboard navigation
		 */
		function handleKeyboard(e) {
			if (!$slider.is(':visible')) return;

			switch (e.key) {
				case 'ArrowLeft':
					e.preventDefault();
					prevSlide();
					break;
				case 'ArrowRight':
					e.preventDefault();
					nextSlide();
					break;
				case 'Home':
					e.preventDefault();
					goToSlide(0);
					break;
				case 'End':
					e.preventDefault();
					goToSlide(totalSlides - 1);
					break;
			}
		}

		// Event handlers
		$prevBtn.on('click', function (e) {
			e.preventDefault();
			prevSlide();
		});

		$nextBtn.on('click', function (e) {
			e.preventDefault();
			nextSlide();
		});

		$dots.on('click', function () {
			const index = parseInt($(this).data('index'), 10);
			if (!isNaN(index)) {
				goToSlide(index);
			}
		});

		// Tab click (for accessibility)
		$tabs.on('click', function () {
			const index = parseInt($(this).data('index'), 10);
			if (!isNaN(index)) {
				goToSlide(index);
			}
		});

		// Keyboard support
		$slider.on('keydown', handleKeyboard);

		// Pause on hover/interaction (only if autoplay is enabled)
		if (config.autoplay) {
			$slider.on('mouseenter touchstart', function () {
				stopAutoplay();
			});

			$slider.on('mouseleave', function () {
				startAutoplay();
			});

			// Pause when tab is hidden
			if (document.addEventListener) {
				document.addEventListener('visibilitychange', function () {
					if (document.hidden) {
						stopAutoplay();
					} else {
						startAutoplay();
					}
				});
			}
		}

		// Initialize
		updateArrowStates();
		if (config.autoplay) {
			startAutoplay();
		}

		// Handle resize
		let resizeTimer;
		$(window).on('resize', function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				const translateX = -(currentIndex * 100);
				$tabTrack.css('transform', `translateX(${translateX}%)`);
			}, 250);
		});

		// Expose API
		$slider.data('tabbedSlider', {
			next: nextSlide,
			prev: prevSlide,
			goTo: goToSlide,
			getCurrent: () => currentIndex,
			getTotal: () => totalSlides
		});
	}

	// Global function
	window.initTabbedSlider = initTabbedSlider;

	// Auto-init
	$(document).ready(function () {
		$('.wp-tabbed-slider').each(function () {
			const id = $(this).attr('id');
			if (id) {
				initTabbedSlider('#' + id);
			}
		});
	});

})(jQuery || window.jQuery);
