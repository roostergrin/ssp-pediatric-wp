(function($) {
	var providers_tri_carousel_owl = $('.providers-tri-carousel .providers.owl-carousel');
	var isSmilesInMotion = $('body').hasClass('smiles-in-motion');
	providers_tri_carousel_owl.owlCarousel({
		dots: false,
		center: true,
		loop: true,
		mouseDrag: true,
		pullDrag: true,
		freeDrag: false,
		autoplay: false,
		autoplayTimeout: (isSmilesInMotion ? 3000 : 5000),
		autoplayHoverPause: true,
		responsiveClass: true,
		responsive: {
			0: {
				margin: 15,
				stagePadding: 40,
				autoWidth: false,
				items: 1
			},
			928: {
				margin: 40,
				autoWidth: true,
				items: 3
			}
		}
	});

	providers_tri_carousel_owl.on('changed.owl.carousel', function(e) {
		providers_tri_carousel_owl.trigger('stop.owl.autoplay');
		providers_tri_carousel_owl.trigger('play.owl.autoplay');
	});

	$('section.providers-tri-carousel .pagination .page-left').click(function() {
		providers_tri_carousel_owl.trigger('prev.owl.carousel');
	});

	$('section.providers-tri-carousel .pagination .page-right').click(function() {
		providers_tri_carousel_owl.trigger('next.owl.carousel');
	});

	var providers_home_carousel_owl = $('.providers.home-carousel .providers.owl-carousel');
	providers_home_carousel_owl.owlCarousel({
		dots: false,
		center: true,
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
				stagePadding: 40,
				autoWidth: false,
				items: 1
			},
			928: {
				margin: 40,
				autoWidth: true,
				items: 3
			}
		}
	});

	// providers_home_carousel_owl.on('changed.owl.carousel', function(e) {
	// 	providers_home_carousel_owl.trigger('stop.owl.autoplay');
	// 	providers_home_carousel_owl.trigger('play.owl.autoplay');
	// });

	// $('section.providers.home-carousel .pagination .page-left').click(function() {
	// 	providers_home_carousel_owl.trigger('prev.owl.carousel');
	// });

	// $('section.providers.home-carousel .pagination .page-right').click(function() {
	// 	providers_home_carousel_owl.trigger('next.owl.carousel');
	// });
})(jQuery);
