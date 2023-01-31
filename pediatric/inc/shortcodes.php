<?
add_shortcode('PARTIAL', function($args, $content) {
	$args = wp_parse_args($args, [
		'path' => null,
	]);

	return partial($args['path'], [], false);
});

add_shortcode('NAME', function() {
	global $team;
	$current = $team->getActiveTeamMember();
	if(empty($current)) return '';
	return strip_tags($current->name);
});

add_shortcode('POSITION', function() {
	global $team;
	$current = $team->getActiveTeamMember();
	if(empty($current)) return '';

	return !empty($current->position->name) ? $current->position->name : $current->position;
});

add_shortcode('LOCATIONS', function() {
	global $team;
	$current = $team->getActiveTeamMember();
	if(empty($current) || empty($current->locations)) return '';

	return implode(' | ', array_filter(array_unique(array_map(function($v) { return $v->name; }, $current->locations))));
});

add_shortcode('LOCATION_NAME', function() {
	global $locations;
	$current = $locations->last_location;
	if(empty($current) || empty($current->name)) return '';
	return $current->name;
});

add_shortcode('CANDIDATE_IFRAME', function($atts) {
	$atts = shortcode_atts([
		'src' => null,
		'width' => '100%',
		'height' => 900,
	], $atts, 'CANDIDATE_IFRAME');

	if(empty($atts['src'])) return '';

	ob_start();
	?>
	<div class="iframe candidate-iframe" data-source="<?= esc_url($atts['src']) ?>">
		<iframe src="<?= esc_url($atts['src']) ?>" width="<?= esc_attr($atts['width']) ?>" height="<?= esc_attr($atts['height']) ?>"></iframe>
	</div>
	<?
	return ob_get_clean();
});

add_shortcode('CTA', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'href' => '',
		'target' => '_self',
		'rel' => '',
		'text' => '',
		'navigation' => '0',
	], $atts, 'CTA');

	$classes = array_unique(array_filter(array_merge(['cta'], explode(' ', $atts['class']))));

	if(empty($atts['href'])) return '';

	ob_start();
	?>
	<? if(!empty($atts['navigation'])): ?><div class="navigation"><? endif ?>
	<div class="cta-wrapper"><a <? if(!empty($classes)): ?>class="<?= implode(' ', $classes) ?>"<? endif ?> href="<?= esc_url($atts['href']) ?>" target="<?= esc_attr($atts['target']) ?>" <? if(!empty($atts['rel'])): ?>rel="<?= esc_attr($atts['rel']) ?>"<? endif ?>><?= $atts['text'] ?></a></div>
	<? if(!empty($atts['navigation'])): ?></div><? endif ?>
	<?
	return ob_get_clean();
});

add_shortcode('BRAND_TITLE', function($atts) {
	$brand = is_brand();
	return $brand->post_title;
});

add_shortcode('BRAND_SHORTNAME', function($atts) {
	$brand = is_brand();
	switch($brand->post_title) {
		case 'Kristo Orthodontics':
			return 'Kristo';
			break;
		default:
			return '';
			break;
	}
});

add_shortcode('BRAND_SEO_TITLE', function($atts) {
	$brand = is_brand();
	return do_shortcode($brand->seo_title);
});

add_shortcode('BRAND_SEO_DESCRIPTION', function($atts) {
	$brand = is_brand();
	return $brand->seo_description;
});

add_shortcode('SEO_DESCRIPTION', function($atts) {
	if( is_front_page() ){
		$brand = is_brand();
		return $brand->seo_description;
	} else {
		return get_post_meta(get_the_ID(), '_aioseop_description', true);
		
	}	
});

