<?
class SmileTransformations
{
	public
		$is_rebuilding = false,
		$smile_transformations = null,

		$___ ## DUMMY ##
	;

	public function __construct()  {
		$this->registerPreviewButtonHook();
		$this->registerPostTypes();
		$this->registerTaxonomies();
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

		foreach(['smile_transformation'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('smile_transformation', array(
				'labels' => array(
					'name' => _x('Smile Transformations', 'smile_transformation'),
					'singular_name' => _x('Smile Transformation', 'smile_transformation'),
					'add_new' => _x('Add New Smile Transformation', 'smile_transformation'),
					'add_new_item' => _x('Add New Smile Transformation', 'smile_transformation'),
					'edit_item' => _x('Edit Smile Transformation', 'smile_transformation'),
					'new_item' => _x('Add a Smile Transformation', 'smile_transformation'),
					'view_item' => _x('View Smile Transformation', 'smile_transformation'),
					'search_items' => _x('Search Smile Transformations', 'smile_transformation'),
					'not_found' => _x('No smile transformations found', 'smile_transformation'),
					'not_found_in_trash' => _x('No smile transformations found in trash', 'smile_transformation'),
					'parent_item_colon' => _x('Parent Smile Transformation:', 'smile_transformation'),
					'menu_name' => _x('Smile Transformations', 'smile_transformation'),
				),
				'hierarchical' => false,
				'supports' => ['title', 'editor', 'page-attributes', 'revisions', 'thumbnail'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 12,
				'menu_icon' => 'dashicons-smiley',
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
			register_taxonomy('smile_transformation_diagnosis', ['smile_transformation'], [
				'labels' => [
					'name' => _x('Diagnosis', 'smile_transformation'),
					'singular_name' => _x('Diagnosis', 'smile_transformation'),
					'menu_name' => __('Diagnosis', 'smile_transformation'),
					'all_items' => __('All Diagnosis', 'smile_transformation'),
					'parent_item' => __('Parent Diagnosis', 'smile_transformation'),
					'parent_item_colon' => __('Parent Diagnosis:', 'smile_transformation'),
					'new_item_name' => __('New Diagnosis Name', 'smile_transformation'),
					'add_new_item' => __('Add New Diagnosis', 'smile_transformation'),
					'edit_item' => __('Edit Diagnosis', 'smile_transformation'),
					'update_item' => __('Update Diagnosis', 'smile_transformation'),
					'view_item' => __('View Diagnosis', 'smile_transformation'),
					'separate_items_with_commas' => __('Separate diagnosis with commas', 'smile_transformation'),
					'add_or_remove_items' => __('Add or remove diagnosis', 'smile_transformation'),
					'choose_from_most_used' => __('Choose from the most used', 'smile_transformation'),
					'popular_items' => __('Popular Diagnosis', 'smile_transformation'),
					'search_items' => __('Search Diagnosis', 'smile_transformation'),
					'not_found' => __('Not Found', 'smile_transformation'),
					'no_terms' => __('No Diagnosis', 'smile_transformation'),
					'items_list' => __('Diagnosis list', 'smile_transformation'),
					'items_list_navigation' => __('Diagnosis list navigation', 'smile_transformation'),
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

			register_taxonomy('smile_transformation_treatments', ['smile_transformation'], [
				'labels' => [
					'name' => _x('Treatments', 'smile_transformation'),
					'singular_name' => _x('Treatments', 'smile_transformation'),
					'menu_name' => __('Treatments', 'smile_transformation'),
					'all_items' => __('All Treatments', 'smile_transformation'),
					'parent_item' => __('Parent Treatments', 'smile_transformation'),
					'parent_item_colon' => __('Parent Treatments:', 'smile_transformation'),
					'new_item_name' => __('New Treatments Name', 'smile_transformation'),
					'add_new_item' => __('Add New Treatments', 'smile_transformation'),
					'edit_item' => __('Edit Treatments', 'smile_transformation'),
					'update_item' => __('Update Treatments', 'smile_transformation'),
					'view_item' => __('View Treatments', 'smile_transformation'),
					'separate_items_with_commas' => __('Separate treatments with commas', 'smile_transformation'),
					'add_or_remove_items' => __('Add or remove treatments', 'smile_transformation'),
					'choose_from_most_used' => __('Choose from the most used', 'smile_transformation'),
					'popular_items' => __('Popular Treatments', 'smile_transformation'),
					'search_items' => __('Search Treatments', 'smile_transformation'),
					'not_found' => __('Not Found', 'smile_transformation'),
					'no_terms' => __('No Treatments', 'smile_transformation'),
					'items_list' => __('Treatments list', 'smile_transformation'),
					'items_list_navigation' => __('Treatments list navigation', 'smile_transformation'),
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
			'key' => 'smile_transformation_settings',
			'title' => 'Smile Transformation Settings',
			'fields' => [
				[
					'key' => 'smile_transformation_region_relationship',
					'name' => 'smile_transformation_region_relationship',
					'label' => 'Region Relationship',
					'type' => 'relationship',
					'required' => 1,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'region',
					],
					'filters' => [],
					'elements' => [],
					'return_format' => 'object',
				],
				[
					'key' => 'smile_transformation_provider_relationship',
					'name' => 'smile_transformation_provider_relationship',
					'label' => 'Provider Relationship',
					'type' => 'relationship',
					'required' => 1,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'provider',
					],
					'filters' => [],
					'elements' => [],
					'return_format' => 'object',
				],
				[
					'key' => 'smile_transformation_before_image',
					'name' => 'smile_transformation_before_image',
					'label' => 'Before Image',
					'type' => 'image',
					'mime_types' => 'svg,png,jpeg,jpg',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'full',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'smile_transformation_after_image',
					'name' => 'smile_transformation_after_image',
					'label' => 'After Image',
					'type' => 'image',
					'mime_types' => 'svg,png,jpeg,jpg',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'full',
					'wrapper' => [
						'width' => 50,
					],
				],
			],
			'location' => [
				[[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'smile_transformation',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		add_filter('manage_smile_transformation_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'region' => 'Region',
				'provider' =>'Providers',
				'diagnosis' => 'Diagnosis',
				'treatments' =>'Treatments',
				'date' => $columns['date'],
			];
			return $columns;
		}, 10, 1);

		add_action('manage_smile_transformation_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'region':
					$regions = get_region_for_smile_transformation($post_id, 1);
					echo !empty($regions) ? implode(',<br>', $regions) : '—';
					break;
				case 'provider':
					$providers = get_providers_for_smile_transformation($post_id, 1);
					echo !empty($providers) ? implode(',<br>', $providers) : '—';
					break;
				case 'diagnosis':
				case 'treatments':
					get_custom_column_info($column, $post_id);
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
		add_action('save_post', function($id, WP_Post $post, $update) {
			if (wp_is_post_revision($id) || wp_is_post_autosave($id)) {
				return $post;
			}

			if ($update && $post->post_type == 'smile_transformation') {
				$before = get_post_meta($id, 'smile_transformation_before_image', true);
				set_post_thumbnail($id, $before);
			}

			if (get_post_type($id) == 'smile_transformation') $this->rebuild();

			return $post;
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_smile_transformations');
	}

	private function loadData() {
		$this->smile_transformations = get_option('__website_cache_metadata_smile_transformations');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-smile_transformations'])
			|| empty($this->smile_transformations)
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
			'post_type' => 'smile_transformation',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->smile_transformations = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'smile_transformation_')) $temp[str_replace('smile_transformation_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);

			$this->smile_transformations[$p->ID] = (object)array_merge((array)$p, $temp);
		}
		update_option('__website_cache_metadata_smile_transformations', $this->smile_transformations, false);
	}

	public function searchSmileTransformations($search_ids) {
		return array_filter($this->smile_transformations, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
