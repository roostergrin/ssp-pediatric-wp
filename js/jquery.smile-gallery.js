(function($) {
	function compareImages(img) {
		var slider, sliderbar, img, clicked = 0, w, h;

		/* Get the width and height of the img element */
		w = img.parentElement.offsetWidth;
		h = img.parentElement.offsetHeight;

		/* Set the width of the img element to 50%: */
		img.style.width = (w / 2) + "px";

		/* Create slider: */
		slider = document.createElement("DIV");
		slider.setAttribute("id", "img-comp-slider");

		/* Create sliderbar: */
		sliderbar = document.createElement("DIV");
		sliderbar.setAttribute("id", "img-comp-slider-bar");

		/* Insert slider */
		img.parentElement.insertBefore(slider, img);
		slider.parentElement.insertBefore(sliderbar, slider);

		/* Position the slider in the middle: */
		slider.style.top = (h / 2) - (slider.offsetHeight / 2) + "px";
		slider.style.left = (w / 2) - (slider.offsetWidth / 2) + "px";
		sliderbar.style.left = (w / 2) - (sliderbar.offsetWidth / 2) + "px";

		/* Execute a function when the mouse button is pressed: */
		slider.addEventListener("mousedown", slideReady);

		/* And another function when the mouse button is released: */
		window.addEventListener("mouseup", slideFinish);

		/* Or touched (for touch screens: */
		slider.addEventListener("touchstart", slideReady);

		/* And released (for touch screens: */
		window.addEventListener("touchend", slideFinish);

		function slideReady(e) {
			/* Prevent any other actions that may occur when moving over the image: */
			e.preventDefault();
			/* The slider is now clicked and ready to move: */
			clicked = 1;
			/* Execute a function when the slider is moved: */
			window.addEventListener("mousemove", slideMove);
			window.addEventListener("touchmove", slideMove);
		}

		function slideFinish() {
			/* The slider is no longer clicked: */
			clicked = 0;
		}

		function slideMove(e) {
			var pos;
			/* If the slider is no longer clicked, exit this function: */
			if (clicked == 0) return false;
			/* Get the cursor's x position: */
			pos = getCursorPos(e)
			/* Prevent the slider from being positioned outside the image: */
			if (pos < 0) pos = 0;
			if (pos > w) pos = w;
			/* Execute a function that will resize the overlay image according to the cursor: */
			slide(pos);
		}

		function getCursorPos(e) {
			var a, x = 0;
			e = e || window.event;
			/* Get the x positions of the image: */
			a = img.getBoundingClientRect();
			/* Calculate the cursor's x coordinate, relative to the image: */
			x = e.pageX - a.left;
			
			if(e.touches && e.touches[0]) x = e.touches[0].pageX - a.left;
				
			/* Consider any page scrolling: */
			x = x - window.pageXOffset;
			return x;
		}

		function slide(x) {
			/* Resize the image: */
			img.style.width = x + "px";
			/* Position the slider: */
			slider.style.left = img.offsetWidth - (slider.offsetWidth / 2) + "px";
			sliderbar.style.left = img.offsetWidth - (sliderbar.offsetWidth / 2) + "px";
		}
	}

	function initComparisons() {
		var curSlider, curSliderbar, x, i;

		/* Remove slider if exists: */
		curSlider = document.getElementById("img-comp-slider");
		curSliderbar = document.getElementById("img-comp-slider-bar");
		if (curSlider !== null) curSlider.remove();
		if (curSliderbar !== null) curSliderbar.remove();

		/* Find all elements with an "overlay" class: */
		x = document.getElementsByClassName("img-comp-overlay");
		for (i = 0; i < x.length; i++) {
			/* Once for each "overlay" element:
			pass the "overlay" element as a parameter when executing the compareImages function: */
			compareImages(x[i]);
		}
	}

	$(window).on('load resize', function() {
		initComparisons();
	});

	var smile_gallery_owl = $('.smile-gallery .owl-carousel');
	smile_gallery_owl.owlCarousel({
		items: 1,
		slideBy: 1,
		margin: 10,
		nav: true,
		navText: [],
		dots: false,
		loop: true,
		center: true,
		autoWidth: true,
		mouseDrag: true,
		pullDrag: true,
		freeDrag: false,
	});

	smile_gallery_owl.on('translated.owl.carousel', function(e) {
		$('ul.before-images li.img.active').removeClass('active');
		var pos = $('.smile-gallery .owl-item.active.center img').data('position');
		$('.smile-gallery ul.before-images img[data-position='+pos+']').parent().addClass('active');

		var container = $('.gallery .img-comp-container');
		var content = '<div class="img-comp-img"><img src="'+$('.smile-gallery .owl-item.active.center img').data('after')+'" width="780" height="460" class="" alt="" /><p><strong>After</strong></p></div>';
		content += '<div class="img-comp-img img-comp-overlay"><img src="'+$('.smile-gallery .owl-item.active.center img').data('before')+'" width="780" height="460" class="" alt="" /><p><strong>Before</strong></p></div>';
		container.html(content);
		initComparisons();
	});

	// Clicking on a gallery img (Desktop)
	$('ul.before-images li.img img').on('click', function() {
		$('ul.before-images li.img.active').removeClass('active');
		$(this).parent().addClass('active');
 
		$('.gallery .img-comp-container').empty().append(
			$('<div class="img-comp-img">').append(
				$('<img src="' + $(this).data('after') + '" width="780" height="460" alt="' + $(this).attr('alt').replace('Before', 'After') + '">'),
				$('<p>').html('<strong>After</strong>')
			),
			$('<div class="img-comp-img img-comp-overlay">').append(
				$('<img src="' + $(this).data('before') + '" width="780" height="460" alt="' + $(this).attr('alt') + '">'),
				$('<p>').html('<strong>Before</strong>')
			),
		);
		initComparisons();

		// Handle mobile active img
		var pos = $(this).data('position') - 1;
		smile_gallery_owl.trigger('to.owl.carousel', [pos]);
	});
})(jQuery);
