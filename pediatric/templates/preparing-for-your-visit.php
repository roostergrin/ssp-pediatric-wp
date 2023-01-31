<?
# Template Name: Preparing For Your Visit
global $providers, $reviews;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();

$hero_images = array();
for($i = 1; $i < 4; $i++) {
    $hero_images[] = [
        'src' => wp_get_attachment_image_src(get_post_meta(get_the_id(), 'preparing_for_your_visit_hero_image_' . $i, true), 'medium_large')[0],
        'alt' => get_post_meta(get_post_meta(get_the_id(), 'preparing_for_your_visit_hero_image_' . $i, true), '_wp_attachment_image_alt', true),
        'classes' => ['bubble']
    ];
}
$link_text_1 = get_post_meta(get_the_id(), 'preparing_for_your_visit_link_text_1', true);
$link_text_2 = get_post_meta(get_the_id(), 'preparing_for_your_visit_link_text_2', true);
$link_text_3 = get_post_meta(get_the_id(), 'preparing_for_your_visit_link_text_3', true);
partial('section.wrapper', [
    'classes' => ['prepare-for-your-visit-hero'],
    'partials' => [
        [        
            'name' => 'section.hero.three-bubble-hero',
            'parts' => [
                'classes' => ['bg-green', $brand->post_name],
                'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_hero_heading', true),
                'copy' => get_post_meta(get_the_id(), 'preparing_for_your_visit_hero_subheading', true),
                'link_text_1' => $link_text_1,
                'link_text_2' => $link_text_2,
                'link_text_3' => $link_text_3,
                'images' => $hero_images,
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-4', 'green']
            ]
        ],
        [
           'name' => 'section.pediatric.split-static',
           'parts' =>  [
               'id' => 'section-1',
                'classes' => ['big-img-left', 'lg-top-overlap', 'after-hero'],
                'heading' => $link_text_1,
                'heading_classes' => ['h1', 'mb-15'],
                'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'preparing_for_your_visit_section_2_copy', true)),
                'image' => [                    
                    'src' => wp_get_attachment_image_src(get_post_meta(get_the_id(), 'preparing_for_your_visit_section_2_image', true), 'medium_large')[0],
                    'alt' => get_post_meta(get_post_meta(get_the_id(), 'preparing_for_your_visit_section_2_image', true),'_wp_attachment_image_alt', true),
                    'classes' => ['bubble', 'animatable'],
                ]
            ]
        ]
    ],
]);

$blue_boxes_1 = array();
$sec_2_box_bg_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-gray' : ''); 
for($j = 1; $j < 4; $j++) {
    $blue_boxes_1[] = [
        'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_3_box_' . $j . '_heading', true),
        'content' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_3_box_' . $j . '_copy', true), 
    ];
}
partial('section.pediatric.three-box-slide-in', [
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_3_heading', true),
    'boxes' => $blue_boxes_1,
    'box_classes' => [$sec_2_box_bg_color]
]);

$sec_3_section_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'red'); 
$six_icons_1 = array();
for($m = 0; $m < 6; $m++) {
    $icon = get_post_meta(get_the_id(), 'preparing_for_your_visit_section_4_icons_' . $m . '_preparing_for_your_visit_section_4_icons_' . $m . '_icon', true);
    $six_icons_1[] =    [
        'img' => $icon,
        'copy' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_4_icons_' . $m . '_preparing_for_your_visit_section_4_icons_' . $m . '_copy', true),
    ];
}
partial('section.pediatric.six-icons', [
    'classes' => ['bg-white', $sec_3_section_color, 'mt'],
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_4_heading', true),
    'heading_classes' => [$sec_3_section_color],
    'icons' => $six_icons_1
]);

