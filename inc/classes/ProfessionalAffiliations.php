<?
class ProfessionalAffiliations
{
	public
		$is_rebuilding = false,
		$pro_affiliations = null,

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

		foreach(['pro_affiliation'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('pro_affiliation', array(
				'labels' => array(
					'name' => _x('Professional Affiliations', 'pro_affiliation'),
					'singular_name' => _x('Professional Affiliation', 'pro_affiliation'),
					'add_new' => _x('Add New Professional Affiliation', 'pro_affiliation'),
					'add_new_item' => _x('Add New Professional Affiliation', 'pro_affiliation'),
					'edit_item' => _x('Edit Professional Affiliation', 'pro_affiliation'),
					'new_item' => _x('Add a Professional Affiliation', 'pro_affiliation'),
					'view_item' => _x('View Professional Affiliation', 'pro_affiliation'),
					'search_items' => _x('Search Professional Affiliations', 'pro_affiliation'),
					'not_found' => _x('No professional affiliations found', 'pro_affiliation'),
					'not_found_in_trash' => _x('No professional affiliations found in trash', 'pro_affiliation'),
					'parent_item_colon' => _x('Parent Professional Affiliation:', 'pro_affiliation'),
					'menu_name' => _x('Professional Affiliations', 'pro_affiliation'),
				),
				'hierarchical' => false,
				'supports' => ['revisions', 'page-attributes'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 12,
				'menu_icon' => 'dashicons-welcome-learn-more',
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
			'key' => 'pro_affiliation_settings',
			'title' => 'Professional Affiliation Settings',
			'fields' => [
				[
					'key' => 'pro_affiliation_provider_relationship',
					'name' => 'pro_affiliation_provider_relationship',
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
					'key' => 'pro_affiliation_logo',
					'name' => 'pro_affiliation_logo',
					'label' => 'Professional Affiliation Logo',
					'type' => 'image',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				],
				[
					'key'   => 'pro_affiliation_heading',
					'name'  => 'pro_affiliation_heading',
					'label' => 'Professional Affiliation Heading',
					'type'  => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'pro_affiliation_content',
					'name' => 'pro_affiliation_content',
					'label' => 'Professional Affiliation Content',
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
					'value' => 'pro_affiliation',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		add_filter('manage_pro_affiliation_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'pro_affiliation' => __('Title', 'pro_affiliation'),
				'pro_affiliation_provider_relationship' => 'Provider',
				'date' => $columns['date'],
			];

			return $columns;
		}, 10, 1);

		add_action('manage_pro_affiliation_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'pro_affiliation':
					$heading = get_post_meta($post_id, 'pro_affiliation_heading', true);
					echo '<a href="'.get_edit_post_link($post_id).'" class="admin-edit-item">'.$heading.'</a>';
					break;
				case 'pro_affiliation_provider_relationship':
					$provider_ids = get_post_meta($post_id, 'pro_affiliation_provider_relationship', true);
					$provider_links = !empty($provider_ids) ? get_providers_for_pro_affiliation($provider_ids, 1) : false;
					echo !empty($provider_links) ? $provider_links : '-';
					break;
			}
		}, 10, 2);

		add_action('manage_edit-pro_affiliation_sortable_columns', function($columns) {
			$columns['pro_affiliation'] = 'pro_title';

			return $columns;
		}, 10, 1);

		add_action('pre_get_posts', function($query) {
			if (!is_admin()) return;

			$orderby = $query->get('orderby');

			if ('pro_title' == $orderby) {
				$query->set('meta_key', 'pro_affiliation_heading');
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
			if(get_post_type($post_id) == 'pro_affiliation') $this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_pro_affiliations');
	}

	private function loadData() {
		$this->pro_affiliations = get_option('__website_cache_metadata_pro_affiliations');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-pro_affiliations'])
			|| empty($this->pro_affiliations)
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
			'post_type' => 'pro_affiliation',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->pro_affiliations = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'pro_affiliation_')) $temp[str_replace('pro_affiliation_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->pro_affiliations[$p->ID] = (object)array_merge((array)$p, $temp);

			$this->pro_affiliations[$p->ID]->image = [
				'src' => str_replace(brand_host(), '', wp_get_attachment_image_src($this->pro_affiliations[$p->ID]->logo, 'medium_large')[0]),
				'alt' => get_post_meta($this->pro_affiliations[$p->ID]->logo, '_wp_attachment_image_alt', true),
				'width' => 330,
				'height' => 200,
				'classes' => []
			];
		}
		update_option('__website_cache_metadata_pro_affiliations', $this->pro_affiliations, false);
	}

	public function searchProfessionalAffiliations($search_ids) {
		return array_filter($this->pro_affiliations, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
