<?
namespace MDG;

class Optimize {

	private $js = '';
	private $css = '';
	private $site_url = '';
	private $merged_styles = '';
	private $merged_scripts = '';
	private $style_handles = [];
	private $script_handles = [];
	private $scripts = [];
	private $localize = [];
	private $inline = false; // Whether or not to inline the scripts/styles (instead of including in a separate file)

	public function __construct() {
		if (isset($_GET['disable_optimizer'])) return;
		// Actions
		if (isset($_GET['purge_js'])) $this->purge_js();
		add_action('wp_enqueue_scripts', [$this, 'hijack_styles'], 1e6);
		// add_action('wp_enqueue_scripts', [$this, 'hijack_scripts'], 1e6);
		add_action('wp_footer', [$this, 'hijack_scripts']);
		add_action('wp_footer', [$this, 'localize_data'], 1e5);
		add_action('wp_footer', [$this, 'deploy_scripts'], 1e6);

		// Initialize
		if (!file_exists(get_stylesheet_directory().'/css')) mkdir(get_stylesheet_directory().'/css');
		if (!file_exists(get_stylesheet_directory().'/js')) mkdir(get_stylesheet_directory().'/js');
		$this->css = apply_filters('mdg_optimize_css_prefix', get_stylesheet_directory().'/css/cache.');
		$this->js = apply_filters('mdg_optimize_js_prefix', get_stylesheet_directory().'/js/cache.');
		$this->inline = apply_filters('mdg_optimize_inline', $this->inline);
		$this->site_url = site_url();

		// Autoload
		spl_autoload_register(function($class_name) {
			if (strstr($class_name, 'MatthiasMullie\\')) {
				$file = get_stylesheet_directory().'/lib/'.str_replace(['MatthiasMullie\\', '\\'], ['', '/'], $class_name).'.php';
				if (file_exists($file)) { include_once $file; return; }
			}
		});
	}

	public function hijack_styles() {
		// Initialize
		global $wp_styles;
		$remove_styles = [];

		// Setup to_do order based on script/style dependencies
		$wp_styles->all_deps($wp_styles->queue);

		// Loop through our style handles in order of their dependencies
		if (!empty($wp_styles->to_do)) {
			foreach ($wp_styles->to_do as $handle) {
				$src = strtok($wp_styles->registered[$handle]->src, '?');
				if (strstr($src, 'http')) {
					// Switch to relative path when site_url() is present in the js file path
					if (strstr($src, $this->site_url)) $remove_styles[] = $handle;
					$css_file_path = strstr($src, $this->site_url) ? str_replace($this->site_url, '', $src) : $src;
					$css_file_path = ltrim($css_file_path, '/');
				} else {
					// Relative path
					$css_file_path = ltrim($src, '/');
				}
				$this->style_handles[] = $handle;

				// Merge if file exists
				if (file_exists($css_file_path)) $this->merged_styles .= file_get_contents($css_file_path);
			}
		}

		// Write and enqueue styles
		$this->write_css();
		$this->enqueue_css();

		// Deregister originally enqueued script/style handles
		foreach ($remove_styles as $handle) wp_deregister_style($handle);
	}

	public function hijack_scripts() {
		global $wp_scripts;
		$remove_scripts = [];
		
		// var_dump($wp_scripts->queue);
		// exit;

		// Setup to_do order based on script/style dependencies
		$wp_scripts->all_deps($wp_scripts->queue);

		// Loop through our script handles in order of their dependencies
		if (!empty($wp_scripts->to_do)) {
			foreach ($wp_scripts->to_do as $handle) {
				if(array_key_exists( $handle, $wp_scripts->registered )){
					$src = strtok($wp_scripts->registered[$handle]->src, '?');
					if (strstr($src, 'http')) {
						// Switch to relative path when site_url() is present in the js file path
						if (strstr($src, $this->site_url)) $remove_scripts[] = $handle;
						$js_file_path = strstr($src, $this->site_url) ? str_replace($this->site_url, '', $src) : $src;
						$js_file_path = ltrim($js_file_path, '/');
					} else {
						// Relative path
						$js_file_path = ltrim($src, '/');
					}
					$this->script_handles[] = $handle;
						if (@key_exists('data', $wp_scripts->registered[$handle]->extra)) {
							$this->localize[] = $wp_scripts->registered[$handle]->extra['data'];
						}

					// Merge if file exists
					if (file_exists($js_file_path)) {
						$this->scripts[] = $js_file_path;
					}
				}
			}
		}

		// Deregister scripts
		foreach ($remove_scripts as $handle) wp_deregister_script($handle);
	}

