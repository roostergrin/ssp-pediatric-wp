(function(){
	/**
	 * Create a new MediaLibraryTaxonomyFilter we later will instantiate
	 */
	var BrandMediaLibraryTaxonomyFilter = wp.media.view.AttachmentFilters.extend({
		id: 'brand-media-attachment-taxonomy-filter',
		createFilters: function() {
			var filters = {};
			// Formats the 'terms' we've included via wp_localize_script()
			_.each(BrandMediaLibraryTaxonomyFilterData.terms || {}, function( value, index ) {
				filters[ index ] = {
					text: value.name,
					props: {
						// Change this key: key needs to be the WP_Query var for the taxonomy
						brand: value.slug
					}
				};
			});
			filters.all = {
				// Change this text value: use whatever default label you'd like
				text:  'All brands',
				props: {
					// Change this key: key needs to be the WP_Query var for the taxonomy
					brand: ''
				},
				priority: 10
			};
			this.filters = filters;
		}
	});

	var PageMediaLibraryTaxonomyFilter = wp.media.view.AttachmentFilters.extend({
		id: 'page-media-attachment-taxonomy-filter',
		createFilters: function() {
			var filters = {};
			// Formats the 'terms' we've included via wp_localize_script()
			_.each(PageMediaLibraryTaxonomyFilterData.terms || {}, function( value, index ) {
				filters[ index ] = {
					text: value.name,
					props: {
						// Change this key: key needs to be the WP_Query var for the taxonomy
						page_name: value.slug
					}
				};
			});
			filters.all = {
				// Change this text value: use whatever default label you'd like
				text:  'All pages',
				props: {
					// Change this key: key needs to be the WP_Query var for the taxonomy
					page_name: ''
				},
				priority: 10
			};
			this.filters = filters;
		}
	});

	var PlacementMediaLibraryTaxonomyFilter = wp.media.view.AttachmentFilters.extend({
		id: 'placement-media-attachment-taxonomy-filter',
		createFilters: function() {
			var filters = {};
			// Formats the 'terms' we've included via wp_localize_script()
			_.each(PlacementMediaLibraryTaxonomyFilterData.terms || {}, function( value, index ) {
				filters[ index ] = {
					text: value.name,
					props: {
						// Change this key: key needs to be the WP_Query var for the taxonomy
						placement: value.slug
					}
				};
			});
			filters.all = {
				// Change this text value: use whatever default label you'd like
				text:  'All placements',
				props: {
					// Change this key: key needs to be the WP_Query var for the taxonomy
					placement: ''
				},
				priority: 10
			};
			this.filters = filters;
		}
	});


	/**
	 * Extend and override wp.media.view.AttachmentsBrowser to include our new filter
	 */
	var BrandAttachmentsBrowser = wp.media.view.AttachmentsBrowser;
	wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			// Make sure to load the original toolbar
			BrandAttachmentsBrowser.prototype.createToolbar.call(this);
			this.toolbar.set('BrandMediaLibraryTaxonomyFilter', new BrandMediaLibraryTaxonomyFilter({
				controller: this.controller,
				model: this.collection.props,
				priority: -75
			}).render());
		}
	});

	var PageAttachmentsBrowser = wp.media.view.AttachmentsBrowser;
	wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			// Make sure to load the original toolbar
			PageAttachmentsBrowser.prototype.createToolbar.call(this);
			this.toolbar.set('PageMediaLibraryTaxonomyFilter', new PageMediaLibraryTaxonomyFilter({
				controller: this.controller,
				model: this.collection.props,
				priority: -75
			}).render());
		}
	});

	var PlacementAttachmentsBrowser = wp.media.view.AttachmentsBrowser;
	wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			// Make sure to load the original toolbar
			PlacementAttachmentsBrowser.prototype.createToolbar.call(this);
			this.toolbar.set('PlacementMediaLibraryTaxonomyFilter', new PlacementMediaLibraryTaxonomyFilter({
				controller: this.controller,
				model: this.collection.props,
				priority: -75
			}).render());
		}
	});
})()
