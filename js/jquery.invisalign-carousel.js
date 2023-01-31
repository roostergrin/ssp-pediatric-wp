(function($) {
	var invisalign_owl = $('.invisalign-carousel .owl-carousel');
    invisalign_owl.owlCarousel({
		items: 3,
		slideBy: 1,
		nav: false,
		dots: false,
		loop: false,
		autoWidth: true,
		mouseDrag: true,
		pullDrag: true,
		freeDrag: false,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				loop: true,
				margin: 40,
				autoplay: false,
				autoplayHoverPause: true
			},
			950: {
				margin: 40,
                nav: false,
                mouseDrag: false,
                navigation: false,
            }
		}
	});

	invisalign_owl.on('changed.owl.carousel', function(e) {
        invisalign_owl.trigger('stop.owl.autoplay');
        invisalign_owl.trigger('play.owl.autoplay');
    });

	$('section.invisalign-carousel .pagination .page-left').click(function() {
        invisalign_owl.trigger('prev.owl.carousel');
	});

	$('section.invisalign-carousel .pagination .page-right').click(function() {
		invisalign_owl.trigger('next.owl.carousel');
	});

	// function getActiveSlideIcon() {
	// 	let icon = $('.invisalign-carousel .owl-carousel .owl-item.active.center .slide').data('icon').replace('icon-', '');
	// 	if (typeof(icon) !== 'undefined') {
	// 		if (typeof(prev_icon) !== 'undefined') $('html').removeClass(prev_icon);
	// 		prev_icon = icon;
	// 		$('html').addClass(icon);
	// 	}
	// }
    //
	// invisalign_owl.on('translated.owl.carousel', function(e) {
	// 	console.log(this);
	// 	getActiveSlideIcon();
	// });
	// getActiveSlideIcon();
})(jQuery);