	public function deploy_scripts() {
		// Write and enqueue cache script
		$this->hijack_scripts();
		$this->write_js();
		$this->enqueue_js();
	}

	public function localize_data() {
		if (!empty($this->localize)) {
			echo "\n<script>\n";
			echo "/* <![CDATA[ */\n";
			foreach ($this->localize as $data) {
				echo "$data\n";
			}
			echo "/* ]]> */\n";
			echo "</script>";
		}
	}

	private function minify_js() {
		require_once get_stylesheet_directory().'/lib/Minify/Minify.php';
		require_once get_stylesheet_directory().'/lib/Minify/JS.php';
		$minifier = new \MatthiasMullie\Minify\JS($this->js);
		$minifier->minify($this->js);
	}

	private function minify_css() {
		require_once get_stylesheet_directory().'/lib/Minify/Minify.php';
		require_once get_stylesheet_directory().'/lib/Minify/CSS.php';
		require_once get_stylesheet_directory().'/lib/Minify/ConverterInterface.php';
		require_once get_stylesheet_directory().'/lib/Minify/Converter.php';
		require_once get_stylesheet_directory().'/lib/Minify/Exception.php';
		$minifier = new \MatthiasMullie\Minify\CSS($this->css);
		$minifier->minify($this->css);
	}

	private function purge_js() {
		$purged = [];
		$js_files = glob(get_stylesheet_directory().'/js/cache*.js');
		if (!empty($js_files)) {
			foreach ($js_files as $file) {
				$purged[$file] = unlink($file);
			}
		}
		echo '<h1>Purged '.count($purged).' files</h1>';
		if (count($purged)) {
			echo '<pre>';
			print_r($purged);
			echo '</pre>';
		}
		die;
	}

	private function write_js($force = false) {
		$this->js .= md5($this->site_url.implode(',', $this->script_handles)).'.min.js';
		if (isset($_GET['compile_js']) || !file_exists($this->js) || $force) {
			if (!empty($this->scripts)) {
				foreach ($this->scripts as $script) {
					$this->merged_scripts .= file_get_contents($script).';';
				}
			}
			file_put_contents($this->js, $this->merged_scripts);
			$this->minify_js();
		}
	}

	private function write_css($force = false) {
		$this->css .= md5($this->site_url.implode(',', $this->style_handles)).'.min.css';
		if (isset($_GET['compile']) || !file_exists($this->css) || $force) {
			file_put_contents($this->css, $this->merged_styles);
			$this->minify_css();
		}
	}

	private function enqueue_js() {
		$js_min = file_exists(str_replace('.js', '.min.js', $this->js)) ? str_replace('.js', '.min.js', $this->js) : '';
		$js_file = empty($js_min) ? $this->js : $js_min;
		$js_uri = str_replace(get_stylesheet_directory(), get_stylesheet_directory_uri(), $js_file);
		if ($this->inline) {
			echo "\n<script>\n";
			echo file_get_contents($js_file);
			echo "\n</script>\n";
		} else {
			echo "\n<script src=\"".$js_uri."?ver=".filemtime($js_file)."\"></script>\n";
		}
	}

	private function enqueue_css() {
		$css_min = file_exists(str_replace('.css', '.min.css', $this->css)) ? str_replace('.css', '.min.css', $this->css) : '';
		$css_file = empty($css_min) ? $this->css : $css_min;
		$css_uri = str_replace(get_stylesheet_directory(), get_stylesheet_directory_uri(), $css_file);
		if ($this->inline) {
			echo "\n<style>\n";
			echo file_get_contents($css_file);
			echo "\n</style>\n";
		} else {
			wp_enqueue_style('merged-css', $css_uri, null, filemtime($this->css));
		}
	}

}
