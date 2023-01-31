(function($) {
	let
		scroll_position = 0,
		scroll_direction = 'down',
		desktop_header = $('section.header > .content')
	;

	$('body').on('click', 'a.openchair-widget', function(e) {
		if(typeof OpenChair !== 'undefined') e.preventDefault();
	});

	// Extend jQuery object inview selectors
	$.extend($.expr[':'], {
		topinview:function(el) {
			let wintop = $(window).scrollTop(),
				winbtm = $(window).scrollTop() + $(window).height(),
				eltop = $(el).offset().top,
				elbtm = $(el).offset().top + $(el).outerHeight();
			return winbtm >= eltop && wintop <= elbtm && scroll_direction == 'down';
		}
	});

	$.extend($.expr[':'], {
		bottominview:function(el) {
			let wintop = $(window).scrollTop(),
				winbtm = $(window).scrollTop() + $(window).height(),
				eltop = $(el).offset().top,
				elbtm = $(el).offset().top + $(el).outerHeight();
			return wintop <= elbtm && winbtm >= eltop && scroll_direction == 'up';
		}
	});

	$.extend($.expr[':'], {
		inview:function(el) {
			let wintop = $(window).scrollTop(),
				winbtm = $(window).scrollTop() + $(window).height(),
				eltop = $(el).offset().top,
				elbtm = $(el).offset().top + $(el).outerHeight();
			return (wintop <= elbtm && winbtm >= eltop && scroll_direction == 'up') || (winbtm >= eltop && wintop <= elbtm && scroll_direction == 'down');
		}
	});

	$.extend($.expr[':'], {
		inview300:function(el) {
			let wintop = $(window).scrollTop(),
				winbtm = $(window).scrollTop() + $(window).height(),
				eltop = $(el).offset().top + 300,
				elbtm = $(el).offset().top + 300 + $(el).height();
			return elbtm >= wintop && eltop <= winbtm;
		}
	});

	$.extend($.expr[':'], {
		inview600:function(el) {
			let wintop = $(window).scrollTop(),
				winbtm = $(window).scrollTop() + $(window).height(),
				eltop = $(el).offset().top + 600,
				elbtm = $(el).offset().top + 600 + $(el).height();
			return elbtm >= wintop && eltop <= winbtm;
		}
	});

	if(window.objectFitImages) objectFitImages($('img.bg-img'));

	$(window).on('scroll', function() {
		scroll_direction = $(window).scrollTop() > scroll_position ? 'down' : 'up';
		scroll_position = $(window).scrollTop();
	});

	$.extend($.easing, {
		easeOutCubic: function (x, t, b, c, d) {
			return c*((t=t/d-1)*t*t + 1) + b;
		},
	});

	$(document).on('click', 'a[href="#"]', function(e) {
		e.preventDefault();
	});

	$('.hero-scroll').on('click', function(e) {
		e.preventDefault();
		scrollToPosition($('.hero').innerHeight());
	});

	$('a.candidate-iframe-src').on('click', function(e) {
		e.preventDefault();
		$('.iframe.candidate-iframe iframe').attr('src', $(this).attr('href'));
		scrollToTarget($('#all'));
	});

	///////////// SMOOTH SCROLL FOR ANCHORS /////////////
	$(document).on('click', 'a[href*="#"]:not([href="#"])', function() {
		if(window.location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && window.location.hostname == this.hostname) {
			var target = $(this.hash);
			// console.log('target hash', target);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			// console.log('target', target);
			if(target.length) {
				scrollToTarget(target);
				return false;
			}
		}
	});

	$(window).on('scroll resize', function() {
		$('.owl-carousel:inview300').trigger('play.owl.autoplay');
		$('.owl-carousel:not(:inview300)').trigger('stop.owl.autoplay');
	});

	$(window).on('load', function(e) {
		if(location.href.split("#")[1]) {
			var anchor = location.href.split("#")[1];
			var target = $('#'+anchor+', a[name="'+anchor+'"]');
			if(target.length) {
				e.preventDefault();
				scrollToTarget(target);
				return false;
			}
		}
	});

	function scrollToPosition(position) {
		$('html, body').animate({
			scrollTop: (position + ($('#wpadminbar').outerHeight()||0))
		}, {
			easing: 'easeOutCubic',
			duration: 1000
		});
	}


	function getScrollTopCalculation(target) {
		var header = $(window).width() <= 782 ? $('#mobile-navigation') : $('section.header > .content');
		return Math.floor(target.offset().top - header.outerHeight() - ($('#wpadminbar').outerHeight()||0) - 120);
	}
	function scrollToTarget(target) {
		if ($(window).width() < 782) {
			var header = $('#mobile-header');
			var utilityHeader = $('.header-utility');
			$('html, body').animate({
				scrollTop:
				target[0].getBoundingClientRect().y + $(window).scrollTop()
				- parseInt((target.css('margin-top')).replace('px', ''))
				- header.outerHeight()
				- utilityHeader.outerHeight()
				- ($('#wpadminbar').outerHeight()||0)
				+ ($('.hero .conversation').length ? ((1 - window.hero_scroll_progress)||0) * (window.hero_scroll_delta||0) : 0)
			}, 250);
		} else {
			$('html, body').animate({
				scrollTop: getScrollTopCalculation(target)
			}, 250);
			if ($(window).width() <= 782) $('html').removeClass('mobileMenu mobileNavigating');
		}
	}

	// NAVIGATION //
	$('.super-nav > ul > li.menu-item-has-children > a').on('click', function(e) {
		e.preventDefault();
		$('.super-nav > ul > li.menu-item-has-children.active').not($(this).parent()).removeClass('active');
		$(this).parent().toggleClass('active');
		$('li.expand-active').toggleClass('supernav-inactive', $('.super-nav li.active').length > 0);
	});

	// SERVICE TILES //
	$('ul.service-tiles > li').hover(function() {
		$(this).parent().addClass('hover');
	}, function() {
		$(this).parent().removeClass('hover');
	});

	$('.primary-nav > ul > li').hover(function() {
		$(this).parent().children('li').not($(this)).addClass('inactive');
	}, function() {
		$(this).parent().children('li').not($(this)).removeClass('inactive');
	});

	// STICKY //
	function onPageChanged() {
		if(typeof($('section.header').data('padding')) !== 'undefined') {
			$('section.header').data('padding', parseInt($('section.header').css('padding-top').replace('px', '')));
		}
		//$('section.header').data('padding')
		$('html').toggleClass('sticky-header', $(window).scrollTop() > $('section.header').data('padding-top'));
		var header_height = ((desktop_header.outerHeight()||0) + ($('#wpadminbar').outerHeight()||0));

		// CTA - header-based
		$('.cta.stick-header').each(function() {
			$(this).toggleClass('stuck', $(this).parent().get(0).getBoundingClientRect().top <= header_height);

			if($(this).hasClass('stuck') && !$(this).hasClass('insights-cta')) {
				$(this).attr('style', 'top:'+header_height+'px!important');
			}
			else {
				$(this).removeAttr('style');
			}
		});

		var header_copy_selector = $('section.hero > .content');
		if(header_copy_selector.length) {
			$('html').toggleClass('solid-header', header_copy_selector.get(0).getBoundingClientRect().top <= header_height);
		}

		// Widgets
		$('aside > .widgets').css('top', ((4*12) + 2*(desktop_header.outerHeight()||0) + ($('#wpadminbar').outerHeight()||0)));
	}

	if ($('body').hasClass('page-template-virtual-monitoring')) {
		$('section.video-full-with-text .image-container').on('click', function() {
			if (!$(this).find('.video-container').length) {
				$('section.video-full-with-text .image-container').addClass('hidden');
				$('section.video-full-with-text .content-container').addClass('hidden');
				$('section.video-full-with-text .inner-content').append('<div class="video-container"><iframe width="1170" height="678" src="https://www.youtube.com/embed/K3uSUdiSg0g?rel=0&controls=2&autoplay=1&showinfo=0&modestbranding=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');
			}
		});
	}

	if ($('body').hasClass('page-template-invisalign-aligners')) {
		$('section.invisalign-carousel .column').toggleClass('active', $('section.invisalign-carousel:inview').length);
		$('section.faq-boxes .boxes .box a.mobile:not(.hide)').on('click', function() {
			$(this).parent().addClass('active');
		});

		$('section.faq-boxes .boxes .box a.mobile.hide').on('click', function() {
			$(this).parent().removeClass('active');
		});
	}

	if(typeof($('section.header').data('padding-top')) !== 'undefined') {
		$('section.header').data('padding-top', parseInt($('section.header').css('padding-top').replace('px', '')||0));
	}
	$(document).on('ready', function() { onPageChanged(); });
	$(window).on('scroll load resize', function() {
		onPageChanged();

		if ($('body').hasClass('page-template-virtual-monitoring')) {
			if ($(window).width() > 949) {
				// Step 1
				if ($('.steps-with-lines .step-one:inview').length > 0) {
					$('.steps-with-lines .step-one .image').addClass('active');
				}
				else {
					$('.steps-with-lines .step-one .image').removeClass('active');
				}
				// Step 2
				if ($('.steps-with-lines .step-two:inview').length > 0) {
					$('.steps-with-lines .step-two .image').addClass('active');
				}
				else {
					$('.steps-with-lines .step-two .image').removeClass('active');
				}
				// Step 3
				if ($('.steps-with-lines .step-three:inview').length > 0) {
					$('.steps-with-lines .step-three .image').addClass('active');
				}
				else {
					$('.steps-with-lines .step-three .image').removeClass('active');
				}
			}
		}

		if ($('body').hasClass('page-template-invisalign-aligners')) {
			if ($('section.invisalign-carousel:inview').length > 0) {
				$.each($('section.invisalign-carousel .column'), function (k, e) {
					setTimeout(function () {
						$(e).addClass('active');
						$(e).css('opacity', '1');
					}, 1000)
				})
			}
			else {
				$.each($('section.invisalign-carousel .column'), function(k, e) {
					$(e).removeClass('active');
					$(e).css('opacity', '0');
				})
			}

			if ($(window).width() > 949) {
				if ($('.hero.full .content-container.invisalign.animate-in:inview300').length > 0) {
					$('html').addClass('time-for-content');
				} else {
					$('html').removeClass('time-for-content');
				}
			}
		}

		if ($('body').hasClass('page-template-location-home') || $('body').hasClass('page-template-patient-care-philosophy') || $('body').hasClass('page-template-brand-home')) {
			if ($('.hero.full .content-container.team.animate-in:inview300').length > 0) {
				$('html').addClass('time-for-content');
			} else {
				$('html').removeClass('time-for-content');
			}

			if ($(window).width() > 782) {
				if ($('.giving-back .main-container:inview').length > 0) {
					$('.giving-back .main-container .content-container').css('left', '0');
					$('.giving-back .main-container .img-container').css('right', '0');
				} else {
					$('.giving-back .main-container .content-container').css('left', '-600px');
					$('.giving-back .main-container .img-container').css('right', '-680px');
				}
			}
		}

		if ($('body').hasClass('page-template-patient-care-philosophy')) {
			if ($('.three-cols-cards:inview300').length > 0) {
				$('.three-cols-cards .card:nth-child(1)').addClass('active');
				setTimeout(function(){ $('.three-cols-cards .card:nth-child(2)').addClass('active'); },500);
				setTimeout(function(){ $('.three-cols-cards .card:nth-child(3)').addClass('active'); },1000);
			} else {
				$('.three-cols-cards .card').removeClass('active');
			}
		}

		if ($('body').hasClass('page-template-why-orthodontic-treatment')) {
			if ($('.service.for-ages.first .container:inview300').length > 0) {
				$('.service.for-ages.first .container aside .img-container img').addClass('active');
			} else {
				$('.service.for-ages.first .container aside .img-container img.active').removeClass('active');
			}

			if ($('.service.for-ages.second .container:inview300').length > 0) {
				$('.service.for-ages.second .container aside .img-container img').addClass('active');
			} else {
				$('.service.for-ages.second .container aside .img-container img.active').removeClass('active');
			}

			if ($('.service.for-ages.third .container:inview300').length > 0) {
				$('.service.for-ages.third .container aside .img-container img').addClass('active');
			} else {
				$('.service.for-ages.third .container aside .img-container img.active').removeClass('active');
			}

			if ($('.copy.with-image.first .main-container:inview300').length > 0) {
				$('.copy.with-image.first .main-container aside .img-container img').addClass('active');
				$('.copy.with-image.first .main-container article p.slide').addClass('active');
			} else {
				$('.copy.with-image.first .main-container aside .img-container img.active').removeClass('active');
				$('.copy.with-image.first .main-container article p.slide').removeClass('active');
			}

			if ($('.copy.with-image.second .main-container:inview300').length > 0) {
				$('.copy.with-image.second .main-container aside .img-container img').addClass('active');
				$('.copy.with-image.second .main-container article p.slide').addClass('active');
			} else {
				$('.copy.with-image.second .main-container aside .img-container img.active').removeClass('active');
				$('.copy.with-image.second .main-container article p.slide').removeClass('active');
			}
			/* Make graph on services overview page static
			if ($('.three-fifths.why-orthodontic-treatment .main-container:inview600').length > 0) {
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-1 .level-1').addClass('active');
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-1 .heading').addClass('active');

				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-2 .level-1').addClass('active');
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-2 .level-2').addClass('active');
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-2 .heading').addClass('active');
			} else {
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-1 .level-1.active').removeClass('active');
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-1 .heading.active').removeClass('active');

				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-2 .level-1.active').removeClass('active');
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-2 .level-2.active').removeClass('active');
				$('.three-fifths.why-orthodontic-treatment .main-container aside.graph-container .graph-2 .heading.active').removeClass('active');
			}
			*/
		}

		// two-cols-carousel-with-image
		if ($('body').hasClass('page-template-safer-orthodontic-care')) {
			if ($('.two-cols-carousel-with-image.first .main-container:inview300').length > 0) {
				$('.two-cols-carousel-with-image.first .main-container aside .img-container img').addClass('active');
			} else {
				$('.two-cols-carousel-with-image.first .main-container aside .img-container img.active').removeClass('active');
			}

			if ($('.two-cols-carousel-with-image.second .main-container:inview300').length > 0) {
				$('.two-cols-carousel-with-image.second .main-container aside .img-container img').addClass('active');
			} else {
				$('.two-cols-carousel-with-image.second .main-container aside .img-container img.active').removeClass('active');
			}

			if ($('.two-cols-carousel-with-image.third .main-container:inview300').length > 0) {
				$('.two-cols-carousel-with-image.third .main-container aside .img-container img').addClass('active');
			} else {
				$('.two-cols-carousel-with-image.third .main-container aside .img-container img.active').removeClass('active');
			}

			if ($('.two-cols-carousel-with-image.fourth .main-container:inview300').length > 0) {
				$('.two-cols-carousel-with-image.fourth .main-container aside .img-container img').addClass('active');
			} else {
				$('.two-cols-carousel-with-image.fourth .main-container aside .img-container img.active').removeClass('active');
			}
		}

		if ($(window).width() > 670) {
			if ($('section.footer:inview').length > 0) {
				$('section.footer .widget.schedule-consultation').css('right', '0px');
				$('section.footer .widget.schedule-consultation').css('opacity', '1');
			} else {
				$('section.footer .widget.schedule-consultation').css('right', '-800px');
				$('section.footer .widget.schedule-consultation').css('opacity', '0');
			}
		} else {
			$('section.footer .widget.schedule-consultation').css('right', '0px');
			$('section.footer .widget.schedule-consultation').css('opacity', '1');
		}
        
        if ($(window).width() < 375) {
            $('section.footer .widget.schedule-consultation').css('left', '-28px');
        }

		if ($('section').hasClass('instruction-downloads')) {
			if ($(window).width() > 782) {
				if ($('ul.icons.cols-4:inview, ul.icons.cols-3:inview').length > 0) {
					$.each($('ul.icons.cols-4 li, ul.icons.cols-3 li'), function (k, e) {
						setTimeout(function () {
							$(e).addClass('active');
						}, 1000)
					})
				}
				else {
					$.each($('ul.icons.cols-4 li, ul.icons.cols-3 li'), function(k,e) {
						//$(e).removeClass('active');
					})
				}
			} else {
				$.each($('ul.icons.cols-4 li, ul.icons.cols-3 li'), function() {
					if ( $(this).is(':inview') ) {
						$(this).addClass('active')
					} else {
						//$(this).removeClass('active')
					}
				})
			}
		}

		if ($('body').hasClass('page-template-braces')) {
			if ($('.hero.full .content-container.straight-talk.animate-in:inview300').length > 0) {
				// console.log('in view')
				$('html').addClass('time-for-content');
			} else {
				// console.log('out of view')
				$('html').removeClass('time-for-content');
			}
		}

	});

	$('.filter-toggle').on('click', function() {
		  $(this).closest('.filter').toggleClass('active').parent().toggleClass('active');
	});

	$(window).on('resize', function() {
		if($(window).width() > 782) {
			$('.filters-container').show();
		}
	});

	$('#virtual-consultation ul li a').on('click', function(e) {
		e.preventDefault();
		let link = $(this).attr('id');
		let text = $(this).html();
		$('#start-vc').removeClass('hidden').attr("href", link);
		$('.fancy-select-title').html(text);
		$('.options').addClass('hidden');
	});

	$('section.maps .location-info .links > div').on('mouseenter', function(e) {
		$(this).parent().children('.active').removeClass('active');
		$(this).addClass('active');
	});

	$('section.maps .location-info .links > div').on('mouseleave', function(e) {
		$(this).parent().parent().find('.links > div.active').removeClass('active');
	});

	$(window).trigger('resize');

	$('.carousel-item .content a.read-more.mobile').on('click', function(key, element) {
		$(this).parent().parent().children('p.primary.desktop').removeClass('desktop');
		$(this).parent().parent().children('p.primary.mobile').css('opacity', 0);
		$(this).parent().parent().children('a.read-less.mobile').addClass('active');

	});

	$('.carousel-item .content a.read-less.mobile').on('click', function(key, element) {
		$(this).removeClass('active');
		$(this).parent().children('p.primary:not(.name)').first().addClass('desktop');
		$(this).parent().children('p.primary.mobile').css('opacity', 1);

	});

	// Strip empty P tags from carriage returns in the content
	$('p:empty'). remove();
})(jQuery);
