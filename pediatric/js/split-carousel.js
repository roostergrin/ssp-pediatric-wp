(function($) {
	let carousel = 'section.split-carousel',
		log = true,
		time = 0,
		init = true,
		paused = false,
		autoplay = true,
		autoplaying = false,
		autoplay_speed = 5000,
		autoplay_interval = false;

	function update_height() {
		// $(carousel).find('.content-carousel').css('height', $(carousel).find('.content-carousel .carousel-item.active > .content').outerHeight());
	}
	
	function prev_slide() {
		// if (log) console.log('prev_slide()');
		if ($(carousel).find('.content-carousel .carousel-item.active').prev('.carousel-item').length) {
			$(carousel).find('.content-carousel .carousel-item.active').removeClass('active').prev('.carousel-item').addClass('active');
			$(carousel).find('.images-carousel .img-container.active').removeClass('active').prev('.img-container').addClass('active');
		} else {
			$(carousel).find('.content-carousel .carousel-item.active').removeClass('active');
			$(carousel).find('.images-carousel .img-container.active').removeClass('active');
			$(carousel).find('.content-carousel .carousel-item').last().addClass('active');
			$(carousel).find('.images-carousel .img-container').last().addClass('active');
		}
		update_height();
	}

	function next_slide() {
		// if (log) console.log('next_slide()');

		if ($(carousel).find('.content-carousel .carousel-item.active').next('.carousel-item').length) {
			$(carousel).find('.content-carousel .carousel-item.active').removeClass('active').next('.carousel-item').addClass('active');
			$(carousel).find('.images-carousel .img-container.active').removeClass('active').next('.img-container').addClass('active');
		} else {
			$(carousel).find('.content-carousel .carousel-item.active').removeClass('active');
			$(carousel).find('.images-carousel .img-container.active').removeClass('active');
			$(carousel).find('.content-carousel .carousel-item:first').addClass('active');
			$(carousel).find('.images-carousel .img-container:first').addClass('active');
		}
		update_height();
	}

	function play() {
		// if (log) console.log('play()');
		paused = false;
		if (!init) return false;
		if ($(carousel).find('.last-slide').length) {
			$(carousel).find('.content-carousel .carousel-item.last-slide').removeClass('last-slide').addClass('active');
			$(carousel).find('.images-carousel .img-container.last-slide').removeClass('last-slide').addClass('active');
		} else {
			$(carousel).find('.content-carousel .carousel-item').first().addClass('active');
			$(carousel).find('.images-carousel .img-container').first().addClass('active');
		}
		update_height();
		init = false;
	}

	function stop() {
		// if (log) console.log('stop()');
		$(carousel).find('.content-carousel .carousel-item.active').removeClass('active').addClass('last-slide');
		$(carousel).find('.images-carousel .img-container.active').removeClass('active').addClass('last-slide');
		update_height();
		init = true;
		autoplaying = false;
	}

	function pause() {
		paused = true;
	}

	if ($(carousel).length) {
		$(carousel).find('.pagination-container .pagination .page-left').on('click', function() {
			prev_slide();
		});

		$(carousel).find('.pagination-container .pagination .page-right').on('click', function() {
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
					// if (log) console.log('playing autoplay');
					autoplaying = true;
					if (autoplay) {
						autoplay_interval = setInterval(function() {
							if (!paused) next_slide();
						}, autoplay_speed);
					}
				}
			} else {
				if (autoplaying) {
					// if (log) console.log('pausing autoplay');
					if (autoplay) clearInterval(autoplay_interval);
					autoplaying = false;
				}
				if (!init) pause();
			}

			update_height();

			/**
			 * Jake Schaap - 8/3/22
			 * 
			 * some FAQs don't have Read More cta links
			 * 
			 * what this function does is truncates the length
			 * of longer FAQ body content on mobile, but doesn't provide 
			 * a way for the mobile user to read the full FAQ
			 * 
			 * to avoid having building out additional functionality
			 * at this time, I am commenting this out for now
			 */
			// if(window.innerWidth <= 425) {
			// 	$('.carousel-item').each(function() {							
			// 		var copyText = $(this).find('.copy>p').text(),
			// 			length = 150,
			// 			shortCode = findShortcodeInBodyText($(this).find('.copy>p').text());					

			// 		if(copyText.length > length) {
			// 			var shortText = copyText.substring(0,length);

			// 			if(shortText.charAt(100) == ' ') {
			// 				shortText = shortText.substring(0,length-1)
			// 			}

			// 			shortText += '…'
			// 			$(this).find('.copy>p').text(shortText);

			// 			if(shortCode) {
			// 				$(this).find('.copy>p').append(shortCode);
			// 			}
			// 		} 
			// 	})		
		
			// }
		});

	}

	function findShortcodeInBodyText(text) {
		var openBracketIndex = text.indexOf('['),
			closeBracketIndex = text.indexOf(']'),
			shortcode = '';

		if(openBracketIndex !== -1 && closeBracketIndex !== -1) {
			shortcode = text.substring(openBracketIndex, closeBracketIndex+1);
		}

		return shortcode;
	}

})(jQuery);