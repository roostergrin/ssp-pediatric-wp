(function($) {
	$(".btn.menu").click(function() {
		$(window).scrollTop(0);
		$('html').removeClass('contact-form');
		$("html").toggleClass("mobileMenu mobileNavigating");
		$("html").addClass("sticky-header");
		$('.btn.menu .hamburger--squeeze').toggleClass('is-active');
	});
	/* function onMobileNavSlideComplete(submenu) {
	 	if($('#mobile-navigation .primary-nav ul.sub-menu.expanded').length > 0) {
	 		var top = submenu.parent().offset().top - $('.menu').offset().top;
	 		$('#mobile_navigation').animate({scrollTop: top}, 200, 'linear');
	 	}
	 	else $('#mobile_navigation').animate({scrollTop: 0}, 200, 'linear');
	 }
	 var max_submenu_depth = 0;
	 var depth = -1;
	 $('#mobile-navigation .primary-nav ul.sub-menu').each(function() {
	 	var depth = $(this).parent().parentsUntil('#menu-main-menu > ul', 'ul').length;
	 	$(this).data('depth', depth);
	 	if(depth > max_submenu_depth) max_submenu_depth = depth;
	 });
	 $('#mobile-navigation > ul > li.menu-item-has-children > a').click(function(e) {
	 	if($(window).width() < 768) e.preventDefault();
	 	var submenu = $(this).parent().find('> .sub-menu');
	 	depth = $(this).parent().parentsUntil('.menu > ul', 'ul').length;

	 	if(submenu.length) {
	 		$('#mobile-navigation .primary-nav ul.sub-menu.expanded').each(function() {
	 			if(!$(this).is(submenu) && $(this).data('depth') >= depth) {
	 				$(this).slideUp(0).removeClass('expanded');
	 				$(this).closest('li').removeClass('menu-item-expanded');
	 			}
	 		});
	 		submenu.toggleClass('expanded');
	 		submenu.closest('li').toggleClass('menu-item-expanded');
	 		if(submenu.hasClass('expanded')) submenu.slideDown(100, function() { onMobileNavSlideComplete(submenu); });
	 		else submenu.slideUp(100, function() { onMobileNavSlideComplete(submenu); });
	 		e.preventDefault();
	 	}
	 });
	 $('#menu-main-menu > li a').click(function(e) {
	 	if($(this).parent().find('.sub-menu').length && $(this).parent().find('.sub-menu li:not(.mobile_only)').length && $('body').hasClass('mobile')) e.preventDefault();
	 });*/
    function onMobileNavSlideComplete(submenu) {
			if($('#mobileNavigation .primary-nav ul.sub-menu.expanded').length > 0) {
				var top = submenu.parent().offset().top - $('.menu').offset().top;
				$('#mobile_navigation').animate({scrollTop: top}, 200, 'linear');
			}
			else $('#mobile_navigation').animate({scrollTop: 0}, 200, 'linear');
		}
		var max_submenu_depth = 0;
		var depth = -1;
		$('#mobileNavigation .primary-nav ul.sub-menu').each(function() { 
			var depth = $(this).parent().parentsUntil('#menu-main-menu > ul', 'ul').length;
			$(this).data('depth', depth);
			if(depth > max_submenu_depth) max_submenu_depth = depth;
		});
		$('#mobileNavigation > ul > li.menu-item-has-children > a').click(function(e) {
            if($(window).width() < 949) e.preventDefault();
			var submenu = $(this).parent().find('> .sub-menu');
			depth = $(this).parent().parentsUntil('.menu > ul', 'ul').length;

			if(submenu.length) {
				$('#mobileNavigation .menu ul.sub-menu.expanded').each(function() {
					if(!$(this).is(submenu) && $(this).data('depth') >= depth) {
						$(this).slideUp(0).removeClass('expanded');
						$(this).closest('li').removeClass('menu-item-expanded');
					} 
				});
				submenu.toggleClass('expanded');
				submenu.closest('li').toggleClass('menu-item-expanded');
				if(submenu.hasClass('expanded')) submenu.slideDown(100, function() { onMobileNavSlideComplete(submenu); });
				else submenu.slideUp(100, function() { onMobileNavSlideComplete(submenu); });
				e.preventDefault();
			}
		});
		$('#menu-main-menu > li a').click(function(e) {
			if($(this).parent().find('.sub-menu').length && $(this).parent().find('.sub-menu li:not(.mobile_only)').length && $('body').hasClass('mobile')) e.preventDefault();
		});
		$('ul.primary-nav > li > a').on('click', function(e) { //> span.dropdown
	        if($(window).width() < 949) {
	            var anchor = $(this).closest('a');
	            var li = $(this).closest('li');
	            if(anchor.next('ul.sub-menu').hasClass('expanded')) {
	                // anchor.children('span.dropdown').text('+');
	                anchor.children('span.dropdown').addClass('icon-plus');
	                anchor.children('span.dropdown').removeClass('icon-close');
	            } else {
	                // anchor.children('span.dropdown').text('-');
	                anchor.children('span.dropdown').addClass('icon-close');
	                anchor.children('span.dropdown').removeClass('icon-plus');
	            }
	            anchor.next('ul.sub-menu').toggleClass('expanded');
	            li.toggleClass('menu-item-expanded');
	            if(li.hasClass('menu-item-has-children')) return false;
	        }
	    });
        $('#header-location-tagline').on('click', function(e) { 
	        if($(window).width() < 949) {
                $(this).toggleClass('active');
                $('#header-mobile-utility-navigation-more-locations').toggleClass('active');
            }
        });
    
        $('.menu-search a').on('click', function(e) { 
	        if($(window).width() < 949) {
                $('.form_site_search').toggleClass('active');
            }
        });
})(jQuery);
