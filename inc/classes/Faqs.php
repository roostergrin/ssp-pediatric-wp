<?
class Faqs
{
	public
		$is_rebuilding = false,
		$faqs = null,

		$___ ## DUMMY ##
	;

	public function __construct()  {
		$this->registerPfaqButtonHook();
		$this->registerPostTypes();
		$this->registerACF();
		$this->registerActions();
		$this->registerSaveHook();
        $this->registerTaxonomies();
	}

	private function registerPfaqButtonHook() {
		add_action('admin_head-post-new.php', [$this, 'hidePfaqButton']);
		add_action('admin_head-post.php', [$this, 'hidePfaqButton']);
	}

	public function hidePfaqButton() {
		global $post_type;

		foreach(['faq'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .pfaq.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('faq', array(
				'labels' => array(
					'name' => _x('Faqs', 'faq'),
					'singular_name' => _x('Faq', 'faq'),
					'add_new' => _x('Add New Faq', 'faq'),
					'add_new_item' => _x('Add New Faq', 'faq'),
					'edit_item' => _x('Edit Faq', 'faq'),
					'new_item' => _x('Add a Faq', 'faq'),
					'view_item' => _x('View Faq', 'faq'),
					'search_items' => _x('Search Faqs', 'faq'),
					'not_found' => _x('No faqs found', 'faq'),
					'not_found_in_trash' => _x('No faqs found in trash', 'faq'),
					'parent_item_colon' => _x('Parent Faq:', 'faq'),
					'menu_name' => _x('Faqs', 'faq'),
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


	private function registerTaxonomies() {
		add_action('init', function () {
			register_taxonomy('faq_tags', ['faq'], [
				'labels' => [
					'name' => _x('Tags', 'faq'),
					'singular_name' => _x('Tags', 'faq'),
					'menu_name' => __('Tags', 'faq'),
					'all_items' => __('All Tags', 'faq'),
					'parent_item' => __('Parent Tags', 'faq'),
					'parent_item_colon' => __('Parent Tags:', 'faq'),
					'new_item_name' => __('New Tags Name', 'faq'),
					'add_new_item' => __('Add New Tags', 'faq'),
					'edit_item' => __('Edit Tags', 'faq'),
					'update_item' => __('Update Tags', 'faq'),
					'view_item' => __('View Tags', 'faq'),
					'separate_items_with_commas' => __('Separate tags with commas', 'faq'),
					'add_or_remove_items' => __('Add or remove tags', 'faq'),
					'choose_from_most_used' => __('Choose from the most used', 'faq'),
					'popular_items' => __('Popular Tags', 'faq'),
					'search_items' => __('Search Tags', 'faq'),
					'not_found' => __('Not Found', 'faq'),
					'no_terms' => __('No Tags', 'faq'),
					'items_list' => __('Tags list', 'faq'),
					'items_list_navigation' => __('Tags list navigation', 'faq'),
				],
				'capabilities' => [],
				'show_in_quick_edit' => false,
				'hierarchical' => true,
				'public' => false,
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud' => true,
			]);

		});
	}

	private function registerACF() {
		////////////////////////////// POST TYPE FIELDS //////////////////////////////
		$menu_order = 0;

		if(function_exists('acf_add_local_field_group')) acf_add_local_field_group([
			'key' => 'faq_settings',
			'title' => 'Faq Settings',
			'fields' => [
				[
					'key' => 'faq_relationships',
					'name' => 'faq_relationships',
					'label' => 'Relationships',
					'type' => 'relationship',
					'required' => 1,
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
					'value' => 'faq',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		// add_action('manage_faq_posts_custom_column', function($column, $post_id) {
		// 	if($column == 'faq') echo get_post_meta($post_id, 'faq_body', true);
		// 	elseif($column == 'attribution') echo get_post_meta($post_id, 'faq_attribution', true);
		// 	elseif($column == 'attribution') echo get_post_meta($post_id, 'faq_attribution', true);
		// }, 10, 2);

		add_filter('manage_faq_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'brand' => 'Brand',
				'date' => $columns['date'],
			];
			return $columns;
		}, 10, 1);

		add_action('manage_faq_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'brand':
					$brands = get_brand_for_faq($post_id, 1);
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
			if(get_post_type($post_id) == 'faq') $this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_faqs');
	}

	private function loadData() {
		$this->faqs = get_option('__website_cache_metadata_faqs');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-faqs'])
			|| empty($this->faqs)
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
			'post_type' => 'faq',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->faqs = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'faq_')) $temp[str_replace('faq_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->faqs[$p->ID] = (object)array_merge((array)$p, $temp);

			// Image
			$image_id = get_post_thumbnail_id($p->ID);
			$image = wp_get_attachment_image_src($image_id, 'medium_large');
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
			$this->faqs[$p->ID]->image = [
				'src' => str_replace(brand_host(), '', $image[0]),
				'width' => $image[1],
				'height' => $image[2],
				'alt' => $image_alt,
				'classes' => []
			];
		}
		update_option('__website_cache_metadata_faqs', $this->faqs, false);
	}

	public function searchFaqs($search_ids) {
		return array_filter($this->faqs, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
