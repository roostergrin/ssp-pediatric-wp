(function($) {
	var personalized_treatments_owl = $('.personalized-treatments.owl-carousel');
	personalized_treatments_owl.owlCarousel({
		dots: false,
		mouseDrag: true,
		touchDrag: true,
		autoplay: false,
		autoplayPause: true,
		loop: true,
		responsiveClass: true,
		responsive: {
			0: {
				items: 3,
				slideBy: 1,
				center: true,
				margin: 10,
				autoWidth: true,
				nav: true,
				navText: [],
			},
			783: {
				items: 1,
				slideBy: 1,
				center: false,
				margin: 705,
				autoWidth: false,
				nav: false,
				navText: [],
			}
		}
	});

	personalized_treatments_owl.on('changed.owl.carousel', function(e) {
        personalized_treatments_owl.trigger('stop.owl.autoplay');
        personalized_treatments_owl.trigger('play.owl.autoplay');
    });

	$('.personalized-treatments .pagination .icon-left-arrow-thick').click(function() {
		personalized_treatments_owl.trigger('prev.owl.carousel');
	});

	$('.personalized-treatments .pagination .icon-right-arrow').click(function() {
		personalized_treatments_owl.trigger('next.owl.carousel');
	});

	$('section.personalized-treatments .main-container article .read-more').on('click', function() {
		let more_div = $('section.personalized-treatments .main-container article .more');
		more_div.toggleClass('active');
		if (more_div.hasClass('active')) {
			$(this).text('Read less');
		} else {
			$(this).text('Read more');
		}
	});
})(jQuery);
