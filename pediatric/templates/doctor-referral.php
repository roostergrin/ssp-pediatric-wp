<?
# Template Name: Doctor Referral
get_header();
$brand = is_brand();
$hero_image_id = get_post_meta(get_the_ID(), 'doctor_referral_hero_image', true);
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
                'classes' => [ sanitize_title($brand->post_title), 'no-right-padding' ],
                'h1' => get_post_meta(get_the_id(), 'doctor_referral_hero_heading', true),
                'content' => get_post_meta(get_the_id(), 'doctor_referral_hero_subheading', true)
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-1', (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'green' : 'blue'), sanitize_title($brand->palette).'-palette', 'doctor-referral']
            ]
        ]
    ],
]);

partial('section.form', [
	'form' => 'orthodontic-referral',
	'classes' => [sanitize_title($brand->post_title)],
	'content' => '',
]);
get_footer();
