(function($) {
	function getScrollTopCalculation(target) {
		var header = $(window).width() <= 782 ? $('#mobile-navigation') : $('section.header > .content');
		return Math.floor(target.offset().top - header.outerHeight() - ($('#wpadminbar').outerHeight()||0) - 120);
	}
	function scrollToTarget(target) {
		var header = $(window).width() <= 782 ? $('#mobile-navigation') : $('section.header');
		$('html, body').animate({
			scrollTop: getScrollTopCalculation(target)
		}, 250);
		if ($(window).width() <= 782) $('html').removeClass('mobileMenu mobileNavigating');
	}
	$('section.header nav .primary-nav > ul > li > a, #mobile-navigation > ul > li > a').on('click', function() {
		// console.log('click');
		// console.log(window.location.pathname.replace(/^\//,''));
		// console.log(this.pathname.replace(/^\//,''));
		// console.log(window.location.hostname);
		// console.log(this.hostname);
		if (window.location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && window.location.hostname == this.hostname) {
			if ($('html').hasClass('mobile-nav-open')) {
				$('html').removeClass('mobile-nav-open');
			}
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				scrollToTarget(target);
				return false;
			}
		}
	});

	///////////// STICKY HEADER / ACTIVE LINKS /////////////
	function onPageChanged() {
		let about_target = getScrollTopCalculation($('#about')),
			approach_target = getScrollTopCalculation($('#approach')),
			locations_target = getScrollTopCalculation($('#locations')),
			process_target = getScrollTopCalculation($('#process')),
			affiliate_target = getScrollTopCalculation($('#affiliate')) - 120;

		if ($(window).width() <= 782) {
			$('html').toggleClass('sticky-header', $(window).scrollTop() > 40);
			$('a[href$="#about"]').closest('li').toggleClass('active', $(window).scrollTop() >= about_target && $(window).scrollTop() < approach_target);
			$('a[href$="#approach"]').closest('li').toggleClass('active', $(window).scrollTop() >= approach_target && $(window).scrollTop() < locations_target);
			$('a[href$="#locations"]').closest('li').toggleClass('active', $(window).scrollTop() >= locations_target && $(window).scrollTop() < process_target);
			$('a[href$="#process"]').closest('li').toggleClass('active', $(window).scrollTop() >= process_target && $(window).scrollTop() < affiliate_target);
			$('a[href$="#affiliate"]').closest('li').toggleClass('active', $(window).scrollTop() >= affiliate_target);
		} else {
			$('html').toggleClass('sticky-header', $(window).scrollTop() > 130);
			$('a[href$="#about"]').closest('li').toggleClass('active', $(window).scrollTop() >= about_target && $(window).scrollTop() < approach_target);
			$('a[href$="#approach"]').closest('li').toggleClass('active', $(window).scrollTop() >= approach_target && $(window).scrollTop() < locations_target);
			$('a[href$="#locations"]').closest('li').toggleClass('active', $(window).scrollTop() >= locations_target && $(window).scrollTop() < process_target);
			$('a[href$="#process"]').closest('li').toggleClass('active', $(window).scrollTop() >= process_target && $(window).scrollTop() < affiliate_target);
			$('a[href$="#affiliate"]').closest('li').toggleClass('active', $(window).scrollTop() >= affiliate_target);
		}
	}
	$(window).on('scroll load resize', function() { onPageChanged(); });
	onPageChanged();
})(jQuery);
