(function($) {
	var hero_owl = $('.hero.owl-carousel');
	hero_owl.owlCarousel({
		items: 1,
		loop: false,
		dots: false,
		nav: false,
		mouseDrag: true,
		touchDrag: true,
		autoplay: false,
		autoplayHoverPause: true
	});

	hero_owl.on('changed.owl.carousel', function(e) {
        hero_owl.trigger('stop.owl.autoplay');
        hero_owl.trigger('play.owl.autoplay');
    });

	$('section.hero-carousel .pagination .icon-page-left').click(function() {
		hero_owl.trigger('prev.owl.carousel');
	});

	$('section.hero-carousel .pagination .icon-page-right').click(function() {
		hero_owl.trigger('next.owl.carousel');
	});

	function getActiveImageLabel() {
		let label = $('.hero.owl-carousel .owl-item.active .img-container img').data('label');
		if (typeof(label) !== 'undefined') $('#image-label').html(label);
	}

	hero_owl.on('translated.owl.carousel', function(e) {
		getActiveImageLabel();
	});
	getActiveImageLabel();
})(jQuery);
