<?
global $site_404_settings;
$settings = $site_404_settings->_404;

get_header();
partial('section.hero.standard', [
	'image' => [
		'src' => get_stylesheet_directory_uri() . '/images/placeholder/404_page_centered.jpeg',
		'alt' => 'Two girls laughing and holding hands',
		'classes' => ['']
	],
	'classes' => ['error404'],
	'h1' => 'Let\'s get you smiling again',
	'h1_classes' => ['primary'],
	'wrapper_classes' => ['left-side'],
	'content' => apply_filters('the_content', 'Unfortunately, we’re not able to find the page you were looking for. Please take a look at the URL you typed in to make sure it is correct. You can also use the navigation at the top to find the page you were looking for. If you are looking to schedule an appointment, you can do so <a href="'. _location_based_url('kids-dentist-appointment') . '">here</a>.')
]);
get_footer();
