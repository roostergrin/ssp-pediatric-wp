<?
// ---------- Quick Utility Functions ---------- //

function get_location_name_from_id($id) {
	global $locations;
	return $locations->locations[$id]->post_title ? $locations->locations[$id]->post_title : strval($id).': un-handled';
}

function get_brands_for_location($location_id, $admin_link = false) {
	global $brands;

	$relationship_ids = get_post_meta($location_id, 'location_brand_relationship', true);
	$location_brands = [];
	if (property_exists($brands, 'brands') && !empty($brands->brands)) {
		$location_brands = array_filter($brands->brands, function($brand) use ($relationship_ids) {
			return is_array($relationship_ids) ? in_array($brand->ID, $relationship_ids) : $brand->ID == $relationship_ids;
		});
		sort($location_brands);
	}

	$brand_str = [];
	foreach ($location_brands as $brand) {
		$brand_str[] = !empty($admin_link) ? '<a href="'.get_edit_post_link($brand->ID).'" class="admin-edit-item">'.$brand->post_title.'</a>' : $brand->post_title;
	}
	return $brand_str;
}

function get_icon_class_by_icon_name($icon_name){
    //TODO: Add missing icon classes
    $icon_class_array = [
        'adjust' => 'icon-adjust',
        'badge' =>'icon-badge',
        'calendar' => 'icon-form_calendar-icon',
        'chair' => 'icon-benefit_orthodontic-treatment',
        'xrays' => 'icon-benefit_digital-x-rays',
        '3d-scan' => 'icon-benefit_3d-scan',
        'success-planning' => 'icon-benefit_success-planning',
        'personalized-treatment-plan' => 'icon-benefit_personalized-treatment-plan',
        'calc' => 'icon-calculator'
    ];

    if (array_key_exists (strval($icon_name), $icon_class_array)) {
        return $icon_class_array[$icon_name];
    }

    return '';
}

function get_providers() {
	global $providers;

	$brand = is_brand();
	$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');
	$location = is_location();
	$all_providers = [];	

	if (property_exists($providers, 'providers') && !empty($providers->providers)) {
        if($location) {			
            $all_providers = array_values(array_filter($providers->providers, function($provider) use ($brand_location_ids) {
                $location_relationship = !empty($provider->location_relationship) ? unserialize($provider->location_relationship) : false;
				return ($location_relationship == false ? null : array_intersect($location_relationship, $brand_location_ids));
            }));

            if (!empty($location)) {
                $all_providers = array_values(array_filter($providers->providers, function($provider) use ($location) {
                    $location_relationship = !empty($provider->location_relationship) ? unserialize($provider->location_relationship) : false;
                    return is_array($location_relationship) ? in_array($location->ID, $location_relationship) : $location->ID === $location_relationship;
                }));
            }
            usort($all_providers, function($a, $b) {
                return $a->menu_order <=> $b->menu_order;
            });
        } else {
            $all_providers = array_values(array_filter($providers->providers, function($provider) use ($brand) {
				$brand_relationship = !empty($provider->brand_relationship) ? unserialize($provider->brand_relationship) : false;
				return is_array($brand_relationship) ? in_array($brand->ID, $brand_relationship) : $brand->ID === $brand_relationship;
			}));
            
			usort($all_providers, function($a, $b) {
                return $a->menu_order <=> $b->menu_order;
            });
        } 
	}

	return $all_providers;
}

function is_single_location_brand() {
	$brand = is_brand();
	return count(wp_list_pluck(get_locations_for_brand($brand->ID), 'ID')) === 1 ? true : false;
}

function get_locations_for_brand($brand_id, $admin_link = false) {
	global $locations;

	$brand_locations = [];
	if (property_exists($locations, 'locations') && !empty($locations->locations)) {
		$brand_locations = array_filter($locations->locations, function($location) use ($brand_id) {
			return in_array($brand_id, (array) get_post_meta($location->ID, 'location_brand_relationship', true));
		});
		usort($brand_locations, function($a, $b) {
			return $a->post_title <=> $b->post_title;
		});
	}

	$location_str = [];
	foreach ($brand_locations as $location) {
		$location_str[] = !empty($admin_link) ? '<a href="'.get_edit_post_link($location->ID).'" class="admin-edit-item">'.$location->post_title.'</a>' : $location;
	}
	return $location_str;
}

function get_region_for_location($location_id, $admin_link = false) {
	global $regions;

	$location_regions = [];
	if (property_exists($regions, 'regions') && !empty($regions->regions)) {
		$location_regions = array_filter($regions->regions, function($region) use ($location_id) {
			return in_array($location_id, (array) get_post_meta($region->ID, 'region_location_relationship', true));
		});
		usort($location_regions, function($a, $b) {
			return $a->post_title <=> $b->post_title;
		});
	}

	$region_str = [];
	foreach ($location_regions as $region) {
		$region_str[] = !empty($admin_link) ? '<a href="'.get_edit_post_link($region->ID).'" class="admin-edit-item">'.$region->post_title.'</a>' : $region->post_title;
	}
	return $region_str;
}

function get_regions_for_edu_association( $edu_id ) {
	global $regions;

	$providers = get_post_meta($edu_id, 'edu_association_provider_relationship', true);
	$locations = [];
	$filtered_regions = [];

	if( count($providers) == 1 ){
		$locations = get_location_ids_for_provider($providers[0]);
		
		foreach ($locations as $key => $location_id) {
			$region = get_region_for_location($location_id, 1);
			array_push( $filtered_regions, $region[0] );
		}
		
	} else {
		return null;
	}

	return $filtered_regions;
}

function get_locations_for_region($region_id, $admin_link = false) {
	global $locations;

	$region_locations = [];
	if (property_exists($locations, 'locations') && !empty($locations->locations)) {
		$region_locations = array_filter($locations->locations, function($location) use ($region_id) {
			return in_array($location->ID, (array) get_post_meta($region_id, 'region_location_relationship', true));
		});
		usort($region_locations, function($a, $b) {
			return $a->post_title <=> $b->post_title;
		});
	}

	$location_str = [];
	foreach ($region_locations as $location) {
		$location_str[] = !empty($admin_link) ? '<a href="'.get_edit_post_link($location->ID).'" class="admin-edit-item">'.$location->post_title.'</a>' : $location->post_title;
	}
	return $location_str;
}

function get_brands_for_post($post_id, $admin_link = false) {
	global $brands;

	$relationship_ids = get_post_meta($post_id, 'post_brand_relationship', true);
	$post_brands = $brands->searchBrands($relationship_ids);
	usort($post_brands, function($a, $b) {
		return $a->post_title <=> $b->post_title;
	});

	$brand_str = [];
	foreach ($post_brands as $brand) {
		$brand_str[] = !empty($admin_link) ? '<a href="'.get_edit_post_link($brand->ID).'" class="admin-edit-item">'.$brand->post_title.'</a>' : $brand->post_title;
	}
	return $brand_str;
}

