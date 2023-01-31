<?
class Reviews
{
	public
		$is_rebuilding = false,
		$reviews = null,

		$___ ## DUMMY ##
	;

	public function __construct()  {
		$this->registerPreviewButtonHook();
		$this->registerPostTypes();
		$this->registerACF();
		$this->registerActions();
		$this->registerSaveHook();
	}

	private function registerPreviewButtonHook() {
		add_action('admin_head-post-new.php', [$this, 'hidePreviewButton']);
		add_action('admin_head-post.php', [$this, 'hidePreviewButton']);
	}

	public function hidePreviewButton() {
		global $post_type;

		foreach(['review'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('review', array(
				'labels' => array(
					'name' => _x('Reviews', 'review'),
					'singular_name' => _x('Review', 'review'),
					'add_new' => _x('Add New Review', 'review'),
					'add_new_item' => _x('Add New Review', 'review'),
					'edit_item' => _x('Edit Review', 'review'),
					'new_item' => _x('Add a Review', 'review'),
					'view_item' => _x('View Review', 'review'),
					'search_items' => _x('Search Reviews', 'review'),
					'not_found' => _x('No reviews found', 'review'),
					'not_found_in_trash' => _x('No reviews found in trash', 'review'),
					'parent_item_colon' => _x('Parent Review:', 'review'),
					'menu_name' => _x('Reviews', 'review'),
				),
				'hierarchical' => false,
				'supports' => ['title', 'editor', 'thumbnail', 'page-attributes', 'revisions'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 12,
				'menu_icon' => 'dashicons-testimonial',
				'show_in_nav_menus' => false,
				'publicly_queryable' => false,
				'exclude_from_search' => false,
				'has_archive' => false,
				'query_var' => true,
				'can_export' => true,
				'capability_type' => 'post',
			));
		});
	}

	private function registerACF() {
		////////////////////////////// POST TYPE FIELDS //////////////////////////////
		$menu_order = 0;

		if(function_exists('acf_add_local_field_group')) acf_add_local_field_group([
			'key' => 'review_settings',
			'title' => 'Review Settings',
			'fields' => [
				[
					'key' => 'review_relationships',
					'name' => 'review_relationships',
					'label' => 'Relationships',
					'type' => 'relationship',
					'required' => 0,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'brand',
						1 => 'location',
						2 => 'provider',
						3 => 'page',
					],
					'filters' => [
						0 => 'post_type',
					],
					'elements' => [],
					'return_format' => 'object',
				],
			],
			'location' => [
				[[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'review',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		// add_action('manage_review_posts_custom_column', function($column, $post_id) {
		// 	if($column == 'review') echo get_post_meta($post_id, 'review_body', true);
		// 	elseif($column == 'attribution') echo get_post_meta($post_id, 'review_attribution', true);
		// 	elseif($column == 'attribution') echo get_post_meta($post_id, 'review_attribution', true);
		// }, 10, 2);

		add_filter('manage_review_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'brand' => 'Brand',
				'date' => $columns['date'],
			];
			return $columns;
		}, 10, 1);

		add_action('manage_review_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'brand':
					$brands = get_brand_for_review($post_id, 1);
					echo !empty($brands) ? implode(',<br>', $brands) : '—';
					break;
			}
		}, 10, 2);

		add_action('admin_head', function() {
			?>
			<style>
			.acf-field.auto-height { min-height:0!important;height:auto!important; }
			</style>
			<?
		});
	}

	private function registerSaveHook() {
		add_action('save_post', function($post_id, $post, $update) {
			if(get_post_type($post_id) == 'review') $this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_reviews');
	}

	private function loadData() {
		$this->reviews = get_option('__website_cache_metadata_reviews');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-reviews'])
			|| empty($this->reviews)
		) {
			add_action('template_redirect', function() {
				$this->rebuild();
			});
		}
	}

	public function rebuild() {
		if($this->is_rebuilding) return;
		$this->is_rebuilding = true;

		$q = new WP_Query([
			'post_type' => 'review',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->reviews = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'review_')) $temp[str_replace('review_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->reviews[$p->ID] = (object)array_merge((array)$p, $temp);

			// Image
			$image_id = get_post_thumbnail_id($p->ID);
			$image = wp_get_attachment_image_src($image_id, 'medium_large');
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
			$this->reviews[$p->ID]->image_left_border = [
				'src' => str_replace(brand_host(), '', $image[0]),
				'width' => $image[1],
				'height' => $image[2],
				'alt' => $image_alt,
				'classes' => ['top-left-border-radius', 'bottom-left-border-radius']
			];
			$this->reviews[$p->ID]->image_right_border = [
				'src' => str_replace(brand_host(), '', $image[0]),
				'width' => $image[1],
				'height' => $image[2],
				'alt' => $image_alt,
				'classes' => ['top-right-border-radius', 'bottom-right-border-radius']
			];
		}
		update_option('__website_cache_metadata_reviews', $this->reviews, false);
	}

	public function searchReviews($search_ids) {
		return array_filter($this->reviews, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
