<?
# Template Name: Service Template 1
global $providers, $reviews, $faqs;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();

$hero_img_id = get_post_meta(get_the_id(), 'service_template_1_hero_image', true);
partial('section.pediatric.split-static', [
    'classes' => ['reverse', 'service-hero'],
    'h1' => get_post_meta(get_the_id(), 'service_template_1_hero_heading', true),
    'h1_classes' => ['line-height-1', 'mb-20'],
    'copy' => apply_filters('the_content',get_post_meta(get_the_id(), 'service_template_1_hero_subheading', true)),
    'image' => [                    
        'src' => wp_get_attachment_image_src($hero_img_id, 'medium_large')[0],
        'alt' => get_post_meta($hero_img_id, '_wp_attachment_image_alt', true),
        'classes' => ['bubble', 'animatable']
    ],
    'cta' => do_shortcode(get_post_meta(get_the_id(), 'service_template_1_hero_cta', true)),
]);

$slides = [];
$counter = 0;
for($i = 0; $i < 4; $i++) {
    $icon = get_post_meta(get_the_id(), 'service_template_1_section_two_slide_' . $i . '_service_template_1_section_two_slide_' . $i . '_icon', true);
    $heading = get_post_meta(get_the_id(), 'service_template_1_section_two_slide_' . $i . '_service_template_1_section_two_slide_' . $i . '_heading', true);
    $copy = get_post_meta(get_the_id(), 'service_template_1_section_two_slide_' . $i . '_service_template_1_section_two_slide_' . $i . '_copy', true);
    if(empty($icon) || empty($heading) || empty($copy) ) {
        break;
    } else {
        $counter++;
        $slides[] = [
            'icon' => $icon,
            'icon_classes' => [(sanitize_title($brand->palette)=='smiles-in-motion' ? 'gray' : 'blue')],
            'heading' => $heading,
            'content' => $copy,
        ];
    }
}
partial('section.wrapper', [
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['top', 'var-4', 'green']
            ]
        ],
        [
            'name' => 'section.pediatric.variable-slide-up-carousel',
            'parts' => [
                'classes' => ['service', 'bg-green', sanitize_title($brand->palette).'-palette'],
                'carousel_classes' => ['slides-'. $counter, 'kill-carousel'],
                'heading' => get_post_meta(get_the_id(), 'service_template_1_section_two_heading', true),
                'content' => apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_1_section_two_content', true)),
                'slides' => $slides,                
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-2', 'green']
            ]
        ],
    ]
]);

partial('section.copy.full', [
    'classes' => ['heading-orange'],
    'heading' => get_post_meta(get_the_id(), 'service_template_1_section_four_heading', true),
    'heading_classes' => ['h1', 'orange'],
    'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_1_section_four_content', true))
]);

$icon_slides_1 = array();
for($j = 0; $j < 2; $j++) {
    $icon_slides_1[] = [
        'icon' => get_post_meta(get_the_id(), 'service_template_1_section_five_slide_' . $j . '_service_template_1_section_five_slide_' . $j . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'service_template_1_section_five_slide_' . $j . '_service_template_1_section_five_slide_' . $j . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'service_template_1_section_five_slide_' . $j . '_service_template_1_section_five_slide_' . $j . '_copy', true),
    ];
}
$section_5_image_id = get_post_meta(get_the_id(), 'service_template_1_section_five_main_image', true);
partial('section.pediatric.two-icons-with-image', [
    'classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'orange')],
    'heading' => get_post_meta(get_the_id(), 'service_template_1_section_five_heading', true),
    'copy' =>  apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_1_section_five_content', true)),
    'image' => [
        'src' => wp_get_attachment_image_src($section_5_image_id, 'medium_large')[0],
        'alt' => get_post_meta($section_5_image_id, '_wp_attachment_image_alt', true),
        'classes' => ['animatable'],
    ],
    'slides' => $icon_slides_1,
]);



