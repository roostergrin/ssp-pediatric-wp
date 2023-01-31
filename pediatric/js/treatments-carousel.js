$(document).ready(function() {

    var treatmentsCarousel = $('#treatments-owl-carousel');

    $(treatmentsCarousel).owlCarousel({
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
        responsiveClass: true,
        responsive: {
            0: {
                stagePadding: 30,
                margin: 30,
                items: 1,
            },
            670: {
                stagePadding: 30,
                margin: 20,
                items: 2,
            },
            1080: {
            stagePadding: 150,
                margin: 30,
                items: 3,
            },
            2150: {
                margin: 30,
                items: 5,

            }
        }
    });

    window.onresize = function() {
        $(treatmentsCarousel).trigger('refresh.owl.carousel');
    }

    treatmentsCarousel.on('changed.owl.carousel', function(e) {
        treatmentsCarousel.trigger('stop.owl.autoplay');
        treatmentsCarousel.trigger('play.owl.autoplay');
    });

    $('section.treatments-carousel .pagination .page-left').click(function() {
        treatmentsCarousel.trigger('prev.owl.carousel');
    });

    $('section.treatments-carousel .pagination .page-right').click(function() {
        treatmentsCarousel.trigger('next.owl.carousel');
    });
});