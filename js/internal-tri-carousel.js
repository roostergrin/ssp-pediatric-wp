(function($) {
	var tri_carousel_owl = $('.tri-carousel.owl-carousel');
	tri_carousel_owl.owlCarousel({
		dots: false,
		center: true,
		autoWidth: true,
		loop: true,
		mouseDrag: true,
		pullDrag: true,
		freeDrag: false,
		autoplay: true,
		autoplayHoverPause: true,
		responsiveClass: true,
		responsive: {
			0: {
				margin: 30,
				stagePadding: 30,
				items: 1
			},
			928: {
				margin: 40,
				items: 3
			}
		}
	});

	tri_carousel_owl.on('changed.owl.carousel', function(e) {
        tri_carousel_owl.trigger('stop.owl.autoplay');
        tri_carousel_owl.trigger('play.owl.autoplay');
    });

	$('section.tri-carousel .pagination .page-left').click(function() {
		tri_carousel_owl.trigger('prev.owl.carousel');
	});

	$('section.tri-carousel .pagination .page-right').click(function() {
		tri_carousel_owl.trigger('next.owl.carousel');
	});
})(jQuery);
