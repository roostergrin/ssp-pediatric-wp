<?
# Template Name: Brand Home
global $locations, $providers, $reviews, $smile_transformations, $faqs;	

$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');

get_header();

$hero_position = $brand->section_one_hero_position ? 'right-side' : 'left-side'; // Default return 0 or left
$hero_text_color = $brand->section_one_heading_color; // Default return 'primary'
$container_color_classes = $hero_text_color === 'primary' ? 'bg-gray' : 'bg-primary';

partial('section.wrapper', [
	'classes' => ['about-our-practice-hero'],
	'partials' => [
		[
			'name' => 'section.hero.standard',
			'parts' => [
				'image' => [
					'src' => wp_get_attachment_image_url(get_post_meta($brand->ID,'homepage_hero_image', true), 'full'),
					'classes' => []
				],
				'classes' => [ sanitize_title($brand->post_title), 'bg-green' ],
				'container_classes' => [],
				'wrapper_classes' => [],
				'h1' => get_post_meta($brand->ID,'homepage_hero_heading', true),
				'h1_classes' => [],
				'content' => apply_filters('the_content', get_post_meta($brand->ID,'homepage_hero_copy', true)),
				'cta' => do_shortcode(get_post_meta($brand->ID,'homepage_hero_link_text', true))
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
	'classes' => ['mt-neg-130'],
	'static_icons' => true,
	'icons' => $icons
]);

$all_providers = array_values(array_filter($providers->providers, function($provider) use ($brand) {
				$brand_relationship = !empty($provider->brand_relationship) ? unserialize($provider->brand_relationship) : false;
				return is_array($brand_relationship) ? in_array($brand->ID, $brand_relationship) : $brand->ID === $brand_relationship;
			}));

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

partial('section.maps.search');

partial('section.pediatric.split-static', [
	'classes' => ['reverse', 'middle', 'mt', 'mb-neg-130', 'z-3'],
	'heading' => get_post_meta($brand->ID,'section5_group_homepage_section5_heading', true),
	'heading_classes' => ['orange'],
	'copy' => apply_filters('the_content', get_post_meta($brand->ID,'section5_group_homepage_section5_copy', true)),
	'image' => [
		'src' => wp_get_attachment_image_src(get_post_meta($brand->ID, 'section5_group_homepage_section5_image', true), 'medium_large')[0],
		'alt' => get_post_meta(get_post_meta($brand->ID, 'section5_group_homepage_section5_image', true),'_wp_attachment_image_alt', true),
		'classes' => ['bubble']
	],
	'cta' => do_shortcode(get_post_meta($brand->ID,'section5_group_homepage_section5_link_text', true)),

]);

$slides_count = get_post_meta($brand->ID,'age_group_slides_section_age_group_slides', true);
$slides = [];
if (!empty($slides_count)) {
	for ($i = 0; $i < $slides_count; $i++) {
		$icon = get_post_meta($brand->ID, 'age_group_slides_section_age_group_slides_'. $i . '_age_group_slides_image', true);
		$slides[] = [
			'heading' => get_post_meta($brand->ID, 'age_group_slides_section_age_group_slides_' . $i . '_age_group_slides_heading', true),
			'content' =>apply_filters('the_content', get_post_meta($brand->ID, 'age_group_slides_section_age_group_slides_' . $i . '_age_group_slides_copy', true)),
			'cta' => get_post_meta($brand->ID, 'age_group_slides_section_age_group_slides_'. $i . '_age_group_slides_link_text', true),
	        'image' => [
				'src' => wp_get_attachment_image_src($icon, 'medium_large')[0],
				'alt' => get_post_meta($icon, '_wp_attachment_image_alt', true),
			]
		];
	}

	$bubbles_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue'); 
	partial('section.wrapper', [
		'partials' => [
			[
				'name' => 'section.pediatric.bubbles',
				'parts' => [
					'classes' => ['top', 'var-1', $bubbles_color]
				]
			],
			[
				'name' => 'section.pediatric.variable-slide-up-carousel',
				'parts' => [
					'classes' => ['service', 'bg-'.$bubbles_color],
					'carousel_classes' => ['slides-3'],
					'heading' => get_post_meta($brand->ID, 'age_group_slides_section_age_group_section_heading', true),
					'slides' => $slides,
				]
			],
			[
				'name' => 'section.pediatric.bubbles',
				'parts' => [
					'classes' => ['bottom', 'var-3', $bubbles_color]
				]
			],

		]
	]);
}

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
			'name' => 'section.pediatric.split-carousel',
			'parts' => [
				'classes' => ['faq',sanitize_title($brand->palette).'-palette'],
				'slides' => $sec_6_slides,
				'is_questions' => true
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
$testimonials_heading_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'gray' : 'red'); 
partial('section.testimonials.carousel', [
	'classes' => [sanitize_title($brand->post_title)],
	'htag' => 'h2',
	'heading_classes' => [$testimonials_heading_color],
	'heading' => 'Our&nbsp;patients (and&nbsp;their&nbsp;parents) say&nbsp;it&nbsp;best',
	'reviews' => $testimonials,
]);

$sec_8_heading = get_post_meta($brand->ID, 'homepage_bottom_section_heading', true);
$sec_8_copy = apply_filters('the_content', get_post_meta($brand->ID, 'homepage_bottom_section_copy', true));
$sec_8_slides = array();
$sec_8_slide_count = get_post_meta($brand->ID, 'homepage_bottom_section_slides', true);
if ($sec_8_slide_count > 0) {
	for ($z = 0; $z < $sec_8_slide_count; $z++) {
		$attachment_id = get_post_meta($brand->ID, 'homepage_bottom_section_slides_'.($z).'_image', true);
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
