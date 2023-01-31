(function($) {
	var providers_owl = $('.providers.carousel .providers-container.owl-carousel');
	providers_owl.owlCarousel({
		items: 1,
		center: false,
		loop: true,
		nav: false,
		dots: false,
		// margin: 10,
		autoplay: true,
		autoplayTimeout: 5000,
		autoplayHoverPause: true,
		responsive: {
			0: {
				 margin: 30,
                 stagePadding: 40,
			},
			783: {
				margin: 10,
			}
		}
	});

	providers_owl.on('changed.owl.carousel', function(e) {
        providers_owl.trigger('stop.owl.autoplay');
        providers_owl.trigger('play.owl.autoplay');
    });

	$('.providers.carousel .pagination .page-left').click(function() {
		providers_owl.trigger('prev.owl.carousel');
	});

	$('.providers.carousel .pagination .page-right').click(function() {
		providers_owl.trigger('next.owl.carousel');
	});
})(jQuery);
