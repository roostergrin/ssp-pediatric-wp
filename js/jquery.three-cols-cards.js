(function($) {
		function show_card(card) {
			card.addClass('active');
		}

		$(window).on('scroll load resize', function() {
			if (
				$('body').hasClass('page-template-why-orthodontic-treatment') || 
				$('body').hasClass('page-template-patient-care-philosophy')
			) {
				if ($('.three-cols-cards .main-container:inview300').length > 0) {
					show_card($('.three-cols-cards .main-container .card').first());
					window.setTimeout(function() { show_card($('.three-cols-cards .main-container .card:nth-child(2)')); }, 1000);
					window.setTimeout(function() { show_card($('.three-cols-cards .main-container .card').last()); }, 2000);
				}
			}
		});
})(jQuery);
