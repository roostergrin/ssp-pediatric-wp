<?
class Regions
{
	public
		$is_rebuilding = false,
		$regions = null,

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

		foreach(['region'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('region', array(
				'labels' => array(
					'name' => _x('Regions', 'region'),
					'singular_name' => _x('Region', 'region'),
					'add_new' => _x('Add New Region', 'region'),
					'add_new_item' => _x('Add New Region', 'region'),
					'edit_item' => _x('Edit Region', 'region'),
					'new_item' => _x('Add a Region', 'region'),
					'view_item' => _x('View Region', 'region'),
					'search_items' => _x('Search Regions', 'region'),
					'not_found' => _x('No regions found', 'region'),
					'not_found_in_trash' => _x('No regions found in trash', 'region'),
					'parent_item_colon' => _x('Parent Region:', 'region'),
					'menu_name' => _x('Regions', 'region'),
				),
				'hierarchical' => false,
				'supports' => ['title', 'revisions'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 5,
				'menu_icon' => 'dashicons-admin-site-alt3',
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
			'key' => 'region_settings',
			'title' => 'Region Settings',
			'fields' => [
				[
					'key' => 'region_location_relationship',
					'name' => 'region_location_relationship',
					'label' => 'Location Relationship',
					'type' => 'relationship',
					'required' => 1,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'location',
					],
					'filters' => [],
					'elements' => [],
					'return_format' => 'object',
				],
			],
			'location' => [
				[[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'region',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		add_filter('manage_region_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'locations' => __('Locations', 'region'),
				'date' => $columns['date'],
			];

			return $columns;
		}, 10, 1);

		add_action('manage_region_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'locations':
					$locations = get_locations_for_region($post_id, 1);
					echo !empty($locations) ? implode(', ', $locations) : '—';
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
			if(get_post_type($post_id) == 'region') $this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_regions');
	}

	private function loadData() {
		$this->regions = get_option('__website_cache_metadata_regions');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-regions'])
			|| empty($this->regions)
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
			'post_type' => 'region',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->regions = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'region_')) $temp[str_replace('region_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);

			$this->regions[$p->ID] = (object)array_merge((array)$p, $temp);
		}
		update_option('__website_cache_metadata_regions', $this->regions, false);
	}

	public function searchRegions($search_ids) {
		return array_filter($this->regions, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