function get_brand_for_page($page_id, $admin_link = false) {
	global $brands;

	$relationship_ids = get_post_meta($page_id, 'page_brand_relationship', true);
	$page_brands = $brands->searchBrands($relationship_ids);
	usort($page_brands, function($a, $b) {
		return $a->post_title <=> $b->post_title;
	});

	$brand_str = [];
	foreach ($page_brands as $brand) {
		$brand_str[] = !empty($admin_link) ? '<a href="'.get_edit_post_link($brand->ID).'" class="admin-edit-item">'.$brand->post_title.'</a>' : $brand->post_title;
	}
	return $brand_str;
}

function get_parent_for_page($page_id, $admin_link = false) {
	global $locations;
	$location_parent_id = get_post_meta($page_id, 'page_location_parent', true);

	if (!empty($location_parent_id)) {
		$location = $locations->locations[$location_parent_id];
		return !empty($admin_link) ? '<a href="'.get_edit_post_link($location->ID).'" class="admin-edit-item">'.$location->post_title.'</a>' : $location->post_title;
	}

	return '-';
}

function get_locations_for_provider($provider_id, $admin_link = false) {
	global $locations;

	$relationship_ids = get_post_meta($provider_id, 'provider_location_relationship', true);
	$provider_locations = $locations->searchLocations($relationship_ids);
	usort($provider_locations, function($a, $b) {
		return $a->post_title <=> $b->post_title;
	});

	$location_str = [];
	foreach ($provider_locations as $location) {
		$location_str[] = !empty($admin_link) ? '<a href="' . (get_edit_post_link($location->ID)) . '" class="admin-edit-item">' . ($location->post_title) . '</a>' : $location->post_title;
	}
	return $location_str;
}

function get_location_ids_for_provider( $provider_id ) {
	global $locations;

	$relationship_ids = get_post_meta($provider_id, 'provider_location_relationship', true);
	$provider_locations = $locations->searchLocations($relationship_ids);
	usort($provider_locations, function($a, $b) {
		return $a->post_title <=> $b->post_title;
	});

	$location_ids = [];
	foreach ($provider_locations as $location) {
		array_push( $location_ids, $location->ID );
	}
	return $location_ids;
}

function get_brand_for_provider($provider_id, $admin_link = false) {
	global $brands;

	$relationship_id = get_post_meta($provider_id, 'provider_brand_relationship', true);
	if( empty( $relationship_id ) ) return ''; 

	$brand_links = '';
	foreach($brands->brands as $brand) {
		if(in_array($brand->ID, $relationship_id) ) {
			$brand_links .= '<a href="' . (get_edit_post_link($brand->ID)) . '" class="admin-edit-item">'. ($brand->post_title) .'</a><br>';
		}
	}
	return !empty($brand_links) ? $brand_links : '';
}

function get_region_for_smile_transformation($smile_transformation_id, $admin_link = false) {
	global $regions;

	$relationship_ids = get_post_meta($smile_transformation_id, 'smile_transformation_region_relationship', true);
	$region_titles = array();
	foreach($regions->regions as $region) {
		if( in_array($region->ID, $relationship_ids)) {
				$region_titles[] = !empty($admin_link)?'<a href="' . (get_edit_post_link($region->ID)) . '" class="admin-edit-item">' . ($region->post_title) . '</a>' : $region->post_title;
			}
		}
	return $region_titles;
}
function get_brand_for_review($review_id, $admin_link = false) {
	global $brands;

	$relationship_ids = get_post_meta($review_id, 'review_relationships', true);
	if( empty( $relationship_ids ) ) return []; 

	$brand_titles = array();
	foreach($brands->brands as $brand) {
		if( in_array($brand->ID, $relationship_ids)) {
				$brand_titles[] = !empty($admin_link)?'<a href="' . (get_edit_post_link($brand->ID)) . '" class="admin-edit-item">' . ($brand->post_title) . '</a>' : $brand->post_title;
			}
		}
	return $brand_titles;
}
function get_brand_for_faq($faq_id, $admin_link = false) {
	global $brands;

	$relationship_ids = get_post_meta($faq_id, 'faq_relationships', true);
	$brand_titles = array();
	foreach($brands->brands as $brand) {
		if( in_array($brand->ID, $relationship_ids)) {
				$brand_titles[] = !empty($admin_link)?'<a href="' . (get_edit_post_link($brand->ID)) . '" class="admin-edit-item">' . ($brand->post_title) . '</a>' : $brand->post_title;
			}
		}
	return $brand_titles;
}

function get_faqs_for_page() {
	global $faqs;

	$page_faqs = [];
	$brand = is_brand();

	if( !is_location() ) {			
		foreach ($faqs->faqs as $key => $faq) {
			if( in_array( strval($brand->ID), unserialize($faq->relationships) ) ) {
				array_push( $page_faqs, $faq );
			}
		}	
	} else {		
		foreach ($faqs->faqs as $key => $faq) {
			if( in_array( strval(is_location()->ID), unserialize($faq->relationships) ) ) {
				array_push( $page_faqs, $faq );
			}
		}
	}	

	if( !empty($page_faqs) && $brand->ID == 18088) { // Smiles in Motion
		shuffle($page_faqs);
	}

	return $page_faqs;
}

function get_providers_for_smile_transformation($smile_transformation_id, $admin_link = false) {
	global $providers;
	$relationship_ids = get_post_meta($smile_transformation_id, 'smile_transformation_provider_relationship', true);
	$provider_names = array();
	foreach($providers->providers as $provider) {
		if( in_array($provider->ID, $relationship_ids)) {
			$provider_names[] = !empty($admin_link) ? '<a href="' . (get_edit_post_link($provider->ID)) . '" class="admin-edit-item">' . ($provider->first_name) . ' ' . ($provider->last_name) . '</a>' : ($provider->first_name) . ' ' . ($provider->last_name);
		}
	}
	return $provider_names;
}

function get_providers_for_pro_affiliation($provider_ids, $admin_link = false) {
	global $providers;
	// $relationship_ids = get_post_meta($provider_ids, 'pro_affiliation_provider_relationship', true);
	// print_stmt($provider_ids);
	$provider_names = '';
	foreach($providers->providers as $provider) {
		if( in_array($provider->ID, $provider_ids)) {
			$provider_names .= !empty($admin_link) ? '<a href="' . (get_edit_post_link($provider->ID)) . '" class="admin-edit-item">' . ($provider->first_name) . ' ' . ($provider->last_name) . '</a><br>' : ($provider->first_name) . ' ' . ($provider->last_name). '<br>';
		}
	}
	return $provider_names;
}

