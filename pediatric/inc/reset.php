<?

// Append mobile classes
add_action('body_class', function($classes) {
	if(wp_is_mobile()) $classes[] = 'mobile-device';
	return array_diff($classes, ['desktop', 'mobile']);
});

// Remove homepage modules from the dashboard
add_action('admin_menu', function() {
	remove_meta_box('dashboard_right_now', 'dashboard', 'core');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
	remove_meta_box('dashboard_primary', 'dashboard', 'core');
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');
	remove_meta_box('dashboard_activity', 'dashboard', 'core');
	remove_meta_box('semperplugins-rss-feed', 'dashboard', 'core');
});

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Disable wp-sitemap.xml
add_filter('wp_sitemaps_enabled', '__return_false');

// Remove the welcome panel and help tab
remove_action('welcome_panel', 'wp_welcome_panel');
add_action('admin_head', function() { get_current_screen()->remove_help_tabs(); });

// Remove pages from the admin menu
add_action('admin_init', function() {
	@remove_menu_page('edit-comments.php');
	@remove_menu_page('link-manager.php');
	@remove_submenu_page('tools.php', 'ms-delete-site.php');
	@remove_submenu_page('themes.php', 'customize.php?return='.urlencode('/wp-admin/'));
    global $current_user;
    if ($current_user->ID != 100) {
        remove_menu_page( 'edit.php?post_type=acf-field-group' );
    }
});

// Remove nodes from the admin bar
add_action('admin_bar_menu', function($wp_admin_bar) {
	$wp_admin_bar->remove_node('wp-logo');
	$wp_admin_bar->remove_node('customize');
	$wp_admin_bar->remove_node('comments');
	$wp_admin_bar->remove_node('updates');
	$wp_admin_bar->remove_node('new-media');
	$wp_admin_bar->remove_node('new-user');
	$wp_admin_bar->remove_node('my-sites-list');
}, 999);

// Remove additional meta tags populated in the header (documented in default-filters.php)
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wp_generator'); # exposes WordPress version number
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_shortlink_header');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'locale_stylesheet');
add_filter('emoji_svg_url', '__return_false'); # Removes s.w.org prefetch
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Disable pingback
add_filter('wp_headers', function($headers) {
	unset($headers['X-Pingback']);
	return $headers;
});

// Disable comments and trackbacks
add_action('init', function() {
	global $wp_post_types;
	foreach($wp_post_types as $type => $settings) {
		remove_post_type_support($type, 'comments');
		remove_post_type_support($type, 'trackbacks');
	}
});

// All in One SEO Pack
add_filter('aioseop_prev_link', function() { return false; });
add_filter('aioseop_next_link', function() { return false; });
add_filter('aioseop_home_page_title', function($text) { return do_shortcode($text); });
add_filter('aioseop_title', function($text) { return do_shortcode($text); });
add_filter('aioseop_title_page', function($text) { return do_shortcode($text); });
add_filter('aioseop_title_single', function($text) { return do_shortcode($text); });
add_filter('aioseop_description', function($text) { return do_shortcode($text); });
add_filter('aioseop_description_attributes', function($text) { return do_shortcode($text); });
add_filter('aioseop_description_full', function($text) { return do_shortcode($text); });
add_filter('aioseop_description_override', function($text) { return do_shortcode($text); });
add_filter('aioseop_keywords', function($text) { return strtolower(do_shortcode(strtoupper($text))); });
add_filter('aioseop_keywords_attributes', function($text) { return strtolower(do_shortcode(strtoupper($text))); });
add_filter('aiosp_opengraph_meta', function($text) { return do_shortcode($text); });
if(!is_admin()) add_filter('the_title', function($title) { return do_shortcode($title); });

// Remove oembed
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('wp_head', 'wp_oembed_add_host_js');

// Allow SVG Upload
add_filter('upload_mimes', function($upload_mimes) {
	$upload_mimes['svg'] = 'image/svg+xml';
	$upload_mimes['svgz'] = 'image/svg+xml';
	return $upload_mimes;
}, 10, 1);

// Disable WP-JSON
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');
add_filter('rest_enabled', '__return_false');
add_filter('rest_jsonp_enabled', '__return_false');
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('template_redirect', 'rest_output_link_header', 11);
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
add_filter('option_use_smilies', '__return_false');

// Remove native prev/next rel links
add_filter('previous_post_rel_link', function() { return false; });
add_filter('next_post_rel_link', function() { return false; });

// Allow featured images
add_theme_support('post-thumbnails');

// Disable srcset generation
add_filter('wp_calculate_image_srcset', '__return_false');

// Disable author queries/pages for security
add_action('template_redirect', function() { if(is_author()) { global $wp_query; $wp_query->set_404(); status_header( 404 ); } });
add_filter('author_link', function($content) { return get_option('home'); });

// Deregister native jQuery
add_action('wp_enqueue_scripts', function() {
	wp_deregister_script('jquery');
});
/*
// Remove type="text/javascript"
add_filter('script_loader_tag', function($ret) {
	return str_replace("'", '"', str_replace("type='text/javascript' ", '', $ret));
});
*/

// completely disable image size threshold
add_filter('big_image_size_threshold', '__return_false');

// increase the image size threshold to 6000px
// add_filter('big_image_size_threshold', function($threshold) {
// 	return 6000;
// }, 999, 1);
