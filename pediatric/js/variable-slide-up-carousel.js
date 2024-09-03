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

    function vsuCarousel(carouselId) {
        var carousel = $('#' + carouselId + '-owl-carousel');
        var checkWidth = $(window).width();
        carousel.each(function() {
            if (checkWidth > 950) {
                if (typeof carousel.data('owl.carousel') != 'undefined') {
                    carousel.data('owl.carousel').destroy();
                }
                carousel.removeClass('owl-carousel');
                doSlides(carousel);
            } else if (checkWidth <= 950) {
                if(!$(this).hasClass('kill-carousel')) {
                    $(this).find('.slide').removeClass('active');
                    carousel.addClass('owl-carousel');
                    carousel.owlCarousel({
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
        });
        
        carousel.on('changed.owl.carousel', function(e) {
            carousel.trigger('stop.owl.autoplay');
            carousel.trigger('play.owl.autoplay');
        });
        
        $('section#' + carouselId + ' .pagination .page-left').click(function() {
            carousel.trigger('prev.owl.carousel');
        });
        
        $('section#' + carouselId + ' .pagination .page-right').click(function() {
            carousel.trigger('next.owl.carousel');
        });
    }

    $('section.variable-slide-up-carousel').each(function() {
        var carouselId = $(this).attr('id');
        vsuCarousel(carouselId);
        $(window).on('scroll resize', function() {
            vsuCarousel(carouselId);
        });
    });
});
