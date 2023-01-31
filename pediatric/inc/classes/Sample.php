<?
class Examples
{
	public
		$is_rebuilding = false,
		$examples = null,

		$___ ## DUMMY ##
	;

	public function __construct()  {
		$this->registerPreviewButtonHook();
		$this->registerPostTypes();
		$this->registerACF();
		$this->registerActions();
		$this->registerSaveHook();
		$this->registerImageSizes();
	}

	private function registerPreviewButtonHook() {
		add_action('admin_head-post-new.php', [$this, 'hidePreviewButton']);
		add_action('admin_head-post.php', [$this, 'hidePreviewButton']);
	}

	public function hidePreviewButton() {
		global $post_type;

		foreach(['example'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('example', array(
				'labels' => array(
					'name' => _x('Examples', 'example'),
					'singular_name' => _x('Example', 'example'),
					'add_new' => _x('Add New Example', 'example'),
					'add_new_item' => _x('Add New Example', 'example'),
					'edit_item' => _x('Edit Example', 'example'),
					'new_item' => _x('Add a Example', 'example'),
					'view_item' => _x('View Example', 'example'),
					'search_items' => _x('Search Examples', 'example'),
					'not_found' => _x('No examples found', 'example'),
					'not_found_in_trash' => _x('No examples found in trash', 'example'),
					'parent_item_colon' => _x('Parent Example:', 'example'),
					'menu_name' => _x('Examples', 'example'),
				),
				'hierarchical' => false,
				'supports' => ['revisions'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 12,
				'menu_icon' => 'dashicons-example',
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

		// if(function_exists('acf_add_local_field_group')) acf_add_local_field_group([
		// 	'key' => 'example_settings',
		// 	'title' => 'Example Settings',
		// 	'fields' => [
		// 		[
		// 			'key'   => 'example_body',
		// 			'label' => 'Example Body',
		// 			'name'  => 'example_body',
		// 			'type'  => 'textarea',
		// 			'required' => true,
		// 			'rows' => 3,
		// 			'instructions' => '',
		// 		],
		// 		[
		// 			'key'   => 'example_attribution',
		// 			'label' => 'Example Attribution',
		// 			'name'  => 'example_attribution',
		// 			'type'  => 'text',
		// 		],
		// 	],
		// 	'location' => [
		// 		[[
		// 			'param' => 'post_type',
		// 			'operator' => '==',
		// 			'value' => 'example',
		// 		]],
		// 	],
		// 	'menu_order' => $menu_order++,
		// ]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		// add_filter('manage_example_posts_columns', function($columns) {
		// 	$columns = [
		// 		'cb' => $columns['cb'],
		// 		'title' => $columns['title'],
		// 		'date' => $columns['date'],
		// 	];

		// 	return $columns;
		// }, 10, 1);

		// add_action('manage_example_posts_custom_column', function($column, $post_id) {
		// 	switch ($column) {
		// 		case 'local_url':
		// 			echo get_post_meta($post_id, 'brand_local_url', true);
		// 			break;
		// 		case 'dev_url':
		// 			echo get_post_meta($post_id, 'brand_dev_url', true);
		// 			break;
		// 		case 'prod_url':
		// 			echo get_post_meta($post_id, 'brand_production_url', true);
		// 			break;
		// 		case 'locations':
		// 			$locations = get_locations_for_brand($post_id, 1);
		// 			echo !empty($locations) ? implode(', ', $locations) : '—';
		// 			break;
		// 	}
		// }, 10, 2);

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
			if(get_post_type($post_id) == 'example') $this->rebuild();
		}, 10, 3);
	}

	private function registerImageSizes() {
		// STUB
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_examples');
	}

	private function loadData() {
		$this->examples = get_option('__website_cache_metadata_examples');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-examples'])
			|| empty($this->examples)
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
			'post_type' => 'example',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->examples = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'example_')) $temp[str_replace('example_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);

			$this->examples[$p->ID] = (object)array_merge((array)$p, $temp);
		}
		update_option('__website_cache_metadata_examples', $this->examples, false);
	}

	public function searchExamples($search_ids) {
		return array_filter($this->examples, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
