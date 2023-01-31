(function($) {
	var all_ages_owl = $('.all-ages.owl-carousel');
	all_ages_owl.owlCarousel({
		items: 1,
		slideBy: 1,
		margin: 10,
		dots: false,
		nav: true,
		navText: [],
		center: true,
		// autoWidth: true,
		mouseDrag: true,
		touchDrag: true,
		autoplayPause: true,
		loop: true,
        responsiveClass: true,
		responsive: {
			0: {
                stagePadding: 40,
                items: 1,
				autoplay: true,
			},
			781: {
                items: 1,
				autoplay: false,
			}
		}
	});

	all_ages_owl.on('changed.owl.carousel', function(e) {
        all_ages_owl.trigger('stop.owl.autoplay');
        all_ages_owl.trigger('play.owl.autoplay');
    });

	all_ages_owl.on('refreshed.owl.carousel', function(e) {
		$('.all-ages article .tab-content.active').removeClass('active');
		let content_id = $('.all-ages.owl-carousel .owl-item.active.center .img-container .label').text().trim().replace('Orthodontic treatment for ', '') + '-content';
		content_id = content_id.toLowerCase();
		$('#'+content_id).addClass('active');
	});

	all_ages_owl.on('translated.owl.carousel', function(e) {
		$('.all-ages article .tab-content.active').removeClass('active');
		let content_id = $('.all-ages.owl-carousel .owl-item.active.center .img-container .label').text().trim().replace('Orthodontic treatment for ', '') + '-content';
		content_id = content_id.toLowerCase();
		$('#'+content_id).addClass('active');
	});

	$('.all-ages article .container-buttons .tab-link').hover(function() {
		if ($(this).hasClass('active')) return;
		$('.all-ages article .container-buttons .tab-link.active').removeClass('active');
		$('.all-ages aside .container-images .tab-image.active').removeClass('active');
		var image_id = $(this).attr('id').replace('link', 'image');
		$(this).addClass('active');
		$('#'+image_id).addClass('active');

		if ($('body').hasClass('page-template-brand-home')) {
			$('.all-ages article .tab-content.active').removeClass('active');
			var content_id = $(this).attr('id').replace('link', 'content');
			$('#'+content_id).addClass('active');
		}
	});

	$(window).on('scroll load resize', function() {
		if ($('body').hasClass('page-template-location-home') || $('body').hasClass('page-template-brand-home')) {
			if ($('.all-ages aside .container-images img:inview').length > 0) {
				if ($('.all-ages aside .container-images .tab-image').hasClass('active')) return;
				$('.all-ages article .container-buttons .tab-link.active').removeClass('active');
				$('.all-ages aside .container-images .tab-image.active').removeClass('active');
				$('#children-link').addClass('active');
				$('#children-image').addClass('active');

				if ($('body').hasClass('page-template-brand-home')) {
					$('.all-ages article .tab-content.active').removeClass('active');
					$('#children-content').addClass('active');
				}
			} else {
				$('.all-ages article .container-buttons .tab-link.active').removeClass('active');
				$('.all-ages aside .container-images .tab-image.active').removeClass('active');
				if ($(window).width() > 1020) {
					if ($('body').hasClass('page-template-brand-home')) {
						$('.all-ages article .tab-content.active').removeClass('active');
					}
				}
			}
		}
	});
})(jQuery);
