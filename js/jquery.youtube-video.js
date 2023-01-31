(function($) {
	// Inject YouTube iframe API script
	let tag = document.createElement('script'),
		script_tag = document.getElementsByTagName('script')[0];
	tag.src = 'https://www.youtube.com/iframe_api';
	script_tag.parentNode.insertBefore(tag, script_tag);

	let promo = null,
		teaser = null,
		first_video = false,
		yt_video = []; // Array of YouTube video objects

	// YouTube iframe API callback event
	window.onYouTubeIframeAPIReady = function() {
		function youtube_create_iframe(container, play) {
			if (typeof play == 'undefined') play = false;
			var index = yt_video.length;
			var iframe_container = container.find('div.iframe-container');
			iframe_container.attr('id', 'youtube-iframe-' + container.data('index'));
			yt_video[container.data('index')] = new YT.Player('youtube-iframe-' + container.data('index'), {
				height: '100%',
				width: '100%',
				playerVars: {
					controls: 0,
					showinfo: 0,
					rel: 0,
					fs: 0,
					autohide: 1,
					wmode: 'opaque',
					enablejsapi: 0,
					mute: container.data('mute'),
					loop: container.data('loop'),
					modestbranding: 1
				},
				videoId: container.data('video'),
				events: {
					onReady: function(e) {
						// Preload video, pause, seek to first frame, and setup playback options
						// e.target.cueVideoById(container.data('video'));
						e.target.loadVideoById(container.data('video'));
						e.target.pauseVideo();
						e.target.seekTo(0, true);
						e.target.setPlaybackQuality('hd1080');
						if (container.data('autoplay') || play) {
							e.target.playVideo();
						}
						container.addClass('embedded');
						container.attr('end', e.target.getDuration());
						e.target.setVolume(70); // Default volume
						container.find('.volume').css('width', '70%');
					},
					onStateChange: function(e) {
						var video = yt_video[container.data('index')];
						switch (e.data) {
							case YT.PlayerState.BUFFERING:
								video.setPlaybackQuality('hd720');
								break;
							case YT.PlayerState.PLAYING:
								container.addClass('playing');
								// Time track
								setInterval(function() {
									if (!container.hasClass('paused')) {
										container.find('div.time').css('width', parseFloat(e.target.getCurrentTime() / e.target.getDuration() * 100).toString() + '%');
									}
								}, 500);
								break;
							case YT.PlayerState.PAUSED:
								container.removeClass('playing').addClass('paused');
								break;
							case YT.PlayerState.ENDED:
								container.removeClass('playing');
								if (container.data('loop')) {
									video.playVideo();
								} else {
									video.seekTo(0);
									video.pauseVideo();
								}
								break;
						}
					}
				}
			});
		}

		// Turn .youtube-container classes into objects used by this YouTube iframe API integration
		$('.youtube-container').each(function(index, value) {
			$(this).data('index', index);
			// if ($(this).data('autoplay') && !$('body').hasClass('mobile-device') && $(window).width() >= 1200) {
				youtube_create_iframe($(this));
			// }
			$(this).find('div.controls > a.fullscreen').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$('html').toggleClass('fullscreen-video');
				$('.youtube-container').removeClass('active');
				$(value).addClass('active');
			});
			$(this).find('div.controls > a.rewind').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				let video = yt_video[$(this).closest('.youtube-container').data('index')];
				video.seekTo(0);
			});
			$(this).find('div.controls > div.track').on('click', function(e) {
				e.stopPropagation();
				let video = yt_video[$(this).closest('.youtube-container').data('index')],
					seek_multiplier = parseFloat((e.pageX - $(this).offset().left) / $(this).width());
				video.seekTo(video.getDuration() * seek_multiplier);
			});
			$(this).find('div.controls > a.volume-down').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				let video = yt_video[$(this).closest('.youtube-container').data('index')],
					volume = video.getVolume() - 10;
				video.setVolume(volume);
				$(this).closest('.youtube-container').find('div.volume').css('width', volume + '%');
			});
			$(this).find('div.controls > div.volume-track').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				let video = yt_video[$(this).closest('.youtube-container').data('index')],
					volume = parseFloat((e.pageX - $(this).offset().left) / $(this).width() * 100);
				video.setVolume(volume);
				$(this).closest('.youtube-container').find('div.volume').css('width', volume + '%');
			});
			$(this).find('div.controls > a.volume-up').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				let video = yt_video[$(this).closest('.youtube-container').data('index')],
					volume = video.getVolume() + 10;
				video.setVolume(volume);
				$(this).closest('.youtube-container').find('div.volume').css('width', volume + '%');
			});
		});

		// Hide any .youtube-video containers on mobile if they have an absolute position
		if ($('body').hasClass('mobile-device')) {
			$('section.asset-section.youtube-video').each(function(index, value) {
				if ($(this).css('position') == 'absolute') {
					$(this).hide();
				}
			});
		}

		// Do stuff on esc key
		$(document).on('keydown', function(e) {
			e = e || w.event;
			var esc = false;
			esc = 'key' in e ? e.key.match('Esc(ape)') : e.keyCode == 27;
			if (esc) $('html').removeClass('fullscreen-video'); // Toggle off fullscreen video
		});

		// YouTube container click event delegation
		$(document).on('click', '.youtube-container', function(e) {
			var container = $(this).closest('.youtube-container');
			$('.youtube-container').removeClass('active');
			$(container).addClass('active');
			if (container.length) {
				if (container.hasClass('playing')) {
					yt_video[container.data('index')].pauseVideo();
				} else if (container.hasClass('paused')) {
					var played = yt_video[container.data('index')].playVideo();
				} else {
					var played = false;
					if (typeof yt_video[container.data('index')] != 'undefined') {
						played = yt_video[container.data('index')].playVideo();
					}
					if (typeof played != 'object' || played.A == null) {
						terminate_video(container);
						youtube_create_iframe(container, 1);
					}
				}
			}
		});

		function terminate_video(container) {
			container.find('iframe').replaceWith(
				$('<div class="iframe-container">')
			);
		}

		window.yt_video = yt_video;
		// window.youtube_create_iframe = youtube_create_iframe;
	}
})(jQuery);