add_shortcode('BRAND_URL', function($atts) {
	$brand = is_brand();
	$atts = shortcode_atts([
		'class' => '',
		'target' => '',
		'path' => '',
		'text' => '',
		'title' => '',
	], $atts, 'brand_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	$path = !empty($atts['path']) ? $atts['path'] : '/';
	$brand_url = brand_url($path, $brand);
	ob_start();
	?>
	<a href="<?= $brand_url; ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});

//=====================================[PAGE LINKS]=====================================//
add_shortcode('brand_link', function($atts) {
	$brand = is_brand();
	$atts = shortcode_atts([
		'class' => '',
		'target' => ''
	], $atts, 'brand_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_host(); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $brand->post_title; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= brand_host(); ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('locations_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'locations_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_host().'/pediatric-dentist/'; ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
//=====================================[OUR TEAM LINKS]=====================================//
add_shortcode('meet_our_orthodontic_team_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'meet_our_orthodontic_team_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('pediatric-dental-team'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('dr_bradley_smith_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'dr_bradley_smith_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('/pediatric-dental-team/brad-smith-dds/'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('dr_scott_smith_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'dr_scott_smith_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('/pediatric-dental-team/scott-smith-dds/'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('dr_john_rubenstrunk_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'dr_john_rubenstrunk_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('/pediatric-dental-team/john-rubenstrunk-dds/'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
//=====================================[CONTACT LINKS]=====================================//
add_shortcode('appointments_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'appointments_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('kids-dentist-appointment'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('contact_us_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'contact_us_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('contact-us'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('phone_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
	], $atts, 'phone_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	$location = is_location();
	if (!empty($location)) {
		$phone_text = str_replace('%number%', hyphenatePhoneNumber($location->phone), $atts['text']);
		ob_start();
	?>
	<a href="tel:+1<?= toTouchTone($location->phone); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?>><?= $phone_text; ?></a>
	<?
		return trim(ob_get_clean());
	}
});
add_shortcode('after_hours_phone_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
	], $atts, 'after_hours_phone_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	$location = is_location();
	if (!empty($location)) {
		$phone_text = str_replace('%number%', hyphenatePhoneNumber($location->after_hours_phone), $atts['text']);
		ob_start();
	?>
	<a href="tel:+1<?= toTouchTone($location->after_hours_phone); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?>><?= $phone_text; ?></a>
	<?
		return trim(ob_get_clean());
	}
});
add_shortcode('toll_free_phone_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
	], $atts, 'toll_free_phone_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	$location = is_location();
	if (!empty($location)) {
		$phone_text = str_replace('%number%', hyphenatePhoneNumber($location->toll_free_phone), $atts['text']);
		ob_start();
	?>
	<a href="tel:+1<?= toTouchTone($location->toll_free_phone); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?>><?= $phone_text; ?></a>
	<?
		return trim(ob_get_clean());
	}
});
//=====================================[OUR APPROACH]=====================================//
add_shortcode('our_approach_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'our_approach_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('dentist-office-for-kids'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
//=====================================[SERVICES]=====================================//
add_shortcode('all_services_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'all_services_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('pediatric-dental-services'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('dental_exams_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'dental_exams_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('kids-dental-exams'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('dental_sealants_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'dental_sealants_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('kids-fluoride-treatments'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('restorative_treatments_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'restorative_treatments_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('kids-dental-fillings-crowns'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('dental_emergencies_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'dental_emergencies_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('emergency-kids-dentist'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('custom_mouthguard_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'custom_mouthguard_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('kids-custom-mouthguard'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
//=====================================[YOUR VISIT]=====================================//
add_shortcode('preparing_for_your_visit_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => '',
		'anchor' => '',
	], $atts, 'preparing_for_your_visit_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('kids-dental-care-visits'); ?><?= !empty($atts['anchor']) ? $atts['anchor'] : '';?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('payment_options_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'payment_options_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('accepted-payment'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('forms_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'forms_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('patient-forms'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
//=====================================[UTILITY PAGES]=====================================//
add_shortcode('doctor_referral_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'doctor_referral_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('pediatric-dentist-referral'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('careers_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'careers_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('careers'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('privacy_policy_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'privacy_policy_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('privacy-policy'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('non_discrimination_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'non_discrimination_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('nondiscrimination'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('site_map_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'site_map_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('site-map'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('community_involvement_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'community_involvement_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('community-involvement'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});
add_shortcode('blog_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'blog_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('kids-dentist-blog'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});


add_shortcode('frenectomy_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'frenectomy_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('frenectomy'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});



//=====================================[DOES THIS PAGE EXIST ON PEDIATRIC TEMPLATE?]=====================================//
add_shortcode('refer_a_friend_link', function($atts) {
	$atts = shortcode_atts([
		'class' => '',
		'text' => '',
		'title' => '',
		'target' => ''
	], $atts, 'refer_a_friend_link');
	$classes = array_unique(array_filter(explode(' ', $atts['class'])));
	ob_start();
	?>
	<a href="<?= brand_url('refer-a-friend-program'); ?>"<? if(!empty($classes)): ?> class="<?= implode(' ', $classes) ?>"<? endif; ?><? if(!empty($atts['title'])): ?> title="<?= $atts['title']; ?>"<? endif; ?><? if(!empty($atts['target'])): ?> target="<?= $atts['target']; ?>"<? endif; ?>><?= $atts['text'] ?></a>
	<?
	return trim(ob_get_clean());
});


