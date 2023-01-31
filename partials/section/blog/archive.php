<?
$brand = is_brand();
$post_url = blog_url('kids-dentist-blog/'.basename(get_permalink($large_post->ID)).'/', '');
$large_image_src_1x = wp_get_attachment_image_src(get_post_thumbnail_id($large_post->ID), 'large', false);
$large_image_src_2x = wp_get_attachment_image_src(get_post_thumbnail_id($large_post->ID), '2048x2048', false);
$large_image = !empty(get_post_thumbnail_id($large_post->ID)) ? responsive_static_img(['src' => $large_image_src_1x[0], 'srcset' => $large_image_src_1x[0].' 1x, '.$large_image_src_2x[0].' 2x', 'sizes' => '100vw', 'width' => $large_image_src_1x[1], 'height' => $large_image_src_1x[2], 'alt' => !empty(get_post_meta(get_post_thumbnail_id($large_post->ID), '_wp_attachment_image_alt', true)) ? get_post_meta(get_post_thumbnail_id($large_post->ID), '_wp_attachment_image_alt', true) : str_replace('_', ' ', get_the_title(get_post_thumbnail_id($large_post->ID))), 'class' => '']) : '';
?>
<?
partial('section.wrapper', [
    'classes' => ['blog-hero'],
    'partials' => [
        [        
            'name' => 'section.hero.centered',
            'parts' => [
                'classes' => ['bg-green', 'padding', 'no-mb'],
                'h1' => get_post_meta(get_the_id(), 'blog_archive_page_hero_title', true),
				'h1_classes' => ['blog-hero-title'],
                'copy' => get_post_meta(get_the_id(), 'blog_archive_page_hero_sub_text', true),
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
<section class="blog archive">
	<div class="content">
		<div class="inner-content">
			<div class="main-container">
				<div class="content-container">
					<div class="blog-posts">
						<div class="large-posts">
							<? partial('widget.blog.large', [
								'image' => $large_image,
								'content' => [
									'h2' => $large_post->post_title,
									'h2_classes' => ['white'],
									'categories' => wp_get_post_categories($large_post->ID),
									'copy' => excerptizeCharacters(do_shortcode($large_post->post_content), 310),
									'cta' => [
										'href' => $post_url,
										'classes' => ['cta', 'white', 'primary'],
										'text' => 'Read article'
									]
								]
							]); ?>
						</div>
						<div class="small-posts">
							<?
								foreach ($small_posts as $post) {
									$small_image_src_1x = wp_get_attachment_image_src(get_post_thumbnail_id($post), 'medium_large', false);
									$small_image_src_2x = wp_get_attachment_image_src(get_post_thumbnail_id($post), '1536x1536', false);
									$small_image = !empty(get_post_thumbnail_id($post)) ? responsive_static_img(['src' => $small_image_src_1x[0], 'srcset' => $small_image_src_1x[0].' 1x, '.$small_image_src_2x[0].' 2x', 'sizes' => '100vw', 'width' => $small_image_src_1x[1], 'height' => $small_image_src_1x[2], 'alt' => !empty(get_post_meta(get_post_thumbnail_id($post), '_wp_attachment_image_alt', true)) ? get_post_meta(get_post_thumbnail_id($post), '_wp_attachment_image_alt', true) : str_replace('_', ' ', get_the_title(get_post_thumbnail_id($post))), 'class' => '']) : '';
									$post_url = blog_url('kids-dentist-blog/'.basename(get_permalink($post)).'/');

									partial('widget.blog.small', [
										'image' => $small_image,
										'content' => [
											'h3' => $post->post_title,
											'h3_classes' => ['white'],
											'categories' => wp_get_post_categories($post->ID),
											'copy' => excerptizeCharacters(do_shortcode($post->post_content), 198),
											'cta' => [
												'href' => $post_url,
												'classes' => ['cta', 'primary', 'white'],
												'text' => 'Read article'
											]
										]
									]);
								}
							?>
						</div>
					</div>
					<? /* <? if($found_posts >= 5): ?>
					<a id="load-more" href="#" data-page="2" class="cta <?= (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'primary' : 'secondary'); ?>">Load more</a>
					<? endif ?> */ ?>
				</div>
				<? partial('widget.blog.sidebar'); ?>
			</div>
		</div>
	</div>
</section>
