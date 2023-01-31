(function($) {
	let carousel = '.three-bubble-hero .three-bubble-carousel',
		log = false,
		time = 0,
		init = true,
		paused = false,
		autoplay = true,
		autoplaying = false,
		autoplay_speed = 7000,
		autoplay_interval = false;

	function next_slide() {
		// if (log) console.log('next_slide()');
		if ($(carousel).find('.bubble-photo.active').next('.bubble-photo').length) {
			$(carousel).find('.bubble-photo.active').removeClass('active').next('.bubble-photo').addClass('active');
		} else {
			$(carousel).find('.bubble-photo.active').removeClass('active');
			$(carousel).find('.bubble-photo:first').addClass('active');
		}
	}

	function play() {
		// if (log) console.log('play()');
		paused = false;
		if (!init) return false;
		if ($(carousel).find('.last-slide').length) {
			$(carousel).find('.bubble-photo.last-slide').removeClass('last-slide').addClass('active');
		} else {
			$(carousel).find('.bubble-photo').first().addClass('active');
		}

		init = false;
	}

	function stop() {
		// if (log) console.log('stop()');
		$(carousel).find('.bubble-photo.active').removeClass('active').addClass('last-slide');

		init = true;
		autoplaying = false;
	}

	function pause() {
		paused = true;
	}

	if ($(carousel).length) {
		$(carousel).on('mouseenter', function() {
			// if (log) console.log('tesimonials hover');
			pause();
		});
		$(carousel).on('mouseleave', function() {
			// if (log) console.log('tesimonials hover out');
			play();
		});

		$(window).on('load scroll resize', function() {
			if (window.innerWidth <= 670) {
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