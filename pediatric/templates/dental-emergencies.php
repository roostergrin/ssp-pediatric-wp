<?
# Template Name: Dental Emergencies
global $providers, $reviews;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();

$hero_heading = get_post_meta(get_the_ID(),'dental_emergencies_hero_heading', true);
$hero_copy = get_post_meta(get_the_ID(),'dental_emergencies_hero_copy', true);
$text_for_link = get_post_meta(get_the_ID(),'dental_emergencies_link_text', true);
partial('section.pediatric.split-static', [
	'id' => 'section-3',
	'classes' => ['hero', 'southmoor'],
	'h1' => $hero_heading,
	'h1_classes' => ['line-height-1'],
	'copy' => apply_filters('the_content',$hero_copy, true),
	'image' => [
		'src' => wp_get_attachment_image_src(get_post_meta(get_the_id(), 'dental_emergencies_hero_image', true), 'medium_large')[0],
		'alt' => get_post_meta(get_post_meta(get_the_id(), 'dental_emergencies_hero_image', true),'_wp_attachment_image_alt', true),
		'classes' => ['bubble']
	],
	'cta' => '<a href="#dental-emergencies-treatments-carousel" class="cta orange hover-green" title="How to handle an injury">' . $text_for_link . '</a>',

]);

$slides_count = get_post_meta(get_the_ID(),'section_2_tab_dental_emergencies_slides', true);
$slides = [];

if (!empty($slides_count)) {
	for ($i = 0; $i < $slides_count; $i++) {
		$slides[] = [
			'heading' => get_post_meta(get_the_ID(), 'section_2_tab_dental_emergencies_slides_' . $i . '_heading', true),
			'content' =>apply_filters('the_content', get_post_meta(get_the_ID(), 'section_2_tab_dental_emergencies_slides_' . $i . '_copy', true)),
		];
	}
	partial('section.wrapper', [
		'classes' => ['treatments'],
		'anchor_name' => 'dental-emergencies-treatments-carousel',
		'partials' => [
			[
				'name' => 'section.pediatric.bubbles',
				'parts' => [
					'classes' => ['top', 'var-1', (sanitize_title($brand->palette)=='smiles-in-motion' ? 'green' : 'blue'), 'var-1-custom']
				]
			],
			[
				'name' => 'section.pediatric.treatments-carousel',
				'parts' =>[
					'heading' => get_post_meta(get_the_ID(),'section_2_tab_dental_emergencies_heading', true),
					'h_tag' => 'h3',
					'content' => apply_filters('the_content', get_post_meta(get_the_ID(),'section_2_tab_dental_emergencies_copy', true)),
					'slides' => $slides,
					'copy_classes' => ['dental-injuries', 'no-top-image', sanitize_title($brand->palette).'-palette']
				]
			],

		]
	]);
}

$icons = [
	[
		'img' => get_post_meta(get_the_ID(),'section_3_emergency_contact_slides_0_section_3_emergency_contact_slides_0_icon', true),
		'copy' => get_post_meta(get_the_ID(),'section_3_emergency_contact_slides_0_section_3_emergency_contact_slides_0_copy', true),
	],
	[
		'img' => get_post_meta(get_the_ID(),'section_3_emergency_contact_slides_1_section_3_emergency_contact_slides_1_icon', true),
		'copy' => get_post_meta(get_the_ID(),'section_3_emergency_contact_slides_1_section_3_emergency_contact_slides_1_copy', true),
	],
	[
		'img' => get_post_meta(get_the_ID(),'section_3_emergency_contact_slides_2_section_3_emergency_contact_slides_2_icon', true),
		'copy' => get_post_meta(get_the_ID(),'section_3_emergency_contact_slides_2_section_3_emergency_contact_slides_2_copy', true),
	]
];

$three_icons_bg_color = 'bg-blue';
$icons_bg_color = 'icons-red';
if(sanitize_title($brand->palette)=='smiles-in-motion'){
	$three_icons_bg_color = 'bg-green';
	$icons_bg_color = 'icons-gray';
}

partial('section.pediatric.three-icons', [
	'classes' => [$three_icons_bg_color, 'white', $icons_bg_color],
	'heading' => 'If this is a true emergency, please contact us',
	'copy' => 'If your child is suffering from a dental emergency and needs to be seen after hours, our pediatric dentists are available to provide emergency dental care. The '.do_shortcode("[BRAND_TITLE]").' dentist on call will be paged and will return your call as promptly as possible.',
	'heading_classes' => ['white', 'h3'],
	'static_icons' => false,
	'icons' => $icons
]);

partial('section.maps.search');

get_footer();
