<?
# Template Name: Community Involvement
$brand = is_brand();
wp_enqueue_script('internal-logo-carousel');
get_header();

$form_image_id = get_post_thumbnail_id( get_the_id() );
partial('section.form', [
    'form' => 'community',
    'heading' => [
        'tag' => 'h1',
        'text' => 'Submit a sponsorship opportunity',
        'classes' => ['h2', 'line-height-1'],
    ],
    'image' => [
        'src' => wp_get_attachment_image_src($form_image_id, 'medium_large')[0],
        'alt' => get_post_meta($form_image_id, '_wp_attachment_image_alt', true),
    ],
    'staggered_image' => true
]);

get_footer();