// used for filtering data in the admin Education Association List Table
function get_education_associations( $filters ) {
	global $wpdb;

	$locations_by_region_id = '';
	/**
	 * build query pieces from most unrelated
	 * relationships to the closest relationships
	 */
	if( $filters->edu_association_region_relationship != 'all' ){
		$locations_by_region_id = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'region_location_relationship' AND post_id = $filters->edu_association_region_relationship" );
	}

	if( $filters->edu_association_brand_relationship != 'all' ){

	}

	if( $filters->edu_association_provider_relationship != 'all' ){

	}
}

function get_provider_name_from_id($id) {
	global $providers;

	if( array_key_exists( $id, $providers->providers ) ) { 
		$provider = $providers->providers[$id];
		return $provider->first_name.' '.$provider->last_name;
	}

	return '-';
}

function get_custom_column_info( $column_name, $post_id ) {
	$post_type = get_post_type($post_id);
	$taxonomy = $post_type .'_'.$column_name;
	$terms = get_the_terms($post_id, $taxonomy);
	if (!empty($terms) ) {
		foreach ( $terms as $term )
		$post_terms[] ="<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " .esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
		echo join(',<br>', $post_terms );
	}
	 else echo '<i>No '. $column_name .' set</i>';
}
// ---------- Quick Utility Functions ---------- //

function is_brand() {
	global $brands;

	$brand = false;
	if (property_exists($brands, 'brands') && !empty($brands->brands)) {
		$brand = array_filter($brands->brands, function($brand) {
			$host = "https://$_SERVER[HTTP_HOST]";
			return in_array($host, [$brand->local_url, $brand->dev_url, $brand->admin_url, $brand->production_url]);
		});

		if( !empty( $brand ) ) {
			$brand = reset($brand);		
			$brand->palette = $brands->brand_color_options[$brand->colors];			
		}
	}
	return !empty($brand) ? $brand : false;
}

function is_location($bypass = false) {
	global $wp, $locations;
	if ($wp->request === 'orthodontist-office' && empty($bypass)) return;

	$url_segments = explode('/', $wp->request);
	$page_id = is_archive('location') ? get_page_by_path('orthodontist-office')->ID : get_the_ID();	
	$page_parent = 0;

	if(is_pediatric_provider_page()){
		$page_parents = get_post_meta($page_id, 'provider_location_relationship', true);
	} else {
		$page_parents = [get_post_meta($page_id, 'page_location_parent', true)];
	}

	/**
	 * Dr. Amanda Spitz
	 */
	if($page_id == 18438) {
		if($url_segments[0] == 'pediatric-dental-team' ) return false;

		if($url_segments[0] == 'pediatric-dentist') {
			print_stmt($locations->locations, true);
		}
	}

	if (property_exists($locations, 'locations') && !empty($locations->locations)) {
		foreach ($page_parents as $parent_ID) {
			$location = array_filter($locations->locations, function($location) use ($page_parent, $page_id) {
				return in_array($location->ID, [$page_id, $parent_ID]);
			});	
		}		

		// if (empty($location) && strstr($wp->request, 'orthodontist-office/')) {
		if (empty($location) && strstr($wp->request, 'pediatric-dentist/')) {			
			$location = get_page_by_path($url_segments[1], OBJECT, 'location');
			$location = $locations->locations[$location->ID];
			return $location;
		}
	}

	return !empty($location) ? reset($location) : false;
}

function is_pediatric_provider_page() {
	global $wp;

	$url_segments = explode('/', $wp->request); 
	$provider_url_key = 'pediatric-dental-team';
	$is_provider_page = false;	

	if( (count($url_segments) == 2 && $url_segments[0] == $provider_url_key) || (count($url_segments) == 4 && $url_segments[2] == $provider_url_key)) {
		$is_provider_page = true;
	}

	return $is_provider_page;
}

function brand_host($brand = false) {
	$brand = !empty($brand) ? $brand : is_brand();
	if (!empty($brand)) {
		if (is_local()) {
			// Comment out and send the empty string so it will work on local images - Greggles wuz here
			return $brand->local_url;
			// return '';
		} else if (is_dev()) {
			return $brand->dev_url;
		} else if (is_sim_admin()) {
			return $brand->admin_url;
		} else {
			return $brand->production_url;
		}
	}
}

function brand_url($path, $brand = false) {
	global $wp;
	
	$is404 = is_404();

	$location = is_location($brand);
	$location_path = !empty($location) && ( strstr($path, 'pediatric-dentist-referral') || !strstr($path, 'pediatric-dentist') ) ? '/pediatric-dentist/'.$location->post_name : false;
	$path = strstr($path, '/') ? $location_path.$path : $location_path.'/'.($path).'/';
	$uri = !empty($brand) ? brand_host($brand).$path : brand_host().$path;
	return $uri;
}

function _location_based_url($path) {
	global $wp;
	$brand = is_brand();
	$brand_locations = get_locations_for_brand($brand->ID);
	$url_segments = explode('/', $wp->request); 
	$uri = brand_host() . '/' . $path;

	if($url_segments[0] == 'pediatric-dentist') {
		$location = get_page_by_path($url_segments[1], OBJECT, 'location');
		$location_path = !empty($location) ? '/pediatric-dentist/'.$location->post_name : false;
		$path = strstr($path, '/') ? $location_path.'/'.$path : $location_path.'/'.($path).'/';
		$uri = !empty($brand) ? brand_host($brand).$path : brand_host().$path;
	}

	return $uri;
}

function provider_url($path, $provider_relative_url) {	
	return _location_based_url($path);
}

function blog_url($path) {
	return _location_based_url($path);
}

function get_email_addresses_for_form($form, $location_id) {
	global $locations;
	$brand = is_brand();
	$location = $locations->locations[$location_id];
	$form_name = $form === 'consultation-no-virtual-consultation' ? 'consultation' : $form;
	$form_to_address_name = (str_replace('-', '_', $form_name)).'_to_emails';
	$ret = '';
	if (!empty($location)) {
		$form_to_address_count = $location->{$form_to_address_name};
		for ($i = 0; $i < $form_to_address_count; $i++) {
			$ret .= ($i == 0) ? $location->{$form_to_address_name.'_'.($i).'_email'} : ','.$location->{$form_to_address_name.'_'.($i).'_email'};
		}
	} else {
		$form_to_address_count = $brand->{$form_to_address_name};
		for ($i = 0; $i < $form_to_address_count; $i++) {
			$ret .= ($i == 0) ? $brand->{$form_to_address_name.'_'.($i).'_email'} : ','.$brand->{$form_to_address_name.'_'.($i).'_email'};
		}
	}
	return $ret;
}

