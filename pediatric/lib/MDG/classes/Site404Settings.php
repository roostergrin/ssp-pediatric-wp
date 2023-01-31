<?
namespace MDG;

class Site404Settings {
	public
		$_404,

		// Pages
		// $page_about,
		// $page_testimonials,

		$___ # placeholder
	;

	public function __construct() {
		if(!session_id()) session_start();

		$settings = get_option('__website_cache_website_404_settings');

		if(empty($settings) || isset($_GET['rebuild'])) {
			$this->rebuild();
		}
		else {
			if(!empty($settings) && is_object($settings)) {
				foreach(array_keys(get_object_vars($this)) as $var) {
					$this->$var = $settings->$var;
				}
			}
		}

		$this->registerACF();
		$this->registerHooks();
	}

	public function __destruct() {
		if (session_id()) session_write_close();
	}

	private function registerACF() {

		if(function_exists('acf_add_options_page')) {
			acf_add_options_page([
				'page_title' 	=> '404 Page Settings',
				'menu_title'	=> '404 Settings',
				'menu_slug' 	=> 'site-404-settings',
				'capability'	=> 'edit_posts',
				'redirect'		=> false,
				'position'      => 2
			]);
		}

		if(function_exists('acf_add_local_field_group')) {
			# 404 Page
			$menu_order = 0;
			acf_add_local_field_group([
				'key' => '404_page',
				'title' => '404 Page',
				'fields' => [
					[
						'key' => '404_page_heading',
						'name' => '404_page_heading',
						'label' => 'Heading',
						'type' => 'text',
						'wrapper' => [
							'width' => 50,
						],
					],
					[
						'key' => '404_page_content',
						'name' => '404_page_content',
						'label' => 'Content',
						'type' => 'textarea',
						'wrapper' => [
							'width' => 50,
						],
					],
					[
						'key' => '404_page_desktop_hero',
						'name' => '404_page_desktop_hero',
						'label' => 'Hero Photo (Desktop)',
						'type' => 'image',
						'mime_types' => 'png,jpeg,jpg,svg',
						'library' => 'all',
						'required' => false,
						'return_format' => 'id',
						'preview_size' => 'thumbnail',
						'wrapper' => [
							'width' => 50,
						],
					],
					[
						'key' => '404_page_mobile_hero',
						'name' => '404_page_mobile_hero',
						'label' => 'Hero Photo (Mobile)',
						'type' => 'image',
						'mime_types' => 'png,jpeg,jpg,svg',
						'library' => 'all',
						'required' => false,
						'return_format' => 'id',
						'preview_size' => 'thumbnail',
						'wrapper' => [
							'width' => 50,
						],
					],
				],
				'position' => 'normal',
				'location' => [[[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'site-404-settings',
				]]],
				'menu_order' => $menu_order++,
			]);
		}
	}

	private function registerHooks() {
		add_action('acf/save_post', function() {
			$screen = get_current_screen();
			if(strpos($screen->id, 'website-settings_page_acf-options-') !== false || $screen->id == 'toplevel_page_site-404-settings') {
				$this->rebuild();
			}
		}, 20, 0);
	}

	public function rebuild() {
		## 404 ##
		$this->_404 = (object)[
			'heading' => get_field('404_page_heading', 'option'),
			'content' => get_field('404_page_content', 'option'),
			'desktop_image' => get_field('404_page_desktop_hero', 'option'),
			'mobile_image' => get_field('404_page_mobile_hero', 'option'),
		];

		update_option('__website_cache_website_404_settings', $this);
	}
}
