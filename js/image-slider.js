(function($) {
	let carousel = 'section.slider',
		log = true,
		time = 0,
		init = true,
		paused = false,
		autoplay = true,
		autoplaying = false,
		autoplay_speed = 7000,
		autoplay_interval = false;

	
	function prev_slide() {
		// if (log) console.log('prev_slide()');
		if ($(carousel).find('.images-carousel .img-container.active').prev('.img-container').length) {
			$(carousel).find('.images-carousel .img-container.active').removeClass('active').prev('.img-container').addClass('active');
		} else {
			$(carousel).find('.images-carousel .img-container.active').removeClass('active');
			$(carousel).find('.images-carousel .img-container').last().addClass('active');
		}
	}

	function next_slide() {
		// if (log) console.log('next_slide()');
		if ($(carousel).find('.images-carousel .img-container.active').next('.img-container').length) {
			$(carousel).find('.images-carousel .img-container.active').removeClass('active').next('.img-container').addClass('active');
		} else {
			$(carousel).find('.images-carousel .img-container.active').removeClass('active');
			$(carousel).find('.images-carousel .img-container:first').addClass('active');
		}
	}

	function play() {
		if (log) console.log('play()');
		paused = false;
		if (!init) return false;
		if ($(carousel).find('.last-slide').length) {
			$(carousel).find('.images-carousel .img-container.last-slide').removeClass('last-slide').addClass('active');
		} else {
			$(carousel).find('.images-carousel .img-container').first().addClass('active');
		}
		init = false;
	}

	function stop() {
		// if (log) console.log('stop()');
		$(carousel).find('.images-carousel .img-container.active').removeClass('active').addClass('last-slide');
		init = true;
		autoplaying = false;
	}

	function pause() {
		paused = true;
	}

	if ($(carousel).length) {
		$(carousel).find('.pagination-container .pagination .icon-left-arrow-thick').on('click', function() {
			prev_slide();
		});

		$(carousel).find('.pagination-container .pagination .icon-right-arrow').on('click', function() {
			next_slide();
		});

		$(carousel).on('mouseenter', function() {
			// if (log) console.log('tesimonials hover');
			pause();
		});

		$(carousel).on('mouseleave', function() {
			// if (log) console.log('tesimonials hover out');
			play();
		});

		$(window).on('scroll resize', function() {
			if ($(carousel + ':inview').length) {
				if (init) play();
				if (!autoplaying) {
					// if (log) console.log('playing testimonials autoplay');
					autoplaying = true;
					if (autoplay) {
						autoplay_interval = setInterval(function() {
							if (!paused) next_slide();
						}, autoplay_speed);
					}
				}
			} else {
				if (autoplaying) {
					// if (log) console.log('pausing testimonials autoplay');
					if (autoplay) clearInterval(autoplay_interval);
					autoplaying = false;
				}
				if (!init) pause();
			}
		});
	}
})(jQuery);