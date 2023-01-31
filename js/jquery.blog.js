(function($) {
	let load_more = $('#load-more');
	load_more.data('loading', false);
	load_more.on('click', function(e) {
		e.preventDefault();
		load_more.html('Loading...');

		if (!load_more.data('loading')) {
			load_more.data('loading', true);
			$.post(ajax.url, {
				action: ajax.action,
				page: load_more.data('page'),
				posts_per_page: ajax.posts_per_page,
				post_type: ajax.post_type,
				e_id: ajax.e_id,
				t_id: ajax.t_id
			}).done(function(response) {
				let data = JSON.parse(response);
				$('div.blog-posts .small-posts').append(data.html);
				if (data.more) {
					load_more.data('page', load_more.data('page') + 1);
				} else {
					load_more.remove();
				}

				load_more.html('Load more');
				load_more.data('loading', false);
			});
		}
	});
})(jQuery);