// Toddlers follows
partial('section.wrapper', [
    'classes' => ['toddlers-and-preschoolers'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['top', 'var-1', 'gray', 'reverse', 'absolute']
            ]
        ],
        [
            'name' => 'section.pediatric.split-static',
            'parts' => [
                'id' => 'section-2',
                'classes' => ['reverse', 'bg-gray', 'big-img-left', 'mt', 'toddlers-and-preschoolers-custom'],
                'heading' => $link_text_2,
                'heading_classes' => ['h1', 'mb-15'],
                'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'preparing_for_your_visit_section_5_copy', true)),
                'image' => [                    
                    'src' => wp_get_attachment_image_src(get_post_meta(get_the_id(), 'preparing_for_your_visit_section_5_image', true), 'medium_large')[0],
                    'alt' => get_post_meta(get_post_meta(get_the_id(), 'preparing_for_your_visit_section_5_image', true),'_wp_attachment_image_alt', true),
                    'classes' => ['bubble', 'animatable']
                ]
            ]
        ]
    ]
]);

$sec_5_box_bg_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-gray' : ''); 
$blue_boxes_2 = array();
for($k = 1; $k < 4; $k++) {
    $blue_boxes_2[] = [
        'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_6_box_' . $k . '_heading', true),
        'content' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_6_box_' . $k . '_copy', true), 
    ];
}
partial('section.pediatric.three-box-slide-in', [
    'classes' => ['bg-gray'],
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_6_heading', true),
    'boxes' => $blue_boxes_2,
    'box_classes' => [$sec_5_box_bg_color]
]);

$sec_6_img_bg_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'green'); 
$six_icons_2 = array();
for($n = 0; $n < 6; $n++) {
    $icon = get_post_meta(get_the_id(), 'preparing_for_your_visit_section_7_icons_' . $n . '_preparing_for_your_visit_section_7_icons_' . $n . '_icon', true);
    $six_icons_2[] =    [
        'img' => $icon,
        'copy' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_7_icons_' . $n . '_preparing_for_your_visit_section_7_icons_' . $n . '_copy', true),
    ];
}
partial('section.pediatric.six-icons', [
    'classes' => ['bg-gray', $sec_6_img_bg_color, 'guidance-2'],
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_7_heading', true),
    'heading_classes' => [],
    'icons' => $six_icons_2
]);

// Elementary + young adults follows
partial('section.pediatric.bubbles', [
    'classes' => ['bottom', 'var-2', 'gray', 'reverse', 'pre-elementary']
]);
partial('section.pediatric.split-static', [
    'id' => 'section-3',
    'classes' => ['elementary'],
    'heading' => $link_text_3,
    'heading_classes' => ['h1', 'mb-15'],
    'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'preparing_for_your_visit_section_8_copy', true)),
    'image' => [                    
        'src' => wp_get_attachment_image_src(get_post_meta(get_the_id(), 'preparing_for_your_visit_section_8_image', true), 'medium_large')[0],
        'alt' => get_post_meta(get_post_meta(get_the_id(), 'preparing_for_your_visit_section_8_image', true),'_wp_attachment_image_alt', true),
        'classes' => ['bubble', 'animatable']
    ]
]);

$sec_9_box_bg_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-gray' : ''); 
$blue_boxes_3 = array();
for($l = 1; $l < 4; $l++) {
    $blue_boxes_3[] = [
        'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_9_box_' . $l . '_heading', true),
        'content' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_9_box_' . $l . '_copy', true), 
    ];
}
partial('section.pediatric.three-box-slide-in', [
    'classes' => ['bg-white'],
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_9_heading', true),
    'boxes' => $blue_boxes_3,
    'box_classes' => [$sec_9_box_bg_color]
]);


/**
 * SECTION 10 - Four Icons w/ Image
 */
$sec_10_icons = array();
$sec_10_heading_color = '';
$sec_10_main_color = 'orange';

if(sanitize_title($brand->palette) == 'smiles-in-motion') {
    $sec_10_heading_color = 'green';
    $sec_10_main_color = 'gray';
}

