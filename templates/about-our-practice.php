<?
# Template Name: About Our Practice
global $providers, $reviews, $faqs;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();
$hero_image_id = get_post_meta(get_the_ID(), 'about_our_practice_hero_image', true);
$hero_bubble_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue');
partial('section.wrapper', [
    'classes' => ['about-our-practice-hero'],
    'partials' => [
        [        
            'name' => 'section.hero.standard',
            'parts' => [
                'image' => [
                    'src' => wp_get_attachment_image_src($hero_image_id, 'medium_large')[0],
                    'alt' => get_post_meta($hero_image_id, '_wp_attachment_image_alt', true),
                    'classes' => ['animatable']
                ],
                'classes' => [ sanitize_title($brand->post_title) ],
                'h1' => get_post_meta(get_the_id(), 'about_our_practice_hero_heading', true),
                'content' => get_post_meta(get_the_id(), 'about_our_practice_hero_subheading', true),
                'cta' => get_post_meta(get_the_id(), 'about_our_practice_hero_cta', true),
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-1', $hero_bubble_color, 'post-hero']
            ]
        ]
    ],
]);

$sec_two_image_id = get_post_meta(get_the_ID(), 'about_our_practice_section_two_image', true);
partial('section.pediatric.split-static', [
    'classes' => ['overlap-top'],
    'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_two_heading', true),
    'heading_classes' => ['font-size-20px'],
    'copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'about_our_practice_section_two_content', true)),
    'image' => [
        'src' => wp_get_attachment_image_src($sec_two_image_id, 'medium_large')[0],
		'alt' => get_post_meta($sec_two_image_id, '_wp_attachment_image_alt', true),
        'classes' => ['bubble', 'animatable']
    ]
]);