function get_cc_email_addresses_for_form($form, $location_id) {
	global $locations;
	$brand = is_brand();
	$location = $locations->locations[$location_id];
	$form_name = $form === 'consultation-no-virtual-consultation' ? 'consultation' : $form;
	$form_to_address_name = (str_replace('-', '_', $form_name)).'_cc_emails';
	$ret = '';
	if (!empty($location)) {
		$form_to_address_count = $location->{$form_to_address_name};
		for ($i = 0; $i < $form_to_address_count; $i++) {
			$ret .= ($i == 0) ? $location->{$form_to_address_name.'_'.($i).'_email'} : ','.$location->{$form_to_address_name.'_'.($i).'_email'};
		}
	} else {
		$form_to_address_count = $brand->{$form_to_address_name};
		for ($i = 0; $i < $form_to_address_count; $i++) {
			$ret .= ($i == 0) ? $brand->{$form_to_address_name.'_'.($i).'_email'} : ','.$brand->{$form_to_address_name.'_'.($i).'_email'};
		}
	}
	return $ret;
}

function get_bcc_email_addresses_for_form($form, $location_id) {
	global $locations;
	$brand = is_brand();
	$location = $locations->locations[$location_id];
	$form_name = $form === 'consultation-no-virtual-consultation' ? 'consultation' : $form;
	$form_to_address_name = (str_replace('-', '_', $form_name)).'_bcc_emails';
	$ret = '';
	if (!empty($location)) {
		$form_to_address_count = $location->{$form_to_address_name};
		for ($i = 0; $i < $form_to_address_count; $i++) {
			$ret .= ($i == 0) ? $location->{$form_to_address_name.'_'.($i).'_email'} : ','.$location->{$form_to_address_name.'_'.($i).'_email'};
		}
	} else {
		$form_to_address_count = $brand->{$form_to_address_name};
		for ($i = 0; $i < $form_to_address_count; $i++) {
			$ret .= ($i == 0) ? $brand->{$form_to_address_name.'_'.($i).'_email'} : ','.$brand->{$form_to_address_name.'_'.($i).'_email'};
		}
	}
	return $ret;
}

function sanitize_array_for_output($array) {
	return array_map(function($v) { return stripslashes(trim(strip_tags(htmlentities($v)))); }, $array);
}

function starts_with($s, $prefix){
	return strpos($s, $prefix) === 0;
}

function do_virtual_page($post_obj = [], $wp_query_override = []) {
    global $wp, $wp_query, $post;

    status_header(200);
    add_action('wp_footer', '_wp_admin_bar_init', 10);

    unset($wp_query->query['error']);
    $post_obj = (object)$post_obj;

    $p = (object)wp_parse_args($post_obj, [
        'post_date' => current_time('mysql'),
        'post_date_gmt' => current_time('mysql', 1),
        'post_content' => $post_obj->post_content,
        'post_title' => $post_obj->post_title,
        'post_name' => $post_obj->post_name,
        'post_type' => $post_obj->post_type,
        'ID' => 2e9,
        'ancestors' => [],
        'comment_count' => 0,
        'comment_status' => 'closed',
        'filter' => $post_obj->filter,
        'guid' => $post_obj->guid,
        'menu_order' => $post_obj->menu_order,
        'modified' => current_time('mysql'),
        'modified_gmt' => current_time('mysql', 1),
        'ping_status' => 'closed',
        'pinged' => '',
        'post_author' => 1,
        'post_content_filtered' => '',
        'post_excerpt' => $post_obj->post_excerpt,
        'post_mime_type' => '',
        'post_parent' => $post_obj->post_parent,
        'post_password' => '',
        'post_status' => 'publish',
        'to_ping' => '',
    ]);

    $post = get_post($p->ID);
	
    $wp_query->comment_count = 0;
    $wp_query->current_comment = null;
    $wp_query->current_post = $p->ID;
    $wp_query->found_posts = 1;
    $wp_query->is_404 = false;
    $wp_query->is_archive = false;
    $wp_query->is_category = false;
    $wp_query->is_home = false;
    $wp_query->is_page = !$wp_query->is_archive && get_post_type($p) == 'page';
    $wp_query->is_singular = !$wp_query->is_archive;
    $wp_query->post = $post;
    $wp_query->post_count = 1;
    $wp_query->posts = array($p);
    $wp_query->queried_object = $p;
    $wp_query->queried_object_id = $p->ID;
    $wp_query->query_vars['error'] = '';
	
	do_action('wp');
	
    // Override $wp_query with $wp_query_override values
    if (!empty($wp_query_override)) {
        foreach ($wp_query_override as $property => $value) {
            $wp_query->{$property} = $value;
        }
    }

    // Setup queried object when ID is present
    wp_reset_postdata();
    setup_postdata($post);
}

function list_all_pages($full_obj = false) {
	$brand = is_brand();
	$all_pages = new WP_Query([
		'post_type' => 'page',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => [
			[
				'key' => 'page_brand_relationship',
				'value' => $brand->ID,
				'compare' => 'LIKE'
			],
		]
	]);
	if ($all_pages->have_posts()) {
		$pages = [];
		foreach ($all_pages->posts as $page) {
			$pages[] = $full_obj ? $page : $page->post_name;
		}
	}
	wp_reset_query();
	return $pages;
}

function list_all_blogs($full_obj = false) {
	$brand = is_brand();
	$blog_posts = new WP_Query([
		'post_type' => 'post',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => [
			[
				'key' => 'post_brand_relationship',
				'value' => $brand->ID,
				'compare' => 'LIKE'
			],
		]
	]);
	if ($blog_posts->have_posts()) {
		$posts = [];
		foreach ($blog_posts->posts as $post) {
			$posts[] = $full_obj ? $post : $post->post_name;
		}
	}
	wp_reset_query();
	return $posts;
}

function list_brand_categories() {
	$post_slugs = list_all_blogs(true);
	if (!empty($post_slugs)) {
		$arrays_of_post_categories = [];
		foreach ($post_slugs as $post) {
			$arrays_of_post_categories[] = wp_get_post_categories($post->ID, ['fields' => 'all']);
		}
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
				$brand_categories = [];
				foreach ($all_post_categories as $category) {
					$brand_categories[] = $category;
				}
				return $brand_categories;
			}
		}
	}
}

function print_stmt($stmt, $die = false) {
	echo '<pre>'; print_r($stmt); echo '</pre>';
	if (!empty($die)) die;
}

