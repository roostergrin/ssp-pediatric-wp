<?
class Providers
{
	public
		$is_rebuilding = false,
		$providers = null,

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

		foreach(['provider'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('provider', array(
				'labels' => array(
					'name' => _x('Providers', 'provider'),
					'singular_name' => _x('Provider', 'provider'),
					'add_new' => _x('Add New Provider', 'provider'),
					'add_new_item' => _x('Add New Provider', 'provider'),
					'edit_item' => _x('Edit Provider', 'provider'),
					'new_item' => _x('Add a Provider', 'provider'),
					'view_item' => _x('View Provider', 'provider'),
					'search_items' => _x('Search Providers', 'provider'),
					'not_found' => _x('No providers found', 'provider'),
					'not_found_in_trash' => _x('No providers found in trash', 'provider'),
					'parent_item_colon' => _x('Parent Provider:', 'provider'),
					'menu_name' => _x('Providers', 'provider'),
				),
				'hierarchical' => false,
				'supports' => ['revisions', 'page-attributes'],
				'taxonomies' => [],
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'rewrite' => ['slug' => 'orthodontic-team', 'with_front' => false],
				// 'menu_position' => 5,
				'menu_icon' => 'dashicons-id-alt',
				'show_in_nav_menus' => true,
				'publicly_queryable' => true,
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
			'key' => 'provider_settings',
			'title' => 'Provider Settings',
			'fields' => [
				[
					'key' => 'relationship_tab_provider',
					'name' => 'relationship_tab_provider',
					'label' => 'Relationship’s',
					'type' => 'tab',
				],
				[
					'key' => 'provider_location_relationship',
					'name' => 'provider_location_relationship',
					'label' => 'Location Relationship',
					'type' => 'relationship',
					'required' => 0,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'location',
					],
					'filters' => [],
					'elements' => [],
					'return_format' => 'object',
				],
				[
					'key' => 'provider_brand_relationship',
					'name' => 'provider_brand_relationship',
					'label' => 'Brand Relationship',
					'type' => 'relationship',
					'required' => 1,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'brand',
					],
					'filters' => [],
					'elements' => [],
					'return_format' => 'object',
				],
				[
					'key' => 'provider_selected_location_relationship',
					'name' => 'provider_selected_location_relationship',
					'label' => 'Selected Location Relationship',
					'type' => 'relationship',
					'required' => false,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'location',
					],
					'filters' => [],
					'elements' => [],
					'max' => 1,
					'return_format' => 'id',
				],
				[
					'key' => 'details_tab_provider',
					'name' => 'details_tab_provider',
					'label' => 'Provider Details',
					'type' => 'tab',
				],
				[
					'key' => 'provider_image',
					'name' => 'provider_image',
					'label' => 'Image',
					'type' => 'image',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => [
						'width' => 20,
					],
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				],
				[
					'key'   => 'provider_first_name',
					'name'  => 'provider_first_name',
					'label' => 'First Name',
					'type'  => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key'   => 'provider_middle_initial',
					'name'  => 'provider_middle_initial',
					'label' => 'Middle Initial',
					'type'  => 'text',
					'wrapper' => [
						'width' => 30,
					],
				],
				[
					'key'   => 'provider_last_name',
					'name'  => 'provider_last_name',
					'label' => 'Last Name',
					'type'  => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key'   => 'provider_degree',
					'name'  => 'provider_degree',
					'label' => 'Degree',
					'type'  => 'text',
					'wrapper' => [
						'width' => 30,
					],
				],
				[
					'key'   => 'provider_specialty_title',
					'name'  => 'provider_specialty_title',
					'label' => 'Specialty Title',
					'type'  => 'text',
					'wrapper' => [
						'width' => 20,
					],
				],
				[
					'key' => 'provider_bio_copy',
					'name' => 'provider_bio_copy',
					'label' => 'Bio Copy',
					'type' => 'wysiwyg',
					'required' => false,
					'tabs' => 'all',
					'toolbar' => 'simple',
					'media_upload' => 0,
					'delay' => 0,
				],
				[
					'key' => 'provider_bio_shortened_copy',
					'name' => 'provider_bio_shortened_copy',
					'label' => 'Bio Shortened Copy',
					'type' => 'textarea',
				],
				[
					'key' => 'page_tab_provider',
					'name' => 'page_tab_provider',
					'label' => 'Bio Page Content',
					'type' => 'tab',
				],
				[
					'key' => 'provider_section_one',
					'name' => 'provider_section_one',
					'label' => 'Section One',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'provider_section_one_heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'provider_section_one_overlay_heading',
							'name' => 'overlay_heading',
							'label' => 'Overlay Heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'provider_section_one_overlay_content',
							'name' => 'overlay_content',
							'label' => 'Overlay Content',
							'type' => 'wysiwyg',
							'required' => false,
							'tabs' => 'all',
							'toolbar' => 'simple',
							'media_upload' => 0,
							'delay' => 0,
						],
					],
				],
				[
					'key' => 'provider_image_gallery',
					'name' => 'provider_image_gallery',
					'label' => 'Section Two Image Gallery',
					'type' => 'gallery',
					'insert' => 'append',
					'library' => 'all',
				],
				[
					'key' => 'provider_section_three',
					'name' => 'provider_section_three',
					'label' => 'Section Three',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'provider_section_three_heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
						],
						[
							'key' => 'provider_section_three_content',
							'name' => 'content',
							'label' => 'Content',
							'type' => 'wysiwyg',
							'required' => false,
							'tabs' => 'all',
							'toolbar' => 'simple',
							'media_upload' => 0,
							'delay' => 0,
						],
					],
				],
				[
					'key' => 'provider_section_four',
					'name' => 'provider_section_four',
					'label' => 'Section Four',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'provider_section_four_heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
						],
					],
				],
