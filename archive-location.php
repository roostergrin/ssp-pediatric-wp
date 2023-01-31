<?
global $locations;

$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');
if(is_single_location_brand()) {
	header("Location: " . site_url());
	exit;
}
get_header();
$all_locations = $locations->searchLocations($brand_location_ids);
usort($all_locations, function($a, $b) {
	return $a->post_title <=> $b->post_title;
});

partial('section.wrapper', [
	'classes' => ['bg-primary'],
    'partials' => [
		[
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['background', 'var-2', 'var-2-custom', 'white']
            ]
		],
        [        
            'name' => 'section.locations-grid',
            'parts' => [
                'heading' => $brand->locations_heading,
				'locations' => $all_locations,
            ]
        ],
    ],
]);

partial('section.maps.search');
add_action('wp_footer', function() { ?>
<script>
if (window.dataLayer !== undefined) {
	window.dataLayer.push({
		 event: 'experimentVariantLocationMapPosition',
		 variant: '<?= ab_variant() ?>'
	});
}
</script>
<?
}, 1e6);
get_footer();
