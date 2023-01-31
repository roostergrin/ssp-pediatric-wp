(function($) {
	let carousel = 'section.overlap.carousel',
		device = $(window).width() > 782 ? 'desktop' : 'mobile',
		owl = $(carousel).find('.owl-carousel'),
		interval_desktop = null;
		
	function resetInterval() {
		if(interval_desktop) clearInterval(interval_desktop);
		interval_desktop = setInterval(function() {
			$(carousel).find('.pagination-container .pagination .page-right').trigger('click');
		}, 4000);
	}

	if ($(carousel).length) {
		// Desktop
		$(carousel).find('.pagination-container .pagination .page-left').on('click', function() {
			if ($(carousel).find('aside .img-container.active').prev('.img-container').length) {
				$(carousel).find('aside .img-container.active').removeClass('active').prev('.img-container').addClass('active');
			} else {
				$(carousel).find('aside .img-container.active').removeClass('active');
				$(carousel).find('aside .img-container').last().addClass('active');
			}
			resetInterval();
		});

		$(carousel).find('.pagination-container .pagination .page-right').on('click', function() {
			if ($(carousel).find('aside .img-container.active').next('.img-container').length > 0) {
				$(carousel).find('aside .img-container.active').removeClass('active').next('.img-container').addClass('active');
			} else {
				$(carousel).find('aside .img-container.active').removeClass('active');
				$(carousel).find('aside .img-container').first().addClass('active');
			}
			resetInterval();
		});

		// Mobile
		$(carousel).find('.mobile-pagination-container .mobile-pagination .icon-left-arrow-thick').on('click', function() {
			if ($(carousel).find('article .images-carousel-mobile .img-container.active').prev('.img-container').length > 0) {
				$(carousel).find('article .images-carousel-mobile .img-container.active').removeClass('active').prev('.img-container').addClass('active');
			} else {
				$(carousel).find('article .images-carousel-mobile .img-container.active').removeClass('active');
				$(carousel).find('article .images-carousel-mobile .img-container').last().addClass('active');
			}
		});

		$(carousel).find('.mobile-pagination-container .mobile-pagination .icon-right-arrow').on('click', function() {
			if ($(carousel).find('article .images-carousel-mobile .img-container.active').next('.img-container').length > 0) {
				$(carousel).find('article .images-carousel-mobile .img-container.active').removeClass('active').next('.img-container').addClass('active');
			} else {
				$(carousel).find('article .images-carousel-mobile .img-container.active').removeClass('active');
				$(carousel).find('article .images-carousel-mobile .img-container').first().addClass('active');
			}
		});

		$(window).on('scroll load resize', function() {
			if ($(carousel).find('.main-container:inview').length > 0) {
				$(carousel).find('article .carousel-item.active').removeClass('active');
				$(carousel).find('aside .img-container.active').removeClass('active');
				$(carousel).find('article .carousel-item').first().addClass('active');
				$(carousel).find('aside .img-container').first().addClass('active');
				if(interval_desktop === null) resetInterval();
			} else {
				$(carousel).find('article .carousel-item.active').first().removeClass('active');
				$(carousel).find('aside .img-container.active').first().removeClass('active');
				if(interval_desktop) {
					clearInterval(interval_desktop);
					interval_desktop = null;
				}
			}

			if ($(window).width() > 782 && device != 'desktop') {
				if ($(owl).hasClass('owl-carousel')) {
					// $(owl).data('owl.carousel').destroy();
					// $(owl).removeClass('.owl-carousel');
				}
			} else if ($(window).width() < 783 && device != 'mobile') {
				if (!$(owl).hasClass('owl-carousel')) {
				}
			}
			device = $(window).width() > 782 ? 'desktop' : 'mobile';
		});
	}

	$(owl).owlCarousel({
		items: 1,
		slideBy: 1,
		margin: 10,
		loop: true,
		dots: false,
		nav: false,
		mouseDrag: true,
		touchDrag: true,
		autoplay: false,
		autoplayHoverPause: true
	});

	$(owl).on('changed.owl.carousel', function(e) {
        $(owl).trigger('stop.owl.autoplay');
        $(owl).trigger('play.owl.autoplay');
    });

	$(carousel).find('.mobile-pagination .icon-left-arrow, .page-left').click(function() {
		$(owl).trigger('prev.owl.carousel');
	});

	$(carousel).find('.mobile-pagination .icon-right-arrow, .page-right').click(function() {
		$(owl).trigger('next.owl.carousel');
	});

})(jQuery);
