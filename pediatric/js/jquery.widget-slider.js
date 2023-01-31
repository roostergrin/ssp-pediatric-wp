(function($) {
	var 
		slide_indicators = $('.widget.slider .pagination > ul').children('li'),
		slides = $('.widget.slider ul.slides').children('li'),
		progress_timeline = null
	;
	
	function advanceSlider() {
		var index = slides.filter('.active').index() + 1;
		if(slides.eq(index).length == 0) index = 0;
		goToSlide(index);
	}
	
	function goToSlide(index, force_animation) {
		force_animation = force_animation || false;
		
		if(slide_indicators.eq(index).hasClass('active') && !force_animation) return;
		
		$('.widget.slider ul.slides').addClass('active');
		
		slide_indicators.filter('.active').removeClass('active');
		slides.filter('.active').removeClass('active');
		
		slide_indicators.eq(index).addClass('active');
		slides.eq(index).addClass('active');
		
		var progress = slides.eq(index).find('.progress');

		if(progress_timeline !== null) progress_timeline.kill();
		progress_timeline = new TimelineMax({repeat:0, delay:0, onComplete: function() {
			advanceSlider();
		}})
			.set(progress, {height:0})
			.to(progress, parseInt(slides.eq(index).attr('data-duration')), {ease: Power1.easeInOut, height:'100%'})
		;
	}
	
	function onSliderPageChanged() {
		if($('.widget.slider ul.slides.active').length == 0 && $('.widget.slider ul.slides').is(':inview')) {
			$('.widget.slider ul.slides').addClass('active');
			goToSlide(0, true);
		}
	}
	
	if(slide_indicators.length > 1) {
		slide_indicators.on('click', function(e) {
			e.preventDefault();
			goToSlide($(this).index());
		});
	}
	
	onSliderPageChanged();
	$(window).on('scroll load resize', function() { onSliderPageChanged();  });
})(jQuery); 