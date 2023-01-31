<?
# Template Name: Contact
$brand = is_brand();

get_header();

$heading = get_post_meta(get_the_ID(),'contact_hero_heading', true);
$content = get_post_meta(get_the_ID(),'contact_hero_subheading', true);



partial('section.form', [
	'classes' => ['contact'],
	'form' => 'contact',
	'heading' => [
		'tag' => 'h1',
		'text' => $heading,
		'classes' => ['line-height-1'],
	],
	'content' => apply_filters('the_content', $content),
]);

if(!is_single_location_brand()) {
	partial('section.maps.search');
} else {
    $location_id = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');
	$location_id = reset($location_id);
	$location = $locations->locations[strval($location_id)];
    partial('section.maps.location', [
        'classes' => ['single-location-brand'],
		'loc' => $location,
		'heading' => $location->section_five_heading,
		'content' => $location->section_five_content
	]);
}
get_footer();
