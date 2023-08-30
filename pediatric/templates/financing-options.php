<?
# Template Name: Financing options
global $providers, $reviews, $faqs;
global $insurance_providers;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();

$hero_img_id = get_post_meta(get_the_id(), 'financing_options_hero_image', true);
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
                'h1' => get_post_meta(get_the_id(), 'financing_options_hero_heading', true),
                'content' => apply_filters('the_content',get_post_meta(get_the_id(), 'financing_options_hero_subheading', true)),
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

$sec_2_main_img_id = get_post_meta(get_the_id(), 'financing_section_two_group_desktop_hero', true);
partial('section.wrapper', [
  'classes' => ['middle', 'mt', 'mb-neg-130'],
  'partials' => [
      [
          'name' => 'section.pediatric.split-static',
          'parts' => [
              'classes' => ['overlap-top'],
              'heading' => get_post_meta(get_the_id(), 'financing_section_two_group_heading', true),
              'heading_classes' => ['line-height-1', 'h1', 'mb-20'],
              'copy' => apply_filters('the_content',get_post_meta(get_the_id(), 'financing_section_two_group_content', true)),
              'image' => [                    
                  'src' => wp_get_attachment_image_src($sec_2_main_img_id, 'medium_large')[0],
                  'alt' => get_post_meta($sec_2_main_img_id, '_wp_attachment_image_alt', true),
                  'classes' => ['bubble', 'animatable']
              ]
          ],
      ],
      [
        'name' => 'section.pediatric.bubbles',
        'parts' => [
            'classes' => ['white', 'bottom', 'var-3']
        ]
    ]
  ]
]);


$icons = [
	[
		'img' => get_post_meta(get_the_ID(),'financing_options_section_three_slide_0_financing_options_section_three_slide_0_icon', true),
		'copy' => apply_filters('the_content', get_post_meta(get_the_ID(),'financing_options_section_three_slide_0_financing_options_section_three_slide_0_copy', true)),
	],
	[
		'img' => get_post_meta(get_the_ID(),'financing_options_section_three_slide_1_financing_options_section_three_slide_1_icon', true),
		'copy' => apply_filters('the_content', get_post_meta(get_the_ID(),'financing_options_section_three_slide_1_financing_options_section_three_slide_1_copy', true)),
	],
	[
		'img' => get_post_meta(get_the_ID(),'financing_options_section_three_slide_2_financing_options_section_three_slide_2_icon', true),
		'copy' => apply_filters('the_content', get_post_meta(get_the_ID(),'financing_options_section_three_slide_2_financing_options_section_three_slide_2_copy', true)),
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
                'heading' => get_post_meta(get_the_ID(), 'financing_options_section_three_heading', true),
                'heading_classes' => [$sec_three_icons_main_color, 'h2'],
                'copy' => get_post_meta(get_the_ID(), 'financing_options_section_three_copy', true),
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


$all_insurance_providers = array_filter($insurance_providers->insurance_providers, function($ins) {
	$relationships = property_exists($ins, 'page_relationship') ? unserialize($ins->page_relationship) : false;
	return !empty($relationships) && is_array($relationships) ? in_array(get_the_ID(), $relationships) : get_the_ID() == $relationships;
});
if (!empty($all_insurance_providers)) {
	usort($all_insurance_providers, function ($a, $b) {
		return $a->post_title <=> $b->post_title;
	});
	partial('section.pediatric.health-plans', [
		'classes' => [''],
		'content_classes' => ['small-width'],
		'h3' => get_post_meta(get_the_ID(),'financing_section_four_heading',true),
		'h3_classes' => ['h2'],
		'content' => apply_filters('the_content', get_post_meta(get_the_ID(),'financing_section_four_content',true)),
		'logos' => $all_insurance_providers
	]);
}

$bottom_hero_img_id = get_post_meta(get_the_id(), 'financing_options_bottom_hero_image', true);
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
                'heading' => get_post_meta(get_the_id(), 'financing_options_bottom_hero_heading', true),
                'heading_classes' => ['line-height-1', 'h1', 'mb-20'],
                'copy' => apply_filters('the_content',get_post_meta(get_the_id(), 'financing_options_bottom_hero_copy', true)),
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
