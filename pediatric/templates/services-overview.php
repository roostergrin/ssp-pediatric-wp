<?php
# Template Name: Services overview

global $reviews;
$brand = is_brand();
get_header();

$hero_img_id =  get_post_meta(get_the_id(), 'services_overview_hero_image', true);
partial('section.pediatric.split-static', [
    'classes' => ['services-overview-hero'],
    'h1' => get_post_meta(get_the_id(), 'services_overview_hero_heading', true),
    'h1_classes' => ['line-height-1'],
    'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'services_overview_hero_content', true)),
    'cta' => apply_filters('the_content', get_post_meta(get_the_id(), 'services_overview_hero_cta', true)),
    'image' => [
        'src' => wp_get_attachment_image_src($hero_img_id, 'medium_large')[0],
		'alt' => get_post_meta($hero_img_id, '_wp_attachment_image_alt', true),
        'classes' => ['bubble']
    ]
]);


$sec_2_slides = array();
$sec_2_slides_num = 5;
$sec_2_bubble_color = 'blue';
$sec_2_slide_img_bg_color = 'bg-green';

// Smiles in Motion
if ($brand->ID == 18088) {
    $sec_2_slides_num = 6;
    $sec_2_bubble_color = 'green';
    $sec_2_slide_img_bg_color = 'bg-gray';
}

for($i = 0; $i < $sec_2_slides_num; $i++) {
    $sec_2_slides[] = [
        'heading' => get_post_meta(get_the_id(), 'services_overview_section_two_slides_'. $i . '_services_overview_section_two_slides_'. $i . '_heading', true),
        'content' => get_post_meta(get_the_id(), 'services_overview_section_two_slides_'. $i . '_services_overview_section_two_slides_'. $i . '_copy', true),
        'cta' => get_post_meta(get_the_id(), 'services_overview_section_two_slides_'. $i . '_services_overview_section_two_slides_'. $i . '_cta', true),
        'icon' => [
            'classes' => [$sec_2_slide_img_bg_color],
            'icon' => get_post_meta(get_the_id(), 'services_overview_section_two_slides_'. $i . '_services_overview_section_two_slides_'. $i . '_icon', true)
        ]
    ];
}
partial('section.wrapper', [
    'classes' => ['treatments'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['top', 'var-1', $sec_2_bubble_color]
            ]
        ],
        [
            'name' => 'section.pediatric.treatments-carousel',
            'parts' =>[
                'copy_classes' => ['pb-0', 'no-top-image', 'no-bottom-mobile-margin'],
                'heading' => get_post_meta(get_the_id(), 'services_overview_section_two_heading', true),
                'h_tag' => 'h3',
                'content' => get_post_meta(get_the_id(), 'services_overview_section_two_subheading', true),
                'slides' => $sec_2_slides,                
            ]
        ],
    ]
]);


partial('section.wrapper', [
    'classes' => ['bg-'.$sec_2_bubble_color],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['top', 'var-1', 'gray', 'reverse']
            ]
        ],
    ]
]);

$sec_3_slides = array();
$sec3_page_faqs = get_faqs_for_page();

foreach($sec3_page_faqs as $f) {
    $sec_3_slides[] = [
        'heading' => $f->post_title,
        'copy' => apply_filters('the_content',$f->post_content),
        'image' => [
            'src' => $f->image['src'],
            'alt' => $f->image['alt'],
        ]
    ];
}
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
$testimonial_header_color_class = ($brand->ID == 18088 ? 'gray': 'red'); // Smiles in Motion
partial('section.wrapper', [
    'classes' => ['bg-gray'],
    'partials' => [
        [
            'name' => 'section.pediatric.split-carousel',
            'parts' => [
                'classes' => ['faq','bg-gray', 'reverse', 'mt-0', sanitize_title($brand->palette).'-palette'],
                'heading' => 'Questions we commonly hear',
                'heading_classes' => ['h1', 'line-height-1'],
                'slides' => $sec_3_slides,
            ]
        ],
        [
            'name' => 'section.testimonials.carousel',
            'parts' => [
                'classes' => [sanitize_title($brand->post_title), 'reverse', 'bg-gray', 'last-section'],
                'htag' => 'h2',
                'heading_classes' => [$testimonial_header_color_class],
                'heading' => 'Our&nbsp;patients (and&nbsp;their&nbsp;parents) say&nbsp;it&nbsp;best',
                'reviews' => $testimonials,
            ]
        ]
    ]
]);

get_footer();