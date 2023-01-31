$(document).ready(function() {

    function threeIconsCarousel() {
        var checkWidth = $(window).width();
        var threeIconsCarousel = $('.three-icons-carousel');

        threeIconsCarousel.each(function() {
            if (checkWidth > 950) {
                if (typeof $(this).data('owl.carousel') != 'undefined') {
                $(this).data('owl.carousel').destroy();
                }
                $(this).removeClass('owl-carousel');
            } else if (checkWidth <= 950) {
                if(!$(this).hasClass('kill-carousel')) {
                    $(this).addClass('owl-carousel');
                    $(this).owlCarousel({
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
                    });
                } else {
                    // do nothing
                }
            }
        })
    }

    threeIconsCarousel();
    $(window).resize(threeIconsCarousel);

});