//				[
//					'key' => 'provider_section_five',
//					'name' => 'provider_section_five',
//					'label' => 'Section Five',
//					'type' => 'group',
//					'layout' => 'block',
//					'sub_fields' => [
//						[
//							'key' => 'bottom_desktop_image',
//							'name' => 'bottom_desktop_image',
//							'label' => 'Desktop Image',
//							'type' => 'image',
//							'wrapper' => [
//								'width' => 50,
//							],
//							'return_format' => 'array',
//							'preview_size' => 'thumbnail',
//							'library' => 'all',
//						],
//						[
//							'key' => 'bottom_mobile_image',
//							'name' => 'bottom_mobile_image',
//							'label' => 'Mobile Image',
//							'type' => 'image',
//							'wrapper' => [
//								'width' => 50,
//							],
//							'return_format' => 'array',
//							'preview_size' => 'thumbnail',
//							'library' => 'all',
//						],
//					],
//				],
			],
			'location' => [
				[[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'provider',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		add_filter('manage_provider_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'brand' => 'Brand',
				'locations' => __('Locations', 'provider'),
				'date' => $columns['date'],
			];

			return $columns;
		}, 10, 1);

		add_action('manage_provider_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'locations':
					$locations = get_locations_for_provider($post_id, 1);
					echo !empty($locations) ? implode(', ', $locations) : '—';
					break;
				case 'brand':
					$brands = get_brand_for_provider($post_id, 1);
					echo !empty($brands) ? $brands : '—';
					break;
			}
		}, 10, 2);

		//=======================================[ACF/Save Post]=======================================//
		add_action('acf/save_post', function($post_id) {
			if (get_post_type($post_id) != 'provider') return;
			$values = get_fields($post_id);
			$first_name = $values['provider_first_name'];
			$middle_initial = $values['provider_middle_initial'];
			$last_name = $values['provider_last_name'];
			$degree = explode(',', $values['provider_degree'])[0];
			$title = !empty($middle_initial) ? ($first_name).' '.($middle_initial).' '.($last_name).' '.($degree) : ($first_name).' '.($last_name).' '.($degree);
			$brands = !empty($values['provider_brand_relationship']) ? $values['provider_brand_relationship'] : '';
			// Updating Title and Slug with ACF content for SEO purposes
			$post_data = [
				'ID' => $post_id,
				'post_title' => $title,
				'post_name' => sanitize_title(str_replace('.', '', $title), '', 'save')
			];
			wp_update_post($post_data);
		}, 5, 1);
		//=====================================[End ACF/Save Post]=====================================//

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
			if(get_post_type($post_id) == 'provider') $this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_providers');
	}

	private function loadData() {
		$this->providers = get_option('__website_cache_metadata_providers');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-providers'])
			|| empty($this->providers)
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
			'post_type' => 'provider',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->providers = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'provider_')) $temp[str_replace('provider_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->providers[$p->ID] = (object)array_merge((array)$p, $temp);

			$image_id = $this->providers[$p->ID]->image;
			$this->providers[$p->ID]->image = [
				'src' => str_replace(brand_host(), '', wp_get_attachment_image_src($image_id, 'full')[0]),
				'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
				'classes' => ['bg-img', 'dr-'.(strtolower($this->providers[$p->ID]->last_name))]
			];
			$this->providers[$p->ID]->caption = [
				'name' => 'Dr. '.($this->providers[$p->ID]->first_name).' '.($this->providers[$p->ID]->last_name),
				'specialty' => $this->providers[$p->ID]->specialty_title,
				'content' => $this->providers[$p->ID]->bio_shortened_copy,
			];
			$this->providers[$p->ID]->url = get_permalink($p->ID);
			$this->providers[$p->ID]->relative_url = get_relative_permalink($p->ID);
		}
		update_option('__website_cache_metadata_providers', $this->providers, false);
	}

	public function searchProviders($search_ids) {
		return array_filter($this->providers, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
