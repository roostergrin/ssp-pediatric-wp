$(document).ready(function() {

    function doSlides(thing) {
        if(thing.is(':inview')) {
            if(thing.find('.slide.active').length == 0 ) {
                thing.find('.slide').addClass('active');
            }
        } else {
            thing.find('.slide').removeClass('active');
        }
    }

    var variableSlideUpCarousel = $('.caro');
    function vsuCarousel() {
        var checkWidth = $(window).width();
        variableSlideUpCarousel.each(function() {
            if (checkWidth > 950) {
                if (typeof variableSlideUpCarousel.data('owl.carousel') != 'undefined') {
                variableSlideUpCarousel.data('owl.carousel').destroy();
                }
                variableSlideUpCarousel.removeClass('owl-carousel');
                doSlides(variableSlideUpCarousel);
            } else if (checkWidth <= 950) {
                if(!$(this).hasClass('kill-carousel')) {
                    $(this).find('.slide').removeClass('active');
                    variableSlideUpCarousel.addClass('owl-carousel');
                    variableSlideUpCarousel.owlCarousel({
                        slideSpeed : 500,
                        paginationSpeed : 400,
                        dots: false,
                        autoWidth: false,
                        loop: true,
                        mouseDrag: true,
                        pullDrag: true,
                        freeDrag: false,
                        autoplay: true,
                        autoplayHoverPause: true,
                        items: 1,
                        margin: 10,
                    });
                } else {
                    doSlides($(this))
                }
            }
        })
    }

    variableSlideUpCarousel.on('changed.owl.carousel', function(e) {
		variableSlideUpCarousel.trigger('stop.owl.autoplay');
		variableSlideUpCarousel.trigger('play.owl.autoplay');
	});

	$('section.variable-slide-up-carousel .pagination .page-left').click(function() {
		variableSlideUpCarousel.trigger('prev.owl.carousel');
	});

	$('section.variable-slide-up-carousel .pagination .page-right').click(function() {
		variableSlideUpCarousel.trigger('next.owl.carousel');
	});
      
    vsuCarousel();
    $(window).on('scroll resize', vsuCarousel);
});
