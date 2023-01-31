$(document).ready(function() {
    function fourIconsCarousel() {
        var checkWidth = $(window).width();
        var fourIconsCarousel = $('.four-icons-with-image-carousel');

        if (checkWidth > 950) {
            if (typeof fourIconsCarousel.data('owl.carousel') != 'undefined') {
            fourIconsCarousel.data('owl.carousel').destroy();
            }
            fourIconsCarousel.removeClass('owl-carousel');
        } else if (checkWidth <= 950) {
            fourIconsCarousel.addClass('owl-carousel');
            fourIconsCarousel.owlCarousel({
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
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                    },
                    768:{
                        items:2,
                    }
                }
            });
        }
    }
      
    fourIconsCarousel();
    $(window).resize(fourIconsCarousel);

});