function dump_stmt($stmt, $die = false) {
	echo '<pre>'; var_dump($stmt); echo '</pre>';
	if (!empty($die)) die;
}

function compile_less() {
	$files = [
		'style.less' => 'style.css',
	];
	$less_directory = get_stylesheet_directory().'/less/';
	$css_directory =  get_stylesheet_directory().'/css/';

	if(!file_exists($css_directory)) mkdir($css_directory, 0755, true);

	require_once  get_stylesheet_directory().'/lib/less/Less.php';
	$parser = new Less_Parser(array(
		'sourceMap'         => true,
		'compress'			=> true,
	));
	$parser->SetImportDirs($less_directory);

	foreach($files as $l => $c) {
		$less = $less_directory.$l;
		$css = $css_directory.$c;
		try {
			$parser->parseFile($less, '');
			$css_content = $parser->getCss();
			file_put_contents($css, $css_content);
		}
		catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	array_map('unlink', glob($css_directory.'cache.*.min.css'));
}

// function register_image_set($prefix, $width = null, $height = null, $crop = false, $density = 2, $height_density = false) {
// 	foreach (range(1, $density) as $i) {
// 		if ($width ?? false && $height ?? false) {

// 		} elseif ($width ?? false) {

// 		}
// 		if ($width) {
// 			add_image_size($prefix.'_'.$i.'x', $width*$i);
// 		}
// 	}
// }

function register_image_set($prefix, $density) {
	foreach(range(1,3) as $i) add_image_size($prefix.'_'.$i.'x', $density*$i);
}

function register_image_set_height($prefix, $density) {
	foreach(range(1,3) as $i) add_image_size($prefix.'_'.$i.'x', 0, $density*$i);
}

function get_relative_permalink($id) {
    return get_relative_url(get_permalink($id));
}

function get_relative_url($url) {
    return trim(wp_make_link_relative($url), '/');
}

function get_relative_pediatric_provider_url($url) {
	// global $wp;
	// return ($wp->request === 'pediatric-dental-team') ? str_replace('orthodontic-team', $wp->request, $url) : $url;
	return strstr($url, 'orthodontic-team') ? str_replace('orthodontic-team', 'pediatric-dental-team', $url) : $url;
} 

function get_relative_pediatric_location_url($url) {
	return strstr($url, 'orthodontist-office') ? str_replace('orthodontist-office', 'pediatric-dentist', $url) : $url;
} 

function serve_404() {
	status_header(404);
	partial('section/main/404.php');
}

function toTouchTone($phone) {
	$phone = str_replace(' ', '', $phone);
    return strtr(preg_replace('/[^a-z0-9 ]/', '', strtolower($phone)), '0123456789abcdefghijklmnopqrstuvwxyz ', '0123456789222333444555666777788899990');
}

function hyphenatePhoneNumber($number) {
	$number = toTouchTone($number);
	$delimiter = '.';
	return substr($number, 0, 3).$delimiter.substr($number, 3, 3).$delimiter.substr($number, 6);
}

function excerptizeCharacters($copy, $maxCharacters = 120, $retainLastWord = true, $more = '&hellip;', $rtrim_punctuation = true) {
    $copy = str_replace([chr(9), chr(10), chr(13)], ' ', $copy);
    do { $copy = str_replace('  ', ' ', $copy, $count); } while($count > 0);
    $copy = strip_tags($copy);
    $copy = trim($copy);
	if(strlen($copy) <= $maxCharacters) return do_shortcode($copy);
	$copy = substr($copy, 0, $maxCharacters);
	if($retainLastWord === true) $copy = substr($copy, 0, strrpos($copy, ' '));
    if($rtrim_punctuation) $copy = rtrim(rtrim($copy, '.'), ',');
	return do_shortcode($copy.$more);
}

function random_int_except($min, $max, $value) { # Ensure random order
	if(($min == $max) || ($min == $value && $max == $value)) return $min;
	$ret = null; while(($ret = random_int($min, $max)) === $value);
	return $ret;
}

function partial($path, $vars = [], $echo = true) {
	foreach($vars as $k => $v) $$k = $v;
	$path = trim(str_replace('.php', '', $path));
	$path = str_replace('.', '/', $path);
	if(!$echo) ob_start();
	$template_directory = get_stylesheet_directory();
	include $template_directory.'/partials/'.$path.'.php';
	if(!$echo) return ob_get_clean();
}

function prepare_image_attributes($attachment_id, $suffix) {
	if(empty($attachment_id)) return null;

	$image = wp_prepare_attachment_for_js($attachment_id);

	$sizes = [];
	foreach(range(1,3) as $size) {
		$image_url = wp_get_attachment_image_src($attachment_id, $suffix.'_'.$size.'x');
		if(!empty($image_url) && !in_array($image_url[0], $sizes)) $sizes[$size.'x'] = $image_url[0];
	}

	$sizes['default'] = current(array_splice($sizes, 0, 1));

	if(empty($sizes['default'])) $sizes = null;

	return $sizes;
}

function get_image_attributes($suffix, $image, array $classes = []) {
	$attributes = [];
	$srcset = [];
	$image = array_merge(['1x' => $image['default']], $image);
	foreach($image as $size => $url) if($size != 'default') $srcset[$size] = $url;

	$attributes = [
		'src' => $image['default'],
		'srcset' => $srcset,
	];

	$attributes = array_filter($attributes);

	$parts = [];

	if(isset($attributes['src'])) {
		$parts[] = 'src="'.esc_attr($attributes['src']).'"';
	}
	if(isset($attributes['srcset'])) {
		$temp = [];
		foreach($attributes['srcset'] as $size => $image) $temp[] = esc_attr($image.' '.$size);
		$parts[] = 'srcset="'.implode(', ', $temp).'"';
		if(!empty($classes)) $parts[] = 'class="'.esc_attr(implode(' ', array_unique($classes))).'"';
	}

	return implode(' ', $parts);
}

function str_replace_first($string, $replacement, $subject) {
	if(($position = strpos($subject, $string)) !== false) return substr_replace($subject, $replacement, $position, strlen($string));
	return $subject;
}

function str_replace_last($string, $replacement, $subject) {
	if(($position = strrpos($subject, $string)) !== false) return substr_replace($subject, $replacement, $position, strlen($string));
	return $subject;
}

function susort(array &$array, $value_compare_func) {
	$index = 0;
	foreach ($array as &$item) {
		$item = array($index++, $item);
	}
	$result = usort($array, function($a, $b) use($value_compare_func) {
		$result = call_user_func($value_compare_func, $a[1], $b[1]);
		return $result == 0 ? $a[0] - $b[0] : $result;
	});
	foreach ($array as &$item) {
		$item = $item[1];
	}
	return $result;
}

/**
 * Builds a responsive HTML <img> tag based on the image sizes passed or defined by active themes and plugins
 *
 * @param int|array		$attachment_id 	Attachment ID
 * @param array 		$attributes 	Associative array of attributes for the <img> tag
 * @param array 		$image_sizes 	Associative array of image size names defined by active themes and plugins
 *
 * @return string 		HTML <img> tag
 */
function responsive_img($attachment_id, $image_sizes = [], $attributes = [], $pixel_density = false, $include_full_size = true) {
	// Defaults
	$image = $sizes = $widths = $image_srcset_sizes = [];
	$attributes = array_filter(wp_parse_args($attributes, [
		'src' => str_replace('http://', 'https://', $include_full_size ? wp_get_attachment_image_src($attachment_id, 'full')[0] : wp_get_attachment_image_src($attachment_id, $image_sizes[count($image_sizes) - 1])[0]),
		'sizes' => '100vw',
		'class' => [],
		'style' => [],
		'alt' => esc_attr(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)),
	]));
	if(!empty($attributes['default_alt'])) {
		if(empty($attributes['alt'])) $attributes['alt'] = esc_attr($attributes['default_alt']);
		unset($attributes['default_alt']);
	}
	$full_size_path = get_attached_file($attachment_id);
	$original_dimensions = @getimagesize($full_size_path);

	// Build associative array of image widths based on sizes defined by active themes and plugins
	if($pixel_density && !empty($image_sizes)) {
		foreach($image_sizes as $key => $size) {
			$widths[(absint($key)+1).'x'] = $size;
		}
	} else {
		global $_wp_additional_image_sizes;
		array_multisort(array_map(function($element) {
			return $element['width'];
		}, $_wp_additional_image_sizes), SORT_DESC, $_wp_additional_image_sizes);
		if(!empty($image_sizes)) {
			// Use image sizes passed
			foreach($image_sizes as $image_size) {
				if(in_array($image_size, array_keys($_wp_additional_image_sizes))) {
					if($original_dimensions[0] >= $_wp_additional_image_sizes[$image_size]['width']) {
						$widths[$_wp_additional_image_sizes[$image_size]['width'].'w'] = $image_size;
					}
				}
			}
		} else {
			// Use image sizes defined by active themes and plugins
			foreach(array_reverse($_wp_additional_image_sizes) as $size_name => $size) {
				if(!in_array($size['width'], $widths) && $original_dimensions[0] >= $size['width'] && empty($size['crop'])) {
					$widths[$size['width'].'w'] = $size_name;
				}
			}
		}
		// Add original size image
		if($include_full_size) $widths[(absint(str_replace('w', '', end(array_keys($widths)))) + 400).'w'] = 'full';
	}

	// Build associative array of srcset widths and paths
	if(!is_array($attachment_id)) {
		// Single attachment ID
		foreach($widths as $width => $size_name) {
			$image[$width] = wp_get_attachment_image_src($attachment_id, $size_name, false)[0];
		}
	} else {
		// Array of attachment IDs
		foreach($attachment_id as $id) {
			$image = [];
			foreach($widths as $width => $size_name) {
				$image[$width] = wp_get_attachment_image_src($id, $size_name, false)[0];
			}
		}
	}

	// Build <img> HTML
	$html = '<img';
	if(!in_array('srcset', array_keys($attributes))) {
		// Dynamically build srcset attribute from image sizes defined by active theme and plugins when not passed in attributes
		$keys = array_keys($image);
		$lastkey = end($keys);
		if(!empty($image)) {
			$html .= ' srcset="';
			foreach($image as $width => $src) {
				$html .= esc_attr($src.' '.$width.($width != $lastkey ? ', ' : ''));
			}
			$html .= '"';
		}
		foreach($image_srcset_sizes as $size => $image) {
			$srcset .= $image.$size.($size != $lastkey ? ', ' : '');
		}
		if(!empty($srcset)) $html .= ' srcset="'.$srcset.'"';
	}
	if(!empty($attributes)) {
		foreach($attributes as $attribute => $value) {
			switch($attribute) {
				case 'srcset':
					if(is_array($value)) {
						$html .= 'srcset="';
						$keys = array_keys($value);
						$lastkey = end($keys);
						foreach($value as $width => $src) {
							$html .= $src.' '.$width.($width != $lastkey ? ', ' : '');
						}
						$html .= '"';
					} else {
						$html .= ' srcset="'.esc_attr($value).'"';
					}
					break;
				case 'style':
					if(is_array($value)) {
						$html .= ' style="';
						$lastkey = array_keys($value)[count($value) - 1];
						foreach($value as $key => $val) {
							$html .= esc_attr($key.':'.$val).';';
						}
						$html .= '"';
					}
					break;
				default:
					if(is_array($value)) {
						$html .= ' '.$attribute.'="';
						$lastkey = array_keys($value)[count($value) - 1];
						foreach($value as $key => $val) {
							$html .= esc_attr(is_array($val) ? implode(' ', $val) : $val).($key != $lastkey ? ' ' : '');
						}
						$html .= '"';
					} else {
						$html .= ' '.$attribute.'="'.esc_attr($value).'"';
					}
					break;
			}
		}
	}
	foreach($attributes as $attribute => $value) {
		$html .= ' '.$attribute.'="'.esc_attr((is_array($value) ? implode(';', $value) : $value)).'"';
	}
	$html .= '>';
	return $html;
}

