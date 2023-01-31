(function($) {
	let checker = (arr, target) => target.every(v => arr.includes(v)),
		smiles_per_page = 8,
		page_number = 1,
		selected = [],
		shown = 0;

	$('.filter').on('click', function() {
		$(this).toggleClass('active');
		filter_gallery();
	});
	// Dummy filter class, changes style, nothing else
	$('.filter-inop').on('click', function() {
		$(this).toggleClass('active');
	});
	
	$('.filter-cat-title').on('click', function() {
		$(this).toggleClass('active');
		$(this).parent().find('.filters-container').toggle();
	});

	$(document).on('click', '.load-more', function(e) {
		e.preventDefault();
		load_more();
	});

	function filter_gallery() {
		let filter_selector = '';
		selected = [];
		$('.filter.active').each(function() {
			selected.push($(this).attr('id'));
		});
		for (i in selected) {
			filter_selector += '[data-filters*="' + selected[i] + '"]';
		}
		$('.before-after-item:visible').hide();
		let items = $('.before-after-item:not(:visible)' + filter_selector);
		$('.before-after-item' + filter_selector).each(function(k, v) {
			if (k < smiles_per_page) {
				$(this).show({
					duration: 200
				});
			} else {
				$(this).hide();
			}
		});
		page_number = 1;
		if (items.length < smiles_per_page + 1) {
			$('.load-more').hide({
				duration: 200
			});
		} else {
			$('.load-more').show({
				duration: 200
			});
		}
		no_results_found();
	}

	function no_results_found() {
		// console.log('no_results_found()');
		setTimeout(function() {
			if ($('.before-after-item:visible').length === 0) {
				$('.load-more').addClass('hidden');
				$('.filter-no-results').removeClass('hidden');
			} else {
				$('.filter-no-results').addClass('hidden');
			}
		}, 300);
	}

	function load_more() {
		let filter_selector = '';
		if (selected.length) {
			for (i in selected) {
				filter_selector += '[data-filters*="' + selected[i] + '"]';
			}
		}
		let items = $('.before-after-item:not(:visible)' + filter_selector);
		$('.before-after-item:not(:visible)' + filter_selector).each(function(k, v) {
			if (k < smiles_per_page) {
				$(this).show({
					duration: 200
				});
			}
		});
		if (items.length < smiles_per_page + 1) {
			$('.load-more').hide();
		}
		page_number++;
	}
})(jQuery);