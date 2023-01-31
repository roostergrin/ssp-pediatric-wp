<?
class InsuranceProviders
{
	public
		$is_rebuilding = false,
		$insurance_providers = null,

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

		foreach(['insurance_provider'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('insurance_provider', array(
				'labels' => array(
					'name' => _x('Insurance Providers', 'insurance_provider'),
					'singular_name' => _x('Insurance Provider', 'insurance_provider'),
					'add_new' => _x('Add New Insurance Provider', 'insurance_provider'),
					'add_new_item' => _x('Add New Insurance Provider', 'insurance_provider'),
					'edit_item' => _x('Edit Insurance Provider', 'insurance_provider'),
					'new_item' => _x('Add a Insurance Provider', 'insurance_provider'),
					'view_item' => _x('View Insurance Provider', 'insurance_provider'),
					'search_items' => _x('Search Insurance Providers', 'insurance_provider'),
					'not_found' => _x('No insurance providers found', 'insurance_provider'),
					'not_found_in_trash' => _x('No insurance providers found in trash', 'insurance_provider'),
					'parent_item_colon' => _x('Parent Insurance Provider:', 'insurance_provider'),
					'menu_name' => _x('Insurance Providers', 'insurance_provider'),
				),
				'hierarchical' => false,
				'supports' => ['title', 'page-attributes', 'revisions'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 12,
				'menu_icon' => 'dashicons-building',
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
			'key' => 'insurance_provider_settings',
			'title' => 'Insurance Provider Settings',
			'fields' => [
				[
					'key' => 'insurance_provider_logo',
					'name' => 'insurance_provider_logo',
					'label' => 'Logo',
					'type' => 'image',
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				],
				[
					'key' => 'insurance_provider_page_relationship',
					'name' => 'insurance_provider_page_relationship',
					'label' => 'Page Relationship',
					'type' => 'relationship',
					'required' => 1,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'page',
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
					'value' => 'insurance_provider',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		// add_action('manage_insurance_provider_posts_custom_column', function($column, $post_id) {
		// 	if($column == 'insurance_provider') echo get_post_meta($post_id, 'insurance_provider_body', true);
		// 	elseif($column == 'attribution') echo get_post_meta($post_id, 'insurance_provider_attribution', true);
		// 	elseif($column == 'attribution') echo get_post_meta($post_id, 'insurance_provider_attribution', true);
		// }, 10, 2);

		// add_filter('manage_insurance_provider_posts_columns', function($columns) {
		// 	unset($columns['title'], $columns['date']);
		// 	$columns['insurance_provider'] = 'Insurance Provider Body';
		// 	$columns['attribution'] = 'Attribution (Author)';
		// 	return $columns;
		// }, 10, 1);

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
			if(get_post_type($post_id) == 'insurance_provider') $this->rebuild();
		}, 10, 3);
	}

	private function registerImageSizes() {
		// STUB
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_insurance_providers');
	}

	private function loadData() {
		$this->insurance_providers = get_option('__website_cache_metadata_insurance_providers');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-insurance_providers'])
			|| empty($this->insurance_providers)
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
			'post_type' => 'insurance_provider',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->insurance_providers = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'insurance_provider_')) $temp[str_replace('insurance_provider_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->insurance_providers[$p->ID] = (object)array_merge((array)$p, $temp);
		}
		update_option('__website_cache_metadata_insurance_providers', $this->insurance_providers, false);
	}

	public function searchInsuranceProviders($search_ids) {
		return array_filter($this->insurance_providers, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