$section_10_image_id = get_post_meta(get_the_id(), 'preparing_for_your_visit_section_10_image', true);
for($o = 0; $o < 4; $o++) {
    $icon = get_post_meta(get_the_id(), 'preparing_for_your_visit_section_10_icons_' . $o . '_preparing_for_your_visit_section_10_icons_' . $o . '_icon', true);
    $sec_10_icons[] =  [
        'img' =>  $icon,
        'copy' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_10_icons_' . $o . '_preparing_for_your_visit_section_10_icons_' . $o . '_copy', true),
    ];
}
partial('section.pediatric.four-icons-with-image', [
    'classes' => [$sec_10_main_color, 'mob-mb-neg-230'],
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_10_heading', true),
    'heading_classes' => [$sec_10_heading_color],
    'image' => [
        'src' => wp_get_attachment_image_src($section_10_image_id, 'medium_large')[0],
        'alt' => get_post_meta($section_10_image_id, '_wp_attachment_image_alt', true),
        'classes' => ['animatable'],
    ],
    'icons' => $sec_10_icons,
]);


/**
 * SECTION 11 - Variable Slide-Up Carousel
 */
$slides = [];
for($p = 1; $p < 4; $p++) {
    $img_id = get_post_meta(get_the_id(), 'preparing_for_your_visit_section_11_box_' . $p . '_image', true);
    $slides[] =     [
        'image' => [
            'src' => wp_get_attachment_image_src($img_id, 'full')[0],
            'alt' => get_post_meta($img_id, '_wp_attachment_image_alt', true),
        ],
        'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_11_box_' . $p . '_heading', true),
        'content' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_11_box_' . $p . '_copy', true),
    ];
}
partial('section.wrapper', [
    'classes' => ['expectations-section'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['top', 'var-1', 'green']
            ]
        ],
        [
            'name' => 'section.pediatric.variable-slide-up-carousel',
            'parts' =>[
                'carousel_classes' => ['slides-3'],
                'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_11_heading', true),
                'heading_classes' => ['h1', 'mb-20'],
                'content' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_11_copy', true),
                'slides' => $slides,                
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-2', 'green', 'pre-about-southmoor-section']
            ]
        ],
    ]
]);


/**
 * SECTION 12 - Split Static
 */
$sec_12_img_id = get_post_meta(get_the_id(), 'preparing_for_your_visit_section_12_image', true);
partial('section.pediatric.split-static', [
    'classes' => ['reverse', 'v-centered-text', 'about-southmoor'],
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_12_heading', true),
    'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'preparing_for_your_visit_section_12_copy', true)),
    'cta' => do_shortcode(get_post_meta(get_the_id(), 'preparing_for_your_visit_section_12_cta', true)),
    'image' => [
        'src' => wp_get_attachment_image_src($sec_12_img_id, 'full')[0],
        'alt' => get_post_meta($sec_12_img_id, '_wp_attachment_image_alt', true),
        'classes' => ['bubble', 'animatable']
    ]
]);


/**
 * SECTION 13 - Centered Heading CTA
 */
$sec_13_btn_text = "Download patient forms";
$sec_13_heading_color = 'red';
$sec_13_cta_color = 'red';

if(sanitize_title($brand->palette) == 'smiles-in-motion') { 
    $sec_13_btn_text = "New patient forms"; 
    $sec_13_heading_color = 'gray';
    $sec_13_cta_color = 'orange';
}

partial('section.pediatric.centered-heading-cta', [
    'classes' => ['gray'],
    'heading' => get_post_meta(get_the_id(), 'preparing_for_your_visit_section_13_heading', true),
    'heading_classes' => [$sec_13_heading_color],
    'cta' => do_shortcode('[forms_link class="cta '.$sec_13_cta_color.'" text="'.$sec_13_btn_text.'" title="'.$sec_13_btn_text.'"]'),
]);


/**
 * SECTION 14 - Testimonials Carousel
 */
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
$sec_14_heading_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'red');
partial('section.testimonials.carousel', [
    'classes' => [sanitize_title($brand->post_title), 'reverse', 'mb'],
    'htag' => 'h2',
    'heading_classes' => [$sec_14_heading_color],
    'heading' => 'Our&nbsp;patients (and&nbsp;their&nbsp;parents) say&nbsp;it&nbsp;best',
    'reviews' => $testimonials,
]);
	
get_footer();