/**
 * Builds a responsive HTML <img> tag based on the image sizes passed
 *
 * @param array 		$attributes 	Associative array of attributes for the <img> tag
 *
 * @return string 		HTML <img> tag
 */
function responsive_static_img($attributes = []) {
	// Defaults
	$image = $sizes = $widths = $image_sizes = [];
	$attributes = array_merge([
		'src' => $attributes['src'] ?? '',
		'sizes' => $attributes['sizes'] ?? '100vw',
		'srcset' => $attributes['srcset'] ?? ['1x' =>  $attributes['src']],
	], $attributes);
	$attributes = array_filter($attributes);

	// Build <img> HTML
	$html = false;
	if (!empty($attributes)) {
		// Start tag
		$html = '<img';
		if (is_array($attributes)) {
			foreach ($attributes as $attribute => $value) {
				// Start attribute
				$html .= ' '.$attribute.'="';
				switch($attribute) {
					// Parse attributes
					case 'style':
						$html .= is_array($value) ? implode(';', $value) : $value;
						break;
					case 'srcset':
						if (is_array($value)) {
							$lastkey = array_keys($value)[count($value) - 1];
							foreach ($value as $width => $path) {
								$html .= $path.' '.$width.($width == $lastkey ? '' : ', ');
							}
						} else {
							$html .= $value;
						}
						break;
					default:
						$html .= is_array($value) ? implode(' ', $value) : $value;
						break;
				}
				// End attribute
				$html .= '"';
			}
		}
		// End tag
		$html .= '>';
	}
	return $html;
}

