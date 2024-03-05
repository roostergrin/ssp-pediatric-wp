<?
# Template Name: Service Template 2
global $providers, $reviews, $faqs;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();

$hero_img_id = get_post_meta(get_the_id(), 'service_template_2_hero_image', true);

partial('section.wrapper', [
    'classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-green' : 'bg-blue')],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['background', 'var-1', 'white', 'absolute']
            ]
        ],
        [
            'name' => 'section.hero.centered',
            'parts' => [
                'classes' => ['bg-blue', sanitize_title($brand->palette)],
                'h1' => get_post_meta(get_the_id(), 'service_template_2_hero_heading', true),
                'h1_classes' => ['line-height-1'],
                'copy' => apply_filters('the_content',get_post_meta(get_the_id(), 'service_template_2_hero_copy', true)),
                'image' => [                    
                    'src' => wp_get_attachment_image_src($hero_img_id, 'full')[0],
                    'alt' => get_post_meta($hero_img_id, '_wp_attachment_image_alt', true),
                    'classes' => ['bubble', 'animatable']
                ],
                'cta' => do_shortcode(get_post_meta(get_the_id(), 'service_template_2_hero_cta', true)),
            ]
        ]
    ]
]);

$slides = [];
$counter = 0;
$y = 2;

// SIM4KIDS brand
if ($brand->ID == 18088) $y = 4;

for($i = 0; $i < $y; $i++) {
    $icon = get_post_meta(get_the_id(), 'service_template_2_section_two_slide_' . $i . '_service_template_2_section_two_slide_' . $i . '_icon', true);
    $heading = get_post_meta(get_the_id(), 'service_template_2_section_two_slide_' . $i . '_service_template_2_section_two_slide_' . $i . '_heading', true);
    $copy = get_post_meta(get_the_id(), 'service_template_2_section_two_slide_' . $i . '_service_template_2_section_two_slide_' . $i . '_copy', true);

    if(empty($icon) && empty($heading) && empty($copy) ) {
        break;
    } else {
        $counter++;
        $slides[] = [
            'icon' => $icon,
            'icon_classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'green')],
            'heading' => $heading,
            'content' => $copy,
        ];
    }
}

