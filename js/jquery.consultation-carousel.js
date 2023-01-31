(function($) {
	var consultation_owl = $('.half.with-icons .consultation.owl-carousel');
	consultation_owl.owlCarousel({
		margin: 0,
		loop: false,
		dots: false,
		autoWidth: false,
		autoplay: false,
		autoplayHoverPause: true,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				slideBy: 1,
				mouseDrag: true,
				touchDrag: true,
				loop: true,
				nav: true,
				navText: [],
			},
			1260: {
				items: 6,
				slideBy: 6,
				mouseDrag: false,
				touchDrag: false,
				loop: false,
				nav: false,
				navText: [],
			}
		}
	});
})(jQuery);