function getH1($content) {
	$output = preg_match_all('/<h1.*?>(.*)<\/h1>/msi', $content, $matches);
	return empty($matches) || empty($matches[1]) ? '' : $matches[1][0];
}

/**
 * Pass an array of posts (or anything really) and return a specific page of the result set passed
 * @param  array       $results          Array of objects/arrays containing the data you want to paginate
 * @param  int|integer $page_number      Which page of results to return
 * @param  int|integer $per_page         How many results per page
 * @param  int|integer $offset           Offset the starting index by this number
 * @return array|array|object            A paginated array of results, or a single result when 1 item is being returned
 */
function page_of($results, $page_number = 1, $per_page = 5, $offset = 0) {
	if (is_null($results)) return false;
	if (count($results) <= $per_page) return $results;
	if ($page_number === 0) $page_number = 1;
	$total_pages = floor(count($results) / $per_page);
	$start_index = ($page_number - 1) * $per_page + $offset;
	// ($page_number > 1 ? $page_number : 0);
	if ($offset) $start_index += $offset;
	// if ($offset) { var_dump($start_index); die; }
	$end_index = $start_index + $per_page;
	if ($end_index > count($results) - 1) $end_index = count($results) - 1;
	$p = array_slice($results, $start_index, $end_index);
	// print_stmt("array_slice($results, $start_index, $end_index)");
	return $p;
}