partial('section.wrapper', [
    'partials' => [
        [
            'name' => 'section.pediatric.variable-slide-up-carousel',
            'parts' => [
                'classes' => ['service', (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-green' : 'bg-blue'), sanitize_title($brand->palette).'-palette'],
                'carousel_classes' => ['slides-'. $counter, 'kill-carousel'],
                'heading' => get_post_meta(get_the_id(), 'service_template_2_section_two_heading', true),
                'content' => apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_2_section_two_content', true)),
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

partial('section.copy.full', [
    'classes' => ['heading-orange'],
    'heading' => get_post_meta(get_the_id(), 'service_template_2_section_four_heading', true),
    'heading_classes' => ['h1', 'orange'],
    'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_2_section_four_content', true))
]);

$icon_slides_1 = array();
for($j = 0; $j < 2; $j++) {
    $icon_slides_1[] = [
        'icon' => get_post_meta(get_the_id(), 'service_template_2_section_five_slide_' . $j . '_service_template_2_section_five_slide_' . $j . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'service_template_2_section_five_slide_' . $j . '_service_template_2_section_five_slide_' . $j . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'service_template_2_section_five_slide_' . $j . '_service_template_2_section_five_slide_' . $j . '_copy', true),
    ];
}
$section_5_image_id = get_post_meta(get_the_id(), 'service_template_2_section_five_main_image', true);
partial('section.pediatric.two-icons-with-image', [
    'classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'orange'), 'mb-150'],
    'heading' => get_post_meta(get_the_id(), 'service_template_2_section_five_heading', true),
    'copy' =>  apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_2_section_five_content', true)),
    'image' => [
        'src' => wp_get_attachment_image_src($section_5_image_id, 'medium_large')[0],
        'alt' => get_post_meta($section_5_image_id, '_wp_attachment_image_alt', true),
        'classes' => ['animatable'],
    ],
    'slides' => $icon_slides_1,

]);


$sec_six_heading = get_post_meta(get_the_id(), 'service_template_2_section_six_heading', true);
if(!empty($sec_six_heading)) {
    $icon_slides_2 = array();
    for($k = 0; $k < 2; $k++) {
        $icon_slides_2[] = [
            'icon' => get_post_meta(get_the_id(), 'service_template_2_section_six_slide_' . $k . '_service_template_2_section_six_slide_' . $k . '_icon', true),
            'heading' => get_post_meta(get_the_id(), 'service_template_2_section_six_slide_' . $k . '_service_template_2_section_six_slide_' . $k . '_heading', true),
            'copy' => get_post_meta(get_the_id(), 'service_template_2_section_six_slide_' . $k . '_service_template_2_section_six_slide_' . $k . '_copy', true),
        ];
    }
    $section_6_image_id = get_post_meta(get_the_id(), 'service_template_2_section_six_main_image', true);
    partial('section.pediatric.two-icons-with-image', [
        'classes' => [(sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'orange'), 'reverse', 'mb-neg-150'],
        'heading' => $sec_six_heading,
        'copy' =>  apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_2_section_six_content', true)),
        'image' => [
            'src' => wp_get_attachment_image_src($section_6_image_id, 'medium_large')[0],
            'alt' => get_post_meta($section_6_image_id, '_wp_attachment_image_alt', true),
            'classes' => ['animatable'],
        ],
        'slides' => $icon_slides_2,
    ]);
}

$sec_seven_heading = get_post_meta(get_the_id(), 'service_template_2_section_seven_heading', true);
if(!empty($sec_seven_heading)) {
    $image_side = get_post_meta(get_the_id(), 'service_template_2_section_seven_toggle', true) ? '' : 'reverse';
    $section_7_image_id = get_post_meta(get_the_id(), 'service_template_2_section_seven_main_image', true);
    partial('section.pediatric.two-icons-with-image', [
        'classes' => ['orange', 'bottom', $image_side],
        'heading' => $sec_seven_heading,
        'copy' =>  apply_filters('the_content', get_post_meta(get_the_id(), 'service_template_2_section_seven_content', true)),
        'image' => [
            'src' => wp_get_attachment_image_src($section_7_image_id, 'medium_large')[0],
            'alt' => get_post_meta($section_7_image_id, '_wp_attachment_image_alt', true),
            'classes' => ['animatable'],
        ],
        'sub_and_cta' => [
            'subheading' => get_post_meta(get_the_id(), 'service_template_2_section_seven_subheading', true),
            'cta' => do_shortcode(get_post_meta(get_the_id(), 'service_template_2_section_seven_cta', true)),
        ]
    
    ]);
}

$show_testimonials = get_post_meta(get_the_id(), 'service_template_2_section_eight_toggle', true);
$sec_8_page_faqs = get_faqs_for_page();

$sec_bottom = get_post_meta(get_the_id(), 'section_eight_tab_about_our_practice', true);

$sec_8_heading = get_post_meta(get_the_id(), 'about_our_practice_section_eight_heading', true);
$sec_8_copy = apply_filters('the_content', get_post_meta(get_the_id(), 'about_our_practice_section_eight_copy', true));
$sec_8_slides = array();
$sec_8_slide_count = get_post_meta(get_the_id(), 'about_our_practice_section_eight_slides', true);
if ($sec_8_slide_count > 0) {
	for ($z = 0; $z < $sec_8_slide_count; $z++) {
		$attachment_id = get_post_meta(get_the_id(), 'about_our_practice_section_eight_slides_'.($z).'_image', true);
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
				'classes' => ['top', 'var-3', (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue')]
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
                    'classes' => ['top', 'var-2', 'gray']
                ]
            ],
            [
                'name' => 'section.pediatric.split-carousel',
                'parts' => [
                    'classes' => ['faq','bg-gray', 'testimonials', sanitize_title($brand->palette).'-palette'],
                    'heading' => get_post_meta(get_the_id(), 'service_template_2_section_eight_heading', true),
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