$sec_six_heading = get_post_meta(get_the_id(), 'service_template_1_section_six_heading', true);
if(!empty($sec_six_heading)) {
    $icon_slides_2 = array();
    for($k = 0; $k < 2; $k++) {
        $icon_slides_2[] = [
            'icon' => get_post_meta(get_the_id(), 'service_template_1_section_six_slide_' . $k . '_service_template_1_section_six_slide_' . $k . '_icon', true),
            'heading' => get_post_meta(get_the_id(), 'service_template_1_section_six_slide_' . $k . '_service_template_1_section_six_slide_' . $k . '_heading', true),
            'copy' => get_post_meta(get_the_id(), 'service_template_1_section_six_slide_' . $k . '_service_template_1_section_six_slide_' . $k . '_copy', true),
        ];
    }
    $section_6_image_id = get_post_meta(get_the_id(), 'service_template_1_section_six_main_image', true);
    // If no section 7, adjust mottom mrgin up
    $bottom_class = get_post_meta(get_the_id(), 'service_template_1_section_seven_heading', true) ? '' : 'mb-neg-150';
    partial('section.pediatric.two-icons-with-image', [
        'classes' => ['reverse', $bottom_class, (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'orange')],
        'heading' => $sec_six_heading,
        'copy' =>  apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_1_section_six_content', true)),
        'image' => [
            'src' => wp_get_attachment_image_src($section_6_image_id, 'medium_large')[0],
            'alt' => get_post_meta($section_6_image_id, '_wp_attachment_image_alt', true),
            'classes' => ['animatable'],
        ],
        'slides' => $icon_slides_2,
    ]);
}

$sec_seven_heading = get_post_meta(get_the_id(), 'service_template_1_section_seven_heading', true);
if(!empty($sec_seven_heading)) {
    $image_side = get_post_meta(get_the_id(), 'service_template_1_section_seven_toggle', true) ? '' : 'reverse';
    $section_7_image_id = get_post_meta(get_the_id(), 'service_template_1_section_seven_main_image', true);
    partial('section.pediatric.two-icons-with-image', [
        'classes' => ['orange', 'bottom', $image_side, 'after-exam-cta'],
        'heading' => $sec_seven_heading,
        'copy' =>  apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_1_section_seven_content', true)),
        'image' => [
            'src' => wp_get_attachment_image_src($section_7_image_id, 'medium_large')[0],
            'alt' => get_post_meta($section_7_image_id, '_wp_attachment_image_alt', true),
            'classes' => ['animatable'],
        ],
        'sub_and_cta' => [
            'subheading' => get_post_meta(get_the_id(), 'service_template_1_section_seven_subheading', true),
            'cta' => do_shortcode(get_post_meta(get_the_id(), 'service_template_1_section_seven_cta', true)),
        ]
    
    ]);
}


$show_testimonials = get_post_meta(get_the_id(), 'service_template_1_section_eight_toggle', true);
$sec_8_page_faqs = get_faqs_for_page();

if($show_testimonials) {
    $sec_8_slides = array();
    foreach($sec_8_page_faqs as $f) {
        $sec_8_slides[] = [
            'heading' => $f->post_title,
            'copy' => apply_filters('the_content',$f->post_content),
            'image' => [
                'src' => $f->image['src'],
                'alt' => $f->image['alt'],
            ]
        ];
    }
    partial('section.wrapper', [
        'classes' => [],
        'partials' => [
            [
                'name' => 'section.pediatric.bubbles',
                'parts' => [
                    'classes' => ['top', 'var-1', 'gray', 'reverse']
                ]
            ],
            [
                'name' => 'section.pediatric.split-carousel',
                'parts' => [
                    'classes' => ['faq','bg-gray', 'testimonials', sanitize_title($brand->palette).'-palette'],
                    'heading' => get_post_meta(get_the_id(), 'service_template_1_section_eight_heading', true),
                    'heading_classes' => ['h1', 'line-height-1'],
                    'slides' => $sec_8_slides,
                ]
            ],
        ]
    ]);
} else {
    partial('section.wrapper', [
        'classes' => ['service-spacer'],
    ]);
}
	
get_footer();
