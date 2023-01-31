(function($) {

    function slideIn() {
        var boxContainer = $('.three-box-slide-in .box-container');

        boxContainer.each(function() {
            if($(this).is(':inview')) {
                if($(this).find('.box.active').length == 0 ) {
                    $(this).find('.box').addClass('active');
                }
            } else {
                $(this).find('.box').removeClass('active');
            }
        })
    }

    $(window).on('scroll resize', slideIn);
})(jQuery)