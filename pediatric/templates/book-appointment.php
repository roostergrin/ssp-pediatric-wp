<?
# Template Name: Book Appointment

get_header();
$brand = is_brand();
$location = is_location();
$hero_img_id = get_post_meta(get_the_id(), 'book_appointment_hero_image', true);
partial('section.wrapper', [
    'classes' => ['bg-green'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['background', 'var-1', 'white', 'absolute', $brand->post_name, (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'no-z-index' : ''), ($location == false ? '' : 'is-location')]
            ]
        ],
        [
            'name' => 'section.hero.centered',
            'parts' => [
                'classes' => ['bg-green', $brand->post_name, (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'no-z-index' : '')],
                'h1' => get_post_meta(get_the_id(), 'book_appointment_hero_heading', true),
                'h1_classes' => ['line-height-1'],
                'copy' => apply_filters('the_content',get_post_meta(get_the_id(), 'book_appointment_hero_copy', true)),
                'image' => [                    
                    'src' => wp_get_attachment_image_src($hero_img_id, 'full')[0],
                    'alt' => get_post_meta($hero_img_id, '_wp_attachment_image_alt', true),
                    'classes' => ['bubble', 'animatable']
                ],
                'cta' => do_shortcode(get_post_meta(get_the_id(), 'book_appointment_hero_cta', true)),
            ]
        ],
    ]
]);

partial('section.pediatric.bubbles', [
    'classes' => ['bottom', 'var-2', 'green', 'reverse', $brand->post_name,  ($location == false ? '' : 'is-location')]
]);

for($i = 0; $i < 3; $i++) {
    $slides[] = [
    'color' => get_post_meta(get_the_id(), 'book_appointment_section_two_slides_' . $i . '_book_appointment_section_two_slides_' . $i . '_color', true),
    'icon' => get_post_meta(get_the_id(), 'book_appointment_section_two_slides_' . $i . '_book_appointment_section_two_slides_' . $i . '_icon', true),
    'heading' => get_post_meta(get_the_id(), 'book_appointment_section_two_slides_' . $i . '_book_appointment_section_two_slides_' . $i . '_heading', true),
    'copy' => get_post_meta(get_the_id(), 'book_appointment_section_two_slides_' . $i . '_book_appointment_section_two_slides_' . $i . '_copy', true),
    ];
}

partial('section.pediatric.icons-reveal-text', [
	'slides' => $slides
]);

$form_image_id = get_post_meta(get_the_id(),  'book_appointment_form_image', true);
partial('section.form', [
    'form' => 'appointment',
    'classes' => [sanitize_title($brand->palette).'-palette'],
    'heading' => [
        'tag' => 'h2',
        'text' => get_post_meta(get_the_id(), 'book_appointment_form_heading', true ),
        'classes' => ['orange', 'line-height-1'],
    ],
    'content' => apply_filters('the_content', get_post_meta(get_the_id(), 'book_appointment_form_copy', true )),
    'image' => [
        'src' => wp_get_attachment_image_src($form_image_id, 'medium_large')[0],
        'alt' => get_post_meta($form_image_id, '_wp_attachment_image_alt', true),
    ],
    'disclaimer' => apply_filters('the_content', get_post_meta(get_the_id(), 'book_appointment_form_disclaimer', true )),

]);
get_footer();