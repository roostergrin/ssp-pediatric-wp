(function($) {

	var logo_owl_carousel = $('.logo.owl-carousel');
	if (logo_owl_carousel.length) {
		logo_owl_carousel.owlCarousel({
			loop:true,
			margin:10,
			responsiveClass:true,
			mouseDrag: true,
			touchDrag: true,
			autoplay: false,
			autoplayHoverPause: true,
			// autoWidth: true,
			responsive:{
				0:{
					items:1,
				},
				768:{
					items:5,

				}
			}
		});

		$('.logo.owl-carousel').on('changed.owl.carousel', function(e) {
	        $('.logo.owl-carousel').trigger('stop.owl.autoplay');
	        $('.logo.owl-carousel').trigger('play.owl.autoplay');
	    });

		$('section.logo-carousel .pagination .icon-page-left').on('click', function() {
			$('.logo.owl-carousel').trigger('prev.owl.carousel');
		});

		$('section.logo-carousel .pagination .icon-page-right').on('click', function() {
			$('.logo.owl-carousel').trigger('next.owl.carousel');
		});
	}

	var univ_logos = $('.univ-logos.owl-carousel');
	if ( univ_logos.length) {
		univ_logos.each(function() {
				$(this).owlCarousel({
				margin: 0,
				loop: false,
				dots: false,
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
						autoplay: false,
						autoplayHoverPause: true
					},
					950: {
						items: 3,
						slideBy: 1,
						mouseDrag: true,
						touchDrag: true,
						loop: $(this).find('.logo-container').length > 3,
						navText: [],
						nav: $(this).find('.logo-container').length > 3,
						autoplayHoverPause: true
					}
				}
			});
		});
		
		$('.car .univ-logos ~ .pagination-container .icon-left-arrow').on('click', function() {
			$(this).closest('.car').find('.univ-logos').trigger('prev.owl.carousel');
		});
		
		$('.car .univ-logos ~ .pagination-container .icon-right-arrow').on('click', function() {
			$(this).closest('.car').find('.univ-logos').trigger('next.owl.carousel');
		});
	}
})(jQuery);
