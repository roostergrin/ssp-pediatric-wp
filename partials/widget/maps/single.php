<?
!empty($loc) ? $location = $loc : $location = is_location();

// Build directions link
if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPod')) {
	$directions_base = 'maps://maps.google.com/maps?daddr=';
} else {
	$directions_base = 'https://maps.google.com/maps?daddr=';
}
$directions_meta = $location->address.','.$location->city.','.$location->state.','.$location->zip;
$directions_suffix = urlencode($directions_meta);
$directions = $directions_base . $directions_suffix;
$directions = !empty($location->google_cid) ? 'https://www.google.com/maps/?cid='.($location->google_cid) : $directions;
wp_localize_script('location-single-map', 'map_data', [
	'latitude' => $location->latitude,
	'longitude' => $location->longitude,
	'name' => $location->post_title,
	'directions' => $directions,
	'phone_number' => $location->phone,
]);
wp_enqueue_script('location-single-map');
?>
<div id="map" class="map-single"></div>
