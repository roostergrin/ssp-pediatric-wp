<?

/**
 * ALERT
 * 
 * because this template is associated with virtual WP pages
 * you may experience issues with compiling LESS changes
 */

$image_src_1x = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large', false);
$image_src_2x = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), '2048x2048', false);

$image = !empty(get_post_thumbnail_id(get_the_ID())) ? responsive_static_img(['src' => $image_src_1x[0], 'srcset' => $image_src_1x[0].' 1x, '.$image_src_2x[0].' 2x', 'sizes' => '100vw', 'width' => $image_src_1x[1], 'height' => $image_src_1x[2], 'alt' => !empty(get_post_meta(get_post_thumbnail_id(get_the_ID()), '_wp_attachment_image_alt', true)) ? get_post_meta(get_post_thumbnail_id(get_the_ID()), '_wp_attachment_image_alt', true) : str_replace('_', ' ', get_the_title(get_post_thumbnail_id(get_the_ID()))), 'class' => '']) : '';
?>
<?
partial('section.wrapper', [
    'classes' => ['blog-hero'],
    'partials' => [
        [        
            'name' => 'section.hero.centered',
            'parts' => [
                'classes' => ['bg-green', 'padding', 'no-mb', 'blog-single-hero-heading-container'],
                'h1' => the_title('','',false),
				'h1_classes' => ['blog-hero-title']
            ]
        ],
        [
            'name' => 'section.pediatric.bubbles',
            'parts' => [
                'classes' => ['bottom', 'var-4', 'green']
            ]
        ],
    ]
])
?>
<section class="blog-post single">
	<div class="content">
		<div class="inner-content">
			<div class="main-container">
				<div class="content-container">
					<? if (!empty($image)) : ?>
						<div class="img-container">
							<?= $image; ?>
						</div>
					<? endif; ?>
					<div class="content">
						<article>
							<? the_content(); ?>
						</article>
					</div>
				</div>
				<? partial('widget.blog.sidebar', ['back' => true, 'label' => 'Back to blog library']); ?>
			</div>
		</div>
	</div>
</section>