$sec_3_l_slides = array();
$sec_3_l_slides_num = 3;
for($i = 0; $i < $sec_3_l_slides_num; $i++) {
    $sec_3_l_slides[] = [
        'icon' => get_post_meta(get_the_id(), 'about_our_practice_section_three_left_col_slides_'. $i . '_about_our_practice_section_three_left_col_slides_'. $i . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_three_left_col_slides_'. $i . '_about_our_practice_section_three_left_col_slides_'. $i . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'about_our_practice_section_three_left_col_slides_'. $i . '_about_our_practice_section_three_left_col_slides_'. $i . '_copy', true),
    ];
}
$sec_3_r_slides = array();
$sec_3_r_slides_num = 3;
for($i = 0; $i < $sec_3_r_slides_num; $i++) {
    $sec_3_r_slides[] = [
        'icon' => get_post_meta(get_the_id(), 'about_our_practice_section_three_right_col_slides_'. $i . '_about_our_practice_section_three_right_col_slides_'. $i . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_three_right_col_slides_'. $i . '_about_our_practice_section_three_right_col_slides_'. $i . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'about_our_practice_section_three_right_col_slides_'. $i . '_about_our_practice_section_three_right_col_slides_'. $i . '_copy', true),
    ];
}

$sec_3_color_class = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'orange': 'red'); // Smiles in Motion
partial('section.pediatric.effervescent-reversible-with-carousel', [
    'classes' => [$sec_3_color_class],
    'heading'=> get_post_meta(get_the_id(), 'about_our_practice_section_three_main_heading', true),
    'heading_classes' => ['h1'],
    'wrapper_left_heading' => get_post_meta(get_the_id(), 'about_our_practice_section_three_bubble_heading', true),
    'wrapper_left_subheading' => get_post_meta(get_the_id(), 'about_our_practice_section_three_bubble_subheading', true),
    'wrapper_left_copy' => get_post_meta(get_the_id(), 'about_our_practice_section_three_bubble_content', true),
    'eyebrow_text' => get_post_meta(get_the_id(), 'about_our_practice_section_three_eyebrows', true),
    'sub_wrapper_left'=> [
        'slides' => $sec_3_l_slides
    ],
    'sub_wrapper_right'=> [
        'slides' => $sec_3_r_slides        
    ] 
]);

$sec_4_l_slides = array();
$sec_4_l_slides_num = 3;
for($i = 0; $i < $sec_4_l_slides_num; $i++) {
    $sec_4_l_slides[] = [
        'icon' => get_post_meta(get_the_id(), 'about_our_practice_section_four_left_col_slides_'. $i . '_about_our_practice_section_four_left_col_slides_'. $i . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_four_left_col_slides_'. $i . '_about_our_practice_section_four_left_col_slides_'. $i . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'about_our_practice_section_four_left_col_slides_'. $i . '_about_our_practice_section_four_left_col_slides_'. $i . '_copy', true),
    ];
}
$sec_4_r_slides = array();
$sec_4_r_slides_num = 3;
for($i = 0; $i < $sec_4_r_slides_num; $i++) {
    $sec_4_r_slides[] = [
        'icon' => get_post_meta(get_the_id(), 'about_our_practice_section_four_right_col_slides_'. $i . '_about_our_practice_section_four_right_col_slides_'. $i . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_four_right_col_slides_'. $i . '_about_our_practice_section_four_right_col_slides_'. $i . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'about_our_practice_section_four_right_col_slides_'. $i . '_about_our_practice_section_four_right_col_slides_'. $i . '_copy', true),
    ];
}
$sec_four_img_id = get_post_meta(get_the_id(), 'about_our_practice_section_four_main_image', true);
$sec_4_color_class = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green': 'blue'); 
partial('section.pediatric.effervescent-reversible-with-carousel', [
    'classes' => [$sec_4_color_class, 'reverse'],
    'top_image' => [
        'src' => wp_get_attachment_image_src($sec_four_img_id, 'medium_large')[0],
        'alt' => get_post_meta($sec_four_img_id, '_wp_attachment_image_alt', true),
        'classes' => ['animatable']
    ],
    'wrapper_left_heading' => get_post_meta(get_the_id(), 'about_our_practice_section_four_bubble_heading', true),
    'wrapper_left_subheading' => get_post_meta(get_the_id(), 'about_our_practice_section_four_bubble_subheading', true),
    'wrapper_left_copy' => get_post_meta(get_the_id(), 'about_our_practice_section_four_bubble_content', true),
    'eyebrow_text' => get_post_meta(get_the_id(), 'about_our_practice_section_four_eyebrows', true),
    'sub_wrapper_left'=> [
        'slides' => $sec_4_l_slides
    ],
    'sub_wrapper_right'=> [
        'slides' => $sec_4_r_slides
    ] 
]);


$sec_5_l_slides = array();
$sec_5_l_slides_num = 3;
for($i = 0; $i < $sec_5_l_slides_num; $i++) {
    $sec_5_l_slides[] = [
        'icon' => get_post_meta(get_the_id(), 'about_our_practice_section_five_left_col_slides_'. $i . '_about_our_practice_section_five_left_col_slides_'. $i . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_five_left_col_slides_'. $i . '_about_our_practice_section_five_left_col_slides_'. $i . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'about_our_practice_section_five_left_col_slides_'. $i . '_about_our_practice_section_five_left_col_slides_'. $i . '_copy', true),
    ];
}
$sec_5_r_slides = array();
$sec_5_r_slides_num = 3;
for($i = 0; $i < $sec_5_r_slides_num; $i++) {
    $sec_5_r_slides[] = [
        'icon' => get_post_meta(get_the_id(), 'about_our_practice_section_five_right_col_slides_'. $i . '_about_our_practice_section_five_right_col_slides_'. $i . '_icon', true),
        'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_five_right_col_slides_'. $i . '_about_our_practice_section_five_right_col_slides_'. $i . '_heading', true),
        'copy' => get_post_meta(get_the_id(), 'about_our_practice_section_five_right_col_slides_'. $i . '_about_our_practice_section_five_right_col_slides_'. $i . '_copy', true),
    ];
}
$sec_five_img_id = get_post_meta(get_the_id(), 'about_our_practice_section_five_main_image', true);
$sec_5_color_class = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'brown': 'orange'); // Smiles in Motion
partial('section.pediatric.effervescent-reversible-with-carousel', [
    'classes' => [$sec_5_color_class, 'mb-1'],
    'top_image' => [
        'src' => wp_get_attachment_image_src($sec_five_img_id, 'medium_large')[0],
        'alt' => get_post_meta($sec_five_img_id, '_wp_attachment_image_alt', true),
        'classes' => ['animatable']
    ],
    'wrapper_left_heading' => get_post_meta(get_the_id(), 'about_our_practice_section_five_bubble_heading', true),
    'wrapper_left_subheading' => get_post_meta(get_the_id(), 'about_our_practice_section_five_bubble_subheading', true),
    'wrapper_left_copy' => get_post_meta(get_the_id(), 'about_our_practice_section_five_bubble_content', true),
    'eyebrow_text' => get_post_meta(get_the_id(), 'about_our_practice_section_five_eyebrows', true),
    'sub_wrapper_left'=> [
        'slides' => $sec_5_l_slides
    ],
    'sub_wrapper_right'=> [
        'slides' => $sec_5_r_slides
        ] 
]);

$sec_6_main_img_id = get_post_meta(get_the_id(), 'about_our_practice_section_six_image', true);
partial('section.wrapper', [
    'classes' => ['abpd-section'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['top', 'var-1', 'green']
            ]
        ],
        [
            'name' => 'section.pediatric.split-static',
            'parts' =>[
                'classes' => ['bg-green', 'reverse', 'seventy-thirty'],
                'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_six_heading', true),
                'heading_classes' => ['mb-15'],
                'copy' => get_post_meta(get_the_id(), 'about_our_practice_section_six_content', true),
                'image' => [
                    'src' => wp_get_attachment_image_src($sec_6_main_img_id, 'medium_large')[0],
                    'alt' => get_post_meta($sec_6_main_img_id, '_wp_attachment_image_alt', true),
                    'width' => '200',
                    'height' => '200',
                ]
            ]
        ],
    ]
]);

