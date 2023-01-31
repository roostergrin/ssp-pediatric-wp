(function($) {
	var copy_tri_carousel_owl = $('.copy-tri-carousel.owl-carousel');
	copy_tri_carousel_owl.owlCarousel({
		items: 3,
		dots: false,
		center: true,
		autoWidth: true,
		loop: true,
		mouseDrag: true,
		pullDrag: true,
		freeDrag: false,
		autoplay: false,
		autoplayHoverPause: true,
		responsiveClass: true,
		responsive: {
			0: {
				margin: 15,
			},
			783: {
				margin: 40,
			}
		}
	});

	copy_tri_carousel_owl.on('changed.owl.carousel', function(e) {
        copy_tri_carousel_owl.trigger('stop.owl.autoplay');
        copy_tri_carousel_owl.trigger('play.owl.autoplay');
    });

	$('section.copy-tri-carousel .pagination .page-left').click(function() {
		copy_tri_carousel_owl.trigger('prev.owl.carousel');
	});

	$('section.copy-tri-carousel .pagination .page-right').click(function() {
		copy_tri_carousel_owl.trigger('next.owl.carousel');
	});
})(jQuery);
