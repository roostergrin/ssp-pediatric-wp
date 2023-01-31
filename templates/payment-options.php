<?
# Template Name: Payment options
global $providers, $reviews, $faqs;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();

$hero_img_id = get_post_meta(get_the_id(), 'payment_options_hero_image', true);
partial('section.wrapper', [
    'classes' => ['payment-options-hero'],
    'partials' => [
        [        
            'name' => 'section.hero.standard',
            'parts' => [
                'image' => [                    
                    'src' => wp_get_attachment_image_src($hero_img_id, 'medium_large')[0],
                    'alt' => get_post_meta($hero_img_id, '_wp_attachment_image_alt', true),
                    'classes' => ['bubble', 'animatable']
                ],
                'classes' => [ 'payment-options', sanitize_title($brand->post_title), 'bg-green' ],
                'h1' => get_post_meta(get_the_id(), 'payment_options_hero_heading', true),
                'content' => apply_filters('the_content',get_post_meta(get_the_id(), 'payment_options_hero_subheading', true)),
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['green', 'bottom', 'var-1']
            ]
        ]
    ],
]);

$overlapping_bubbles = array();
if(get_post_meta(get_the_id(), 'payment_options_section_two_toggle', true)) {
    $overlapping_bubbles = [
        'bb_heading' => get_post_meta(get_the_id(), 'payment_options_section_two_bb_heading', true),
        'bb_copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'payment_options_section_two_bb_copy', true)),
        'sb_heading' => get_post_meta(get_the_id(), 'payment_options_section_two_sb_heading', true),
        'sb_copy' => apply_filters('the_content', get_post_meta(get_the_id(), 'payment_options_section_two_sb_copy', true)),
    ];
}

partial('section.copy.overlapping-bubbles', [
    'classes' => [$brand->post_name],
    'bubbles' => $overlapping_bubbles,
    'right_heading' =>  get_post_meta(get_the_id(), 'payment_options_section_two_heading', true),
    'right_copy' =>  apply_filters('the_content', get_post_meta(get_the_id(), 'payment_options_section_two_copy', true)),
]);

$icons = [
	[
		'img' => get_post_meta(get_the_ID(),'payment_options_section_three_slide_0_payment_options_section_three_slide_0_icon', true),
		'copy' => apply_filters('the_content', get_post_meta(get_the_ID(),'payment_options_section_three_slide_0_payment_options_section_three_slide_0_copy', true)),
	],
	[
		'img' => get_post_meta(get_the_ID(),'payment_options_section_three_slide_1_payment_options_section_three_slide_1_icon', true),
		'copy' => apply_filters('the_content', get_post_meta(get_the_ID(),'payment_options_section_three_slide_1_payment_options_section_three_slide_1_copy', true)),
	],
	[
		'img' => get_post_meta(get_the_ID(),'payment_options_section_three_slide_2_payment_options_section_three_slide_2_icon', true),
		'copy' => apply_filters('the_content', get_post_meta(get_the_ID(),'payment_options_section_three_slide_2_payment_options_section_three_slide_2_copy', true)),
	]
];

$sec_three_icons_main_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'red'); 
partial('section.wrapper', [
    'classes' => ['bg-gray'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['white', 'bottom', 'var-5-sparse']
            ]
            ],
        [
            'name' => 'section.pediatric.three-icons',
            'parts' => [
                'section_id' => 'payment-options',
                'classes' => ['bg-gray', $sec_three_icons_main_color, 'icons-red'],
                'heading' => get_post_meta(get_the_ID(), 'payment_options_section_three_heading', true),
                'heading_classes' => [$sec_three_icons_main_color, 'h2'],
                'carousel_container_classes' => ['kill-carousel'],
                'copy_classes' => ['h5 blue'],
                'static_icons' => false,
                'icons' => $icons
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['white', 'top', 'var-5-sparse']
            ]
        ]
    ]
]);

$slides = [];
$counter = 0;
for($i = 0; $i < 4; $i++) {
    $heading = get_post_meta(get_the_id(), 'payment_options_section_four_slide_' . $i . '_payment_options_section_four_slide_' . $i . '_heading', true);
    $copy = get_post_meta(get_the_id(), 'payment_options_section_four_slide_' . $i . '_payment_options_section_four_slide_' . $i . '_copy', true);
    if( empty($heading) || empty($copy) ) {
        break;
    } else {
        $counter++;
        $slides[] = [
            'heading' => $heading,
            'content' => $copy,
        ];
    }
}
partial('section.pediatric.variable-slide-up-carousel', [
    'classes' => ['mb', 'bg-white'],
    'carousel_classes' => ['slides-'. $counter, 'kill-carousel', 'payment'],
    'heading' => get_post_meta(get_the_id(), 'payment_options_section_four_heading', true),
    'slides' => $slides, 
]);

$bottom_hero_img_id = get_post_meta(get_the_id(), 'payment_options_bottom_hero_image', true);
partial('section.wrapper', [
    'classes' => [],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['green', 'top', 'var-3']
            ],
        ],
        [
            'name' => 'section.pediatric.split-static',
            'parts' => [
                'classes' => ['bg-green', 'last-section', 'big-img-left'],
                'heading' => get_post_meta(get_the_id(), 'payment_options_bottom_hero_heading', true),
                'heading_classes' => ['line-height-1', 'h1', 'mb-20'],
                'copy' => apply_filters('the_content',get_post_meta(get_the_id(), 'payment_options_bottom_hero_copy', true)),
                'image' => [                    
                    'src' => wp_get_attachment_image_src($bottom_hero_img_id, 'medium_large')[0],
                    'alt' => get_post_meta($bottom_hero_img_id, '_wp_attachment_image_alt', true),
                    'classes' => ['bubble', 'animatable']
                ],
            ],
        ],
    ]
]);
	
get_footer();
