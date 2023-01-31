<?
# Template Name: Patient Form
global $providers, $reviews;
$brand = is_brand();
$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');
$location = is_location();
get_header();

$hero_img_id = get_post_meta(get_the_id(), 'patient_form_hero_image', true);
$patient_form_url = wp_get_attachment_url( $brand->info_packet );
$cta_classes = (sanitize_title($brand->palette) == 'smiles-in-motion' ? '' : 'red hover-blue');

/**
 * typical behavior is to attach a media library .pdf, but sometimes
 * this may be a url saved under the "Patient Forms Page" WP admin 
 * Location post type editor
 */
if(!empty($location)) { 
    if(empty($location->info_packet)) {
        $patient_form_url = $location->new_patient_form;
    } else {
        $patient_form_url = wp_get_attachment_url( $location->info_packet );
    }
} 

$cta_source = '<a href="' . $patient_form_url . '" class="cta ' . $cta_classes . '" target="_blank">New patient forms</a>';


partial('section.wrapper', [
    'classes' => ['bg-green'],
    'partials' => [
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['background', 'var-1', 'white', 'absolute', $brand->post_name, (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'no-z-index' : '')]
            ]
        ],
        [
            'name' => 'section.hero.centered',
            'parts' => [
                'classes' => ['bg-green', $brand->post_name, (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'no-z-index' : '')],
                'h1' => get_post_meta(get_the_ID(),'patient_form_hero_heading', true),
                'h1_classes' => ['line-height-1'],
                'copy' => apply_filters('the_content', get_post_meta(get_the_ID(),'patient_form_hero_copy', true)),
                'image' => [                    
                    'src' => wp_get_attachment_image_src($hero_img_id, 'full')[0],
                    'alt' => get_post_meta($hero_img_id, '_wp_attachment_image_alt', true),
                    'classes' => ['bubble', 'animatable']
                ],
                'cta' => $cta_source
            ]
        ]
    ]
]);

partial('section.wrapper', [
        'partials' => [
            [
                'name' => 'section.pediatric.bubbles',
                'parts' => [
                    'classes' => ['bottom', 'var-2', 'green', $brand->post_name, (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'reverse' : '')]
                ]
            ],
        ]
    ]);
	
get_footer();
