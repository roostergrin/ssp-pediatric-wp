<?
global $locations;

$brand = is_brand();
$all_locations = !empty($all_locations) ? $all_locations : array_filter($locations->locations, function($location) use ($brand) {
	$brand_relationship = property_exists($location, 'brand_relationship') && !empty($location->brand_relationship) ? unserialize($location->brand_relationship) : false;
	return !empty($brand_relationship) ? in_array($brand->ID, $brand_relationship) : false;
});

$map_data = [
	'selector' => 'map',
	'offices' => array_values($all_locations),
	'provider' => null,
	'show_detail' => false,
	'selected_location' => $selected_location ?? false,
];

if(strstr(get_page_template(), 'contact')) {
	$map_data['show_detail'] = true;
}

elseif(in_array('page-template-bio', get_body_class())) {
	$map_data['is_bio'] = true;
}

# Patch: Shift map on desktop for GR to compensate for the search
if($brand->ID == 3291) $map_data['pan_x_search'] = true;

wp_localize_script('internal-map', 'map_data', $map_data);
wp_enqueue_script('internal-map');
?>
<div id="map" class="<?= $brand->post_name; ?>"></div>
