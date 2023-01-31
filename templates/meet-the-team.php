<?
# Template Name: Meet the team
global $providers;
$brand = is_brand();
$location = is_location();

get_header();

if( $brand->ID == 12374 ) { // Southmoor
	$provider_relationships = get_post_meta(get_the_ID(), 'meet_the_team_providers_relationship', true);
	$all_providers = $providers->searchProviders($provider_relationships);
	usort($all_providers, function($a, $b) {
		return $a->menu_order <=> $b->menu_order;
	});
	if(count($all_providers) > 1) {
		$all_providers = $all_providers;
		$carousel_pagination_classes = ['pagination-reversed'];
	} else {
		$all_providers = reset($all_providers);
		$carousel_pagination_classes = NULL;
	}
	partial('section.providers.carousel', [
		'h1' => get_post_meta(get_the_ID(), 'meet_the_team_heading', true),
		'h1_classes' => ['primary'],
		'h2' => get_post_meta(get_the_ID(), 'meet_the_team_subheading', true),
		'h2_classes' => ['primary', 'sub-heading'],
		'all_providers' => $all_providers,
		'content' => get_post_meta(get_the_ID(), 'meet_the_team_content', true),
		'pagination_classes' => $carousel_pagination_classes,
		'hide_pagination' => false,
		'hide_meet_the_team' => true,
	]);
}

if( $brand->ID == 18088) { // Smiles in Motion
	partial('section.pediatric.split-static', [
		'classes' => ['hero', 'reverse', 'single-location', 'meet-the-team', 'sim4kids'],
		'h1' => get_post_meta(get_the_ID(), 'meet_the_team_heading', true),
		'h1_classes' => ['primary'],
		'heading' => get_post_meta(get_the_ID(), 'meet_the_team_subheading', true),
		'heading_classes' => ['primary', 'sub-heading'],		
		'copy' => apply_filters('the_content', get_post_meta( get_the_id(), 'meet_the_team_content', true ) ),
		'image' => [
			'src' => get_stylesheet_directory_uri() . '/images/placeholder/SIM_Doctors_3.jpeg', 
			'alt' => 'Smiles in Motion super team',
			'classes' => ['bubble', 'animatable']
		]
	]);
}

$tri_carousel_slides = [];
$slides_count = get_post_meta(get_the_ID(), 'meet_the_team_slides', true) ?? 0;
if ($slides_count > 0) {
	for ($i = 0; $i < $slides_count; $i++) {
		$attachment_id = get_post_meta(get_the_ID(), 'meet_the_team_slides_'.($i).'_image', true);
		$attachment = wp_get_attachment_image_src($attachment_id, 'medium_large');
		$tri_carousel_slides[] = [
			'src' => $attachment[0],
			'width' => $attachment[1],
			'height' => $attachment[2],
			'alt' => !empty(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) ? str_replace('_', ' ', get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) : str_replace('_', ' ', get_the_title($attachment_id)),
			'classes' => [!empty(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) ? get_post_meta($attachment_id, '_wp_attachment_image_alt', true) : get_the_title($attachment_id)]
		];
	}
}
shuffle($tri_carousel_slides);
$cols = [];
$num_cols = get_post_meta(get_the_ID(), 'meet_the_team_three_cols', true) ?? 0;
if($num_cols > 0) {
	for($j = 0; $j < $num_cols; $j++) {
		$col_copy = 'meet_the_team_three_cols_' . $j . '_copy';
		$cols[] =  apply_filters('the_content', get_post_meta(get_the_ID(), $col_copy, true));
	}
}
partial('section.wrapper', [
	'partials' => [
		[
			'name' => 'section.pediatric.bubbles',
			'parts' => [
				'classes' => ['green', 'top', 'var-1', 'var-1-custom', 'alt']
			],
		],
		[
			'name' => 'section.tri-carousel',
			'parts' => [
				'classes' => ['bg-green', sanitize_title($brand->palette).'-palette'],
				'images' => $tri_carousel_slides
			],
		],
		[
			'name' => 'section.copy.static-three-col',
			'parts' => [
				'classes' => ['bg-green'],
				'heading' => get_post_meta(get_the_ID(), 'meet_the_team_three_cols_heading', true),
				'cols' => $cols
			],
		],
		[
			'name' => 'section.pediatric.bubbles',
			'parts' => [
				'classes' => ['green', 'bottom', 'var-3']
			],
		],
	]
]);
$left_photo_id = get_post_meta(get_the_ID(), 'meet_the_team_staggered_left', true);
$right_photo_id = get_post_meta(get_the_ID(), 'meet_the_team_staggered_right', true);
partial('section.photos-staggered', [
	'classes' => ['meet-the-team'],
	'left' => [
		'src' => wp_get_attachment_image_src($left_photo_id, 'medium_large')[0],
		'alt' => get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true),
	],
	'right' => [
		'src' => wp_get_attachment_image_src($right_photo_id, 'full')[0],
		'alt' => get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true),
	],
]);

get_footer();
