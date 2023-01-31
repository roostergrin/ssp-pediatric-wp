<?
global $wp, $providers, $edu_associations, $pro_affiliations;
$brand = is_brand();
$provider = $providers->providers[get_the_ID()];
$relative_url = $wp->request;
$selected_location = 0;

if( $brand->ID == 18088 && $provider->ID != 18453 ) { // SIM4KIDS - Amanda Spitz
	$selected_location = unserialize($provider->selected_location_relationship)[0];
}

get_header();

$provider_mi = $provider->middle_initial ? ' ' . $provider->middle_initial : '';
$provider_name = $provider->first_name . $provider_mi . ' ' . $provider->last_name;
partial('section.copy.two-cols-with-image-popup-content', [
	'classes' => [ sanitize_title($brand->post_title) ],
	'article' => [
		'heading' => $provider->section_one_heading,
		'copy' => $provider->bio_copy,
	],
	'image' => $provider->image,
	'mobile_image' => $provider->image,
	'overlay_name' => $provider_name . ',',
	'overlay_heading' => $provider_name,
	'overlay_degree' => $provider->degree,
	'overlay_specialty_title' => $provider->specialty_title,
	'overlay_content' => $provider->section_one_overlay_content,
]);
$tri_carousel_slides = [];
$slide_image_ids = unserialize($provider->image_gallery);
if (!empty($slide_image_ids)) {
	foreach($slide_image_ids as $attachment_id) {
		$attachment = wp_get_attachment_image_src($attachment_id, 'medium_large');
		$tri_carousel_slides[] = [
			'src' => $attachment[0],
			'width' => $attachment[1],
			'height' => $attachment[2],
			'alt' => !empty(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) ? str_replace('_', ' ', get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) : str_replace('_', ' ', get_the_title($attachment_id)),
			'classes' => [!empty(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) ? get_post_meta($attachment_id, '_wp_attachment_image_alt', true) : get_the_title($attachment_id)]
		];
	}
	shuffle($tri_carousel_slides);
    partial('section.wrapper', [
        'partials' => [
            [
                'name' => 'section.pediatric.bubbles',
                'parts' => [
                    'classes' => ['top', 'var-1', 'var-1-custom2', 'green']
                ]
            ],
            [
                'name' => 'section.tri-carousel',
                'parts' => [
                    'images' => $tri_carousel_slides,
                    'classes' => ['bg-green', sanitize_title($brand->palette).'-palette']
                ],
            ],
            [
                'name' => 'section.pediatric.bubbles',
                'parts' => [
                    'classes' => ['bottom', 'var-2', 'green']
                ]
            ],
        ]
    ]);
}
$all_edu_associations = array_filter($edu_associations->edu_associations, function($association) {
	$provider_relationships = property_exists($association, 'provider_relationship') ? unserialize($association->provider_relationship) : false;
	return !empty($provider_relationships) && is_array($provider_relationships) ? in_array(get_the_ID(), $provider_relationships) : get_the_ID() === $provider_relationships;
});
usort($all_edu_associations, function($a, $b) {
	return $a->menu_order <=> $b->menu_order;
});
$all_pro_affiliations = array_filter($pro_affiliations->pro_affiliations, function($affiliation) {
	$provider_relationships = property_exists($affiliation, 'provider_relationship') ? unserialize($affiliation->provider_relationship) : false;
	return !empty($provider_relationships) && is_array($provider_relationships) ? in_array(get_the_ID(), $provider_relationships) : get_the_ID() === $provider_relationships;
});
usort($all_pro_affiliations, function($a, $b) {
	return $a->heading <=> $b->heading;
});
partial('section.professional-associations', [
	'edu_associations' => $all_edu_associations,
	'pro_affiliations' => $all_pro_affiliations,
]);

if(!is_single_location_brand()) {
	partial('section.maps.bio-location', [
		'provider' => $provider,
		'info_box_copy' => $provider->section_four_heading,
		'relative_url' => $relative_url,
		'selected_location' => $selected_location,
		'classes' => [sanitize_title($brand->palette).'-palette']
	]);
} else {	
	$location_id = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');
	$location_id = reset($location_id);
	$location = $locations->locations[strval($location_id)];
    partial('section.maps.location', [
		'loc' => $location		
	]);
}
//partial('section.hero.full', [
//	'image' => [
//		'src' => wp_get_attachment_image_src($provider->section_five_bottom_desktop_image, 'full')[0],
//		'alt' => get_post_meta($provider->section_five_bottom_desktop_image, '_wp_attachment_image_alt', true),
//		'width' => '1536',
//		'height' => '864',
//		'classes' => ['desktop', strtolower($provider->last_name)],
//	],
//	'mobile_image' => [
//		'src' => wp_get_attachment_image_src($provider->section_five_bottom_mobile_image, 'medium_large')[0],
//		'alt' => get_post_meta($provider->section_five_bottom_mobile_image, '_wp_attachment_image_alt', true),
//		'width' => '768',
//		'height' => '432',
//		'classes' => ['mobile', strtolower($provider->last_name)],
//	],
//	'classes' => ['bio', strtolower($provider->last_name)],
//]);
get_footer();
