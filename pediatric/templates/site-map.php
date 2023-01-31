<?
# Template Name: Site map
global $locations, $providers;
$provider_links = $page_links = $blog_links = $category_links = $location_links = [];

$exclusions = sitemap_exclusions();
$brand = is_brand();
$location = is_location();

/**
 * header needs to be set initially
 * otherwise if set at the bottom after blog content
 * the post ID is changed and displays the incorrect page title
 */
get_header();

if (empty($location)) {
	$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');
	$provider_objects = array_values(array_filter($providers->providers, function($provider) use ($brand_location_ids) {
		$location_relationship = !empty($provider->location_relationship) ? unserialize($provider->location_relationship) : false;
		return $location_relationship == false ? [] : array_intersect($location_relationship, $brand_location_ids);
	}));

	/**
	 * manually add Amanda Spitz for SIM4KIDS 
	 * since she has no location relationship
	 */	
	if($brand->ID == 18088) { // SIM4KIDS		
		array_push( $provider_objects, $providers->providers[18438] );
	}	

	if (!empty($provider_objects)) {
		usort($provider_objects, function($a, $b) {
			return $a->menu_order <=> $b->menu_order;
		});
		$provider_links[] = "<h2>Our Team</h2>\r\n<ul>";
		foreach ($provider_objects as $provider) {
			$provider_title = $provider->first_name.' '.$provider->last_name.', '.rtrim(explode(' ', $provider->degree)[0], ',');
			$provider_links[] = '<li><a href="'.(brand_url("/".(get_relative_pediatric_provider_url($provider->relative_url))."/")).'">'.($provider_title).'</a></li>';
		}
		$provider_links[] = '</ul>';
	}
	$location_objects = $locations->searchLocations($brand_location_ids);
	if (!empty($location_objects)) {
		usort($location_objects, function($a, $b) {
			return $a->post_title <=> $b->post_title;
		});
		$location_links[] = "<h2>Locations</h2>\r\n<ul>";
		if(is_single_location_brand()) {
			$location = reset($location_objects);
			$location_links[] = '<li><a href="'.(brand_url("/")).'">Pediatric office in '.($location->post_title).', '.($location->state).' '.($location->zip).'</a></li>';
		} else {
			foreach ($location_objects as $location) {
				$location_links[] = '<li><a href="'.(get_relative_pediatric_location_url(brand_url("/".($location->relative_url)."/"))).'">Pediatric office in '.($location->post_title).', '.($location->state).' '.($location->zip).'</a></li>';
			}
		}
		$location_links[] = '</ul>';
	}
} else {	
	array_push($exclusions, 'pediatric-dentist');
	$location_relationships = property_exists($location, 'section_three_providers_relationship') && !empty($location->section_three_providers_relationship) ? unserialize($location->section_three_providers_relationship) : false;
	$provider_objects = $providers->searchProviders($location_relationships);
	if (!empty($provider_objects)) {
		usort($provider_objects, function($a, $b) {
			return $a->menu_order <=> $b->menu_order;
		});
		$provider_links[] = "<h2>Our Team</h2>\r\n<ul>";
		foreach ($provider_objects as $provider) {
			$provider_title = $provider->first_name.' '.$provider->last_name.', '.rtrim(explode(' ', $provider->degree)[0], ',');
			$provider_links[] = '<li><a href="'.(brand_url("/".get_relative_pediatric_provider_url($provider->relative_url)."/")).'">'.($provider_title).'</a></li>';
		}
		$provider_links[] = '</ul>';
	}
}

$provider_links_string = !empty($provider_links) ? implode('', $provider_links) : false;
$page_slugs = list_all_pages(true);
if (!empty($page_slugs)) {	
	usort($page_slugs, function($a, $b) {
		return $a->post_title <=> $b->post_title;
	});

	$page_links[] = "<h2>Site Pages</h2>\r\n<ul>";

	/**
	 * somehow the above conditions for provider pages
	 * sets a brand-level postID to a location-level
	 * so, reseting the $location object is necessary here
	 */
	$location_reset = is_location();

	foreach ($page_slugs as $page) {
		if($page->post_name != 'site-map') {		
			$url = is_local() ? str_replace('admin', 'local', get_permalink($page->ID)) : get_permalink($page->ID);
			$relative_url = str_replace(brand_host(), '', $url);
			$relative_url = str_replace( 'orthodontist-office', 'pediatric-dentist', $relative_url );			
			$relative_exclusions = substr(rtrim($relative_url, '/'), 1);
			
			if(!in_array($relative_exclusions, $exclusions)){
				if($location_reset != false && $page->post_parent == $location_reset->ID) {					
					$page_links[] = '<li><a href="'.$relative_url.'">'.($page->post_title).'</a></li>';
				} else {
					if ($page->post_parent == 0) {
						$page_links[] = '<li><a href="'.$relative_url.'">'.($page->post_title).'</a></li>';
					}
				}
			}
		}
	}
	$page_links[] = '</ul>';
}
$page_links_string = !empty($page_links) ? implode('', $page_links) : false;

$arrays_of_post_categories = [];
$post_slugs = list_all_blogs(true);
if (!empty($post_slugs)) {
	$blog_links[] = "<h2>Blog Posts</h2>\r\n<ul>";
	foreach ($post_slugs as $post) {
		$url = is_local() ? str_replace('admin', 'local', get_permalink($post->ID)) : get_permalink($post->ID);
		$relative_url = str_replace(brand_host(), '', $url);
		$blog_links[] = '<li><a href="'.(brand_url($relative_url)).'">'.($post->post_title).'</a></li>';
		$arrays_of_post_categories[] = wp_get_post_categories($post->ID, ['fields' => 'all']);
	}
	$blog_links[] = '</ul>';
}
$blog_links_string = !empty($blog_links) ? implode('', $blog_links) : false;
if (!empty($arrays_of_post_categories)) {
	$all_post_categories = [];
	foreach ($arrays_of_post_categories as $post_categories_array) {
		foreach ($post_categories_array as $category) {
			$all_post_categories[$category->term_id] = $category;
		}
	}
	if (!empty($all_post_categories)) {
		$all_post_categories = array_values($all_post_categories);
		usort($all_post_categories, function($a, $b) {
			return $a->name <=> $b->name;
		});
		$category_links[] = "<h2>Blog Post Categories</h2>\r\n<ul>";
		foreach ($all_post_categories as $category) {
			$category_links[] = '<li><a href="'.(brand_url('/kids-dentist-blog/category/'.($category->slug).'/')).'">'.($category->name).'</a></li>';
		}
		$category_links[] = '</ul>';
	}
}
$category_links_string = !empty($category_links) ? implode('', $category_links) : false;
$location_links_string = !empty($location_links) ? implode('', $location_links) : false;

partial('section.site-map', [
	'providers_links' => $provider_links_string,
	'pages_links' => $page_links_string,
	'blogs_links' => $blog_links_string,
	'categories_links' => $category_links_string,
	'locations_links' => $location_links_string,
]);
get_footer();