function get_patient_forms($id = false) {
	global $wpdb;
    if(!$id) return;
	$patient_forms = current($wpdb->get_results($wpdb->prepare("
	SELECT
		newPatientForms, newPatientFormsAAQQuestionnaire, existingPatientAAOInformedConsent, existingPatientFormsAAQQuestionnaire, virtualConsultation, facebookLink
    FROM EntityPatientForms
    WHERE entityID = %d", $id)));
    return empty($patient_forms) ? false : $patient_forms;
}

function ab_variant() {
	if (!empty($_GET['variant']) && in_array(strtoupper($_GET['variant']), ['A', 'B'])) {
		return $_SESSION['variant'] = strtoupper($_GET['variant']);
	}
	return $_SESSION['variant'] = $_SESSION['variant'] ?? (mt_rand() / mt_getrandmax() > .5 ? 'A' : 'B');
}

function orderProviders(array $array, array $order) {
    $sorted = [];
    foreach($array as $value) {
        $id = $value->ID;
        $index = array_search($id, $order);
        $sorted[$index] = $value;
    }
    ksort($sorted);
    return $sorted;
}

function do_location_page($loc = NULL, $pro = NULL) {
	global $providers, $reviews, $smile_transformations;
	$loc ? $location = $loc : $location = is_location();
	$brand = is_brand();

	//get providers here
	if($pro) {
		$all_providers = $pro;
		$carousel_pagination_classes = NULL;
	} else {
		$location_relationships = property_exists($location, 'section_three_providers_relationship') && !empty($location->section_three_providers_relationship) ? unserialize($location->section_three_providers_relationship) : false;
		$all_providers = $providers->searchProviders($location_relationships);
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
	}
	
	/*
						OLD ID 		NEW ID
	Eau Claire 			- 6		| 	1253
	Black River Falls 	- 7		| 	1247
	Bloomer 			- 8		|	1249
	Chippewa Falls 		- 9		|	1251
	Menomonie 			- 10	| 	1257
	Rice Lake 			- 12	| 	1265
	Stanley 			- 13 	| 	1269
	Wausau 				- 14	|	1271
	Merrill 			- 15	| 	1259
	Marinette 			- 16 	| 	1255
	New Richmond 		- 17	| 	1263
	Amery 				- 18	|	1243
	Baldwin 			- 19	| 	1245
	River Falls 		- 20 	| 	1267
	*/

	$hero_position = $location->section_one_hero_position ? 'right-side' : 'left-side'; // Default return 0 or left
	$hero_text_color = $location->section_one_heading_color; // Default return 'primary'
	$container_color_classes = $hero_text_color === 'primary' ? 'bg-gray' : 'bg-primary';

	get_header();
	$brand = is_brand();
	$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

	partial('section.wrapper', [
		'classes' => ['about-our-practice-hero'],
		'partials' => [
			[
				'name' => 'section.hero.standard',
				'parts' => [
					'image' => [
						'src' => wp_get_attachment_image_src($location->section_one_desktop_hero, 'full')[0],
						'classes' => []
					],
					'classes' => ['brand', sanitize_title($brand->post_title), 'bg-green'],
					'container_classes' => [],
					'wrapper_classes' => [],
					'h1' => $location->section_one_heading,
					'h1_classes' => [],
					'content' => $location->section_one_content,
					'cta' => $location->section_one_cta
				]
			],
			[
				'name' => 'section.pediatric.bubbles',
				'parts' => [
					'classes' => ['bottom', 'var-1', 'green']
				]
			]
		],

	]);

	$icons = [
		[
			'img' => get_post_meta($brand->ID,'three_icons_slides_0_three_icons_slides_0_icon', true),
			'heading' => get_post_meta($brand->ID,'three_icons_slides_0_three_icons_slides_0_heading', true),
			'copy' => get_post_meta($brand->ID,'three_icons_slides_0_three_icons_slides_0_copy', true),
			'bg_color' => (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'orange' : 'red') 
		],
		[
			'img' => get_post_meta($brand->ID,'three_icons_slides_1_three_icons_slides_1_icon', true),
			'heading' => get_post_meta($brand->ID,'three_icons_slides_1_three_icons_slides_1_heading', true),
			'copy' => get_post_meta($brand->ID,'three_icons_slides_1_three_icons_slides_1_copy', true),
			'bg_color' => (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue') 
		],
		[
			'img' => get_post_meta($brand->ID,'three_icons_slides_2_three_icons_slides_2_icon', true),
			'heading' => get_post_meta($brand->ID,'three_icons_slides_2_three_icons_slides_2_heading', true),
			'copy' => get_post_meta($brand->ID,'three_icons_slides_2_three_icons_slides_2_copy', true),
			'bg_color' => (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'brown' : 'orange') 
		],
	];

	partial('section.pediatric.three-icons', [
		'static_icons' => true,
		'icons' => $icons
	]);

	partial('section.providers.carousel', [
		'h2' => $location->section_three_heading,
		'h2_classes' => ['primary', 'h3'],
		'content' => $location->section_three_content,
		'all_providers' => $all_providers,
		'pagination_classes' => $carousel_pagination_classes,
		'hide_pagination' => false,
		'cta_classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'orange' : 'red')]
	]);

	partial('section.maps.location', [
		'classes' => ['single-location-brand', sanitize_title($brand->palette).'-palette'],
		'loc' => $location,
		'heading' => $location->descriptive_heading,
		'content' => $location->descriptive_copy
	]);

	partial('section.pediatric.split-static', [
		'classes' => ['hero', 'reverse', 'single-location'],
		'heading' =>  $location->section_four_heading,
		'heading_classes' => ['orange'],
		'copy' => apply_filters('the_content',  $location->section_four_content),
		'image' => [
			'src' => wp_get_attachment_image_src($location->section_four_desktop_hero, 'full')[0],
			'alt' => get_post_meta($location->section_four_mobile_hero, '_wp_attachment_image_alt', true),
			'classes' => ['bubble']
		],
		'cta' => do_shortcode($location->section_four_cta),

	]);

	$slides_count = get_post_meta(get_the_ID(),'location_age_group_slides_section_location_age_group_slides', true);
	$slides = [];
	if (!empty($slides_count)) {
		for ($i = 0; $i < $slides_count; $i++) {
			$icon = get_post_meta(get_the_ID(), 'location_age_group_slides_section_location_age_group_slides_'. $i . '_location_age_group_slides_image', true);
			$slides[] = [
				'heading' => get_post_meta(get_the_ID(), 'location_age_group_slides_section_location_age_group_slides_' . $i . '_location_age_group_slides_heading', true),
				'content' =>apply_filters('the_content', get_post_meta(get_the_ID(), 'location_age_group_slides_section_location_age_group_slides_' . $i . '_location_age_group_slides_copy', true)),
				'cta' => get_post_meta(get_the_ID(), 'location_age_group_slides_section_location_age_group_slides_'. $i . '_location_age_group_slides_link_text', true),
				'image' => [
					'src' => wp_get_attachment_image_src($icon, 'medium_large')[0],
					'alt' => get_post_meta($icon, '_wp_attachment_image_alt', true),
				]
			];
		}
		partial('section.wrapper', [
			'partials' => [
				[
					'name' => 'section.pediatric.bubbles',
					'parts' => [
						'classes' => ['top', 'var-1', (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue')]
					]
				],
				[
					'name' => 'section.pediatric.variable-slide-up-carousel',
					'parts' => [
						'classes' => ['service', (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-green' : 'bg-blue')],
						'carousel_classes' => ['slides-3', 'kill-carousel'],
						'heading' => get_post_meta(get_the_ID(), 'location_age_group_slides_section_location_age_group_section_heading', true),
						'slides' => $slides,
					]
				],
				[
					'name' => 'section.pediatric.bubbles',
					'parts' => [
						'classes' => ['bottom', 'var-2', (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue')]
					]
				],

			]
		]);
	}

	partial('section.pediatric.image-bubble-content', [
		'classes' => ['hero', sanitize_title($brand->palette).'-palette'],
		'heading' => $location->appointment_section_heading,
		'image' => [
			'src' => wp_get_attachment_image_src($location->appointment_section_image, 'full')[0],
			'alt' => get_post_meta($location->appointment_section_image, '_wp_attachment_image_alt', true),
			'classes' => ['bubble']
		],
		'cta' => do_shortcode($location->appointment_section_cta),
	]);

	$testimonials = array_filter($reviews->reviews, function($review) use ($brand) {
		$relationships = unserialize($review->relationships);
		return is_array($relationships) ? in_array($brand->ID, $relationships) : $brand->ID == $relationships;
	});

	if($brand->ID == 18088){ // Smiles in Motion
		 shuffle($testimonials);
	} else {
		usort($testimonials, function($a, $b) {
			return $a->menu_order <=> $b->menu_order;
		});
	}
	partial('section.testimonials.carousel', [
		'classes' => [sanitize_title($brand->post_title)],
		'htag' => 'h2',
		'heading_classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'red')],
		'heading' => 'Our patients tell us what brings a smile to their&nbsp;faces',
		'reviews' => $testimonials,
	]);

	$sec_8_heading = $location->homepage_bottom_slider_heading;
	$sec_8_copy = apply_filters('the_content', $location->homepage_bottom_slider_copy);
	$sec_8_slides = array();
	$sec_8_slide_count = $location->homepage_bottom_slider_slides;
	if ($sec_8_slide_count > 0) {
		for ($z = 0; $z < $sec_8_slide_count; $z++) {
			$img_id = 'homepage_bottom_slider_slides_' . ($z) . '_image';
			$attachment_id = $location->$img_id;
			$attachment = wp_get_attachment_image_src($attachment_id, 'medium_large');
			$sec_8_slides[] = [
				'src' => $attachment[0],
				'width' => $attachment[1],
				'height' => $attachment[2],
				'alt' => !empty(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) ? str_replace('_', ' ', get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) : str_replace('_', ' ', get_the_title($attachment_id)),
			];
		}
	}
	partial('section.wrapper', [
		'classes' => ['treatments'],
		'partials' => [
			[
				'name' => 'section.pediatric.bubbles',
				'parts' => [
					'classes' => ['top', 'var-3', 'green']
				]
			],
			[
				'name' => 'section.slider.slider',
				'parts' => [
					'classes' => ['bg-green'],
					'htag' => 'h3',
					'heading_classes' => ['white'],
					'heading' => $sec_8_heading,
					'static_copy' => $sec_8_copy,
					'slides' => $sec_8_slides,
					'pagination' => 0,
				]
			],
		]
	]);

	get_footer();
}

function sitemap_exclusions() {
	return [
		'site-map',
		'schedule-orthodontic-consultation',
		'schedule-free-consultation-cwi',
		'schedule-free-consultation-ewi',
		'schedule-free-consultation-scv',
		'schedule-your-free-consultation-scv',
		'free-consultation-minnesota',
		'free-consultation-wisconsin',
		'nondiscrimination',
		'privacy-policy',
		'share-feedback',
		'smilegallery',
		'free-custom-mouthguard',
		'free-custom-mouthguard-cwi',
	];
}

function kristo_roostergrin_flag() {
	$brand = is_brand();
	return ( $brand->ID === 1217 && isset($_GET['schedule_consultation']) ) ? true : false;
}

function gr_roostergrin_flag() {
	$location = is_location();
	if(!empty($location)) {
		return  in_array(get_the_ID(), [4460, 4465]) || ( $location->ID === 3393 || $location->ID === 3396 ) ? true : false;
	}
}