$sec_6_slides = array();
$sec_6_page_faqs = get_faqs_for_page();

foreach($sec_6_page_faqs as $f) {
    $sec_6_slides[] = [
        'heading' => $f->post_title,
        'copy' => apply_filters('the_content',$f->post_content),
        'image' => [
            'src' => $f->image['src'],
            'alt' => $f->image['alt'],
        ]
    ];
}
partial('section.wrapper', [
    'classes' => ['questions'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-2', 'green', 'absolute']
            ]
        ],
        [
            'name' => 'section.pediatric.split-carousel',
            'parts' => [
                'classes' => ['faq','questions-carousel', sanitize_title($brand->palette).'-palette'],
                'slides' => $sec_6_slides,
	            'is_questions' => true
            ]
        ],
    ]
]);

$all_providers = array_values(array_filter($providers->providers, function($provider) use ($brand_location_ids) {
    $location_relationship = !empty($provider->location_relationship) ? unserialize($provider->location_relationship) : false;
    return ($location_relationship == false ? null : array_intersect($location_relationship, $brand_location_ids));
}));

// Test home carousel by unsetting all but 2 providers
// unset($all_providers[2]);

if (!empty($all_providers)) {
    usort($all_providers, function($a, $b) {
        return $a->menu_order <=> $b->menu_order;
    });
    if (count($all_providers) > 2) {
        partial('section.providers.tri-carousel', [
            'heading' => 'Meet the board-certified dentists who work their magic',
            'providers' => $all_providers,
            'classes' => [sanitize_title($brand->palette).'-palette']
        ]);
    } else {
        partial('section.providers.home-carousel', [
            'heading' => 'Meet the board-certified dentists who work their magic',
            'providers' => $all_providers
        ]);
    }
}

$sec_7_img_id = get_post_meta(get_the_id(), 'about_our_practice_section_seven_image', true);
$sec_7_slides = array();
$sec_7_slides_num = get_post_meta(get_the_id(), 'about_our_practice_section_seven_slides', true);
$sec_7_slides_icon_bg_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'bg-gray' : 'bg-green'); 
// Just 5 items for now.
for($i = 0; $i < 5; $i++) {
    $icon = get_post_meta(get_the_id(), 'about_our_practice_section_seven_slides_'. $i . '_about_our_practice_section_seven_slides_'. $i . '_icon', true);
    $sec_7_slides[] = [
        'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_seven_slides_'. $i . '_about_our_practice_section_seven_slides_'. $i . '_heading', true),
        'content' => get_post_meta(get_the_id(), 'about_our_practice_section_seven_slides_'. $i . '_about_our_practice_section_seven_slides_'. $i . '_copy', true),
        'cta' => get_post_meta(get_the_id(), 'about_our_practice_section_seven_slides_'. $i . '_about_our_practice_section_seven_slides_'. $i . '_cta', true),
        'icon' => [
            'classes' => [$sec_7_slides_icon_bg_color],
            'icon' => get_post_meta(get_the_id(), 'about_our_practice_section_seven_slides_'. $i . '_about_our_practice_section_seven_slides_'. $i . '_icon', true)
        ]
    ];
}

$sec_7_bubble_color_class = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green': 'blue'); 
partial('section.wrapper', [
    'classes' => ['treatments'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['top', 'var-2', $sec_7_bubble_color_class]
            ]
        ],
        [
            'name' => 'section.pediatric.treatments-carousel',
            'parts' =>[
                'image' => [
                    'src' => wp_get_attachment_image_src($sec_7_img_id, 'medium_large')[0],
                    'alt' => get_post_meta($sec_7_img_id, '_wp_attachment_image_alt', true),
                    'classes' => ['animatable']
                ],
                'heading' => get_post_meta(get_the_id(), 'about_our_practice_section_seven_heading', true),
                'h_tag' => 'h2',
                'h_class' => 'h1',
                'content' => get_post_meta(get_the_id(), 'about_our_practice_section_seven_subheading', true),
                'slides' => $sec_7_slides,                
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-3', $sec_7_bubble_color_class]
            ]
        ],
    ]
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

$testimonial_header_color_class = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray': 'red');
partial('section.testimonials.carousel', [
    'classes' => [sanitize_title($brand->post_title)],
    'htag' => 'h2',
    'heading_classes' => [$testimonial_header_color_class],
    'heading' => 'Our&nbsp;patients (and&nbsp;their&nbsp;parents) say&nbsp;it&nbsp;best',
    'reviews' => $testimonials,
]);

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
