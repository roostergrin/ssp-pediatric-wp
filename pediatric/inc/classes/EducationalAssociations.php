<?
class EducationalAssociations
{
	public
		$is_rebuilding = false,
		$edu_associations = null,

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

		foreach(['edu_association'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('edu_association', array(
				'labels' => array(
					'name' => _x('Educational Associations', 'edu_association'),
					'singular_name' => _x('Educational Association', 'edu_association'),
					'add_new' => _x('Add New Educational Association', 'edu_association'),
					'add_new_item' => _x('Add New Educational Association', 'edu_association'),
					'edit_item' => _x('Edit Educational Association', 'edu_association'),
					'new_item' => _x('Add a Educational Association', 'edu_association'),
					'view_item' => _x('View Educational Association', 'edu_association'),
					'search_items' => _x('Search Educational Associations', 'edu_association'),
					'not_found' => _x('No educational associations found', 'edu_association'),
					'not_found_in_trash' => _x('No educational associations found in trash', 'edu_association'),
					'parent_item_colon' => _x('Parent Educational Association:', 'edu_association'),
					'menu_name' => _x('Educational Associations', 'edu_association'),
				),
				'hierarchical' => false,
				'supports' => ['revisions', 'page-attributes'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 12,
				'menu_icon' => 'dashicons-art',
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
			'key' => 'edu_association_settings',
			'title' => 'Educational Association Settings',
			'fields' => [
				[
					'key' => 'edu_association_provider_relationship',
					'name' => 'edu_association_provider_relationship',
					'label' => 'Provider Relationship',
					'type' => 'relationship',
					'required' => 1,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'provider',
					],
					'filters' => [],
					'elements' => [],
					'max' => 1,
					'return_format' => 'object',
				],
				[
					'key' => 'edu_association_logo',
					'name' => 'edu_association_logo',
					'label' => 'Logo',
					'type' => 'image',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				],
				[
					'key'   => 'edu_association_heading',
					'name'  => 'edu_association_heading',
					'label' => 'Educational Association Heading',
					'type'  => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'edu_association_content',
					'name' => 'edu_association_content',
					'label' => 'Content',
					'type' => 'wysiwyg',
					'required' => false,
					'tabs' => 'all',
					'toolbar' => 'simple',
					'media_upload' => 0,
					'delay' => 0,
				],
			],
			'location' => [
				[[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'edu_association',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		add_filter('manage_edu_association_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'edu_association' => __('Title', 'edu_association'),
				'edu_association_provider_relationship' => 'Provider',
				'edu_association_brand_relationship' => 'Brand',
				'edu_association_region_relationship' => 'Region',
				'date' => $columns['date'],
			];
			return $columns;
		}, 10, 1);

		add_action('manage_edu_association_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'edu_association':
					$heading = get_post_meta($post_id, 'edu_association_heading', true);
					echo !empty($heading) ? '<a href="'.get_edit_post_link($post_id).'" class="admin-edit-item">'.$heading.'</a>' : '<a href="'.get_edit_post_link($post_id).'" class="admin-edit-item">'.$heading.'</a>';
					break;
				case 'edu_association_provider_relationship':
					$provider_id = get_post_meta($post_id, 'edu_association_provider_relationship', true);
					$provider_name = !empty($provider_id) ? get_provider_name_from_id($provider_id[0]) : false;
					echo !empty($provider_name) ? '<a href="'.get_edit_post_link($provider_id[0]).'" class="admin-edit-item">'.$provider_name.'</a>' : '';
					break;
				case 'edu_association_brand_relationship':
					$providers = get_post_meta($post_id, 'edu_association_provider_relationship', true);
					$brand = get_brand_for_provider($providers[0]);
					echo !empty($brand) ? $brand : '—';
					break;
				case 'edu_association_region_relationship':
					$regions = get_regions_for_edu_association( $post_id );
					echo !empty($regions) ? $regions[0] : '—';
					break;
			}
		}, 10, 2);

		add_action('manage_edit-edu_association_sortable_columns', function($columns) {
			$columns['edu_association'] = 'edu_title';

			return $columns;
		}, 10, 1);

		add_action('pre_get_posts', function($query) {
			if (!is_admin()) return;

			$orderby = $query->get('orderby');

			if ('edu_title' == $orderby) {
				$query->set('meta_key', 'edu_association_heading');
				$query->set('orderby', 'meta_value');
				$query->set('order', 'ASC');
			}
		}, 10, 1);

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
			if(get_post_type($post_id) == 'edu_association') $this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_edu_associations');
	}

	private function loadData() {
		$this->edu_associations = get_option('__website_cache_metadata_edu_associations');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-edu_associations'])
			|| empty($this->edu_associations)
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
			'post_type' => 'edu_association',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->edu_associations = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'edu_association_')) $temp[str_replace('edu_association_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->edu_associations[$p->ID] = (object)array_merge((array)$p, $temp);

			$this->edu_associations[$p->ID]->image = [
				'src' => str_replace(brand_host(), '', wp_get_attachment_image_src($this->edu_associations[$p->ID]->logo, 'medium_large')[0]),
				'alt' => get_post_meta($this->edu_associations[$p->ID]->logo, '_wp_attachment_image_alt', true),
				'width' => 330,
				'height' => 200,
				'classes' => []
			];
		}
		update_option('__website_cache_metadata_edu_associations', $this->edu_associations, false);
	}

	public function searchEducationalAssociations($search_ids) {
		return array_filter($this->edu_associations, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
