$(document).ready(function() {

    function sixIconsCarousel() {
        var checkWidth = $(window).width();
        var sixIconsCarousel = $('.six-icons-carousel');

        sixIconsCarousel.each(function() {
            if (checkWidth > 950) {
                if (typeof $(this).data('owl.carousel') != 'undefined') {
                $(this).data('owl.carousel').destroy();
                }
                $(this).removeClass('owl-carousel');
            } else {
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
                    nav:false,
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
        })
    }
      
    sixIconsCarousel();
    $(window).resize(sixIconsCarousel);

});
