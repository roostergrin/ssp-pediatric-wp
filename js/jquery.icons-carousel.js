(function($) {
	var four_cols_owl = $('.four-cols.owl-carousel');
	four_cols_owl.owlCarousel({
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
			500: {
				items: 2,
				slideBy: 2,
				mouseDrag: true,
				touchDrag: true,
				loop: true,
				nav: true,
				navText: [],
				autoplay: false,
				autoplayHoverPause: true
			},
			950: {
				items: 4,
				slideBy: 4,
				mouseDrag: false,
				touchDrag: false,
				loop: false,
				nav: false,
				navText: [],
			}
		}
	});

	var two_cols_owl = $('.two-cols.owl-carousel');
	two_cols_owl.owlCarousel({
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
				items: 2,
				slideBy: 2,
				mouseDrag: false,
				touchDrag: false,
				loop: false,
				nav: false,
				navText: [],
			}
		}
	});

    $(window).on('resize load', function(){
        var widgets_carousel = $('.widget-carousel');
        if ( $(window).width() < 748 ) {
            widgets_carousel.addClass('owl-carousel');
            $('.widget-carousel .widget-content .copy').css('opacity', 1);
            $('.widget-carousel .widget-content .copy').css('display', 'block');
            $('.widget-carousel .widget-container').unbind("hover");
            $('.widget-carousel .widget-container').unbind("click");
            widgets_carousel.owlCarousel({
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
                }
            });
        }
        else {
            // $('.widget-carousel .widget-content .copy').css('opacity', 0);
            // $('.widget-carousel .widget-content .copy').css('display', 'none');
            widgets_carousel.removeClass('owl-carousel');
            widgets_carousel.owlCarousel('destroy');
        }
	})

    var icons = $('body.page-template-virtual-monitoring .icons.owl-carousel');
    icons.owlCarousel({
        margin: 0,
        loop: false,
        dots: false,
        responsiveClass: false,
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
                items: 4,
                slideBy: 4,
                mouseDrag: false,
                touchDrag: false,
                loop: false,
                nav: false,
                navText: [],
            }
        }
    });
})(jQuery);
