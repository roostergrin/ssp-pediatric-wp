<?php
# Template Name: Careers

$brand = is_brand();

// Tri Carousel - Patient Carousel
add_filter('body_class', function($class) {
	$class[] = 'page-template-careers';
	return $class;
});
$tri_carousel_slides = [];
if( have_rows('careers_slide_repeater') ):
   while ( have_rows('careers_slide_repeater') ) : the_row();
   $i = get_sub_field('careers_slide_repeater_image');
   if($i == 821) continue;
   $attachment = wp_get_attachment_image_src($i, 'medium_large');
   
   array_push($tri_carousel_slides, [
		'src' => $attachment[0],
		'width' => $attachment[1],
		'height' => $attachment[2],
		'alt' => !empty(get_post_meta($i, '_wp_attachment_image_alt', true)) ? get_post_meta($i, '_wp_attachment_image_alt', true) : str_replace('_', ' ', get_the_title($i)),
		'classes' => [get_the_title($i)]
	]);

   endwhile;

else :
	$starting_id = 806;
	$ending_id = 862;
	for($i = $starting_id; $i < ($ending_id + 1); $i++) {
		if($i == 821) continue;
		$attachment = wp_get_attachment_image_src($i, 'medium_large');
		array_push($tri_carousel_slides, [
			'src' => $attachment[0],
			'width' => $attachment[1],
			'height' => $attachment[2],
			'alt' => !empty(get_post_meta($i, '_wp_attachment_image_alt', true)) ? get_post_meta($i, '_wp_attachment_image_alt', true) : str_replace('_', ' ', get_the_title($i)),
			'classes' => [get_the_title($i)]
		]);
	}
endif;
shuffle($tri_carousel_slides);

//left tab
$careers_hero_heading = get_post_meta(get_the_id(), 'careers_hero_heading', true);
$careers_hero_content = get_post_meta(get_the_id(), 'careers_hero_content', true);
$careers_hero_cta = get_post_meta(get_the_id(), 'careers_hero_cta', true);

//right tab
$careers_hero_heading_right = get_post_meta(get_the_id(), 'careers_hero_heading_right', true);
$careers_hero_subheading_right = get_post_meta(get_the_id(), 'careers_hero_subheading_right', true);


$careers_hero_email = get_post_meta(get_the_id(), 'careers_hero_email', true);
$careers_hero_email_text = get_post_meta(get_the_id(), 'careers_hero_email_text', true);

get_header();

// conditional build out column 1 content based on color palette
$copy_side_by_side_column1 = "<h1>" . $careers_hero_heading . "</h1>";
$copy_side_by_side_column1 .= "<p class=\"subTitle\">" . $careers_hero_content . "</p>";
if(sanitize_title($brand->palette) != 'smiles-in-motion') {
	$copy_side_by_side_column1 .= "<p class=\"cta green\">" . do_shortcode($careers_hero_cta) . "</p>";
} else {
	$copy_side_by_side_column1 .= "<p>" . do_shortcode($careers_hero_cta) . "</p>";
}

partial('section.copy.side-by-side-with-box ', [
	'column1' => $copy_side_by_side_column1,
	'column2' => '<p class="bold">'. $careers_hero_heading_right .'</p><p>'. $careers_hero_subheading_right .'</p><p ><a href="'. $careers_hero_email .'" class="cta hover-white-trans
	" target="_blank">'. $careers_hero_email_text .'</a></p>'
]);

// sim
if ($brand->ID === 18088) {
    echo "<section> <div class='content' style='padding: 0 110px; margin-bottom: 14rem;'> <script src='https://workforcenow.adp.com/mascsr/default/mdf/recwebcomponents/recruitment/main-config/recruitment.js'></script><recruitment-current-openings cid='ddbaaed6-ae19-4112-a714-e3ed99d3fed1' ccid='9200431463123_3' host='DP' locale='en_US'></recruitment-current-openings> </div> </section>";
}
// southmoore
else if ($brand->ID === 12374) {
    echo "<section> <div class='content' style='padding: 0 110px; margin-bottom: 14rem;'> <script src='https://workforcenow.adp.com/mascsr/default/mdf/recwebcomponents/recruitment/main-config/recruitment.js'></script><recruitment-current-openings cid='ddbaaed6-ae19-4112-a714-e3ed99d3fed1' ccid='9200431463886_3' host='DP' locale='en_US'></recruitment-current-openings> </div> </section>";
}

partial('section.wrapper', [
	'partials' => [
		[
			'name' => 'section.pediatric.bubbles',
			'parts' => [
				'classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue'), 'top', 'var-1']
			],
		],
		
		[
			'name' => 'section.tri-carousel',
			'parts' => [
				'classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-green' : 'bg-blue'), sanitize_title($brand->palette).'-palette'],
				'images' => $tri_carousel_slides
			],
		]		
	]
]);

get_footer();
