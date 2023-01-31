$(document).ready(function() {
    function animateImage() {
        $('.animatable').each(function() {
            if($(this).is(':inview')) {
                if(!$(this).hasClass('.in-view')) {
                    $(this).addClass('in-view');
                }
            } else {
                $(this).removeClass('in-view');
            }
        })
    }
    animateImage();
    $(window).on('scroll resize', animateImage);
});
