(function($) {

    function setAtts() {
        var width = $('.location-tile>.img-container').width();
        $('.location-content').css('padding-top', (width/2) +40);
        if(window.innerWidth < 670) {
            $('.location-tile').css('margin-top', width*.6)
        } else {
            $('.location-tile').css('margin-top', width*.6 > 200 ? 200 : width*.6 )
        }
    }
    setAtts();
    window.onresize =  setAtts;
})(jQuery);