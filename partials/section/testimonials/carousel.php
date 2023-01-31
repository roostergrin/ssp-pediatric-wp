<?
$heading = $heading ?? false;
$htag = $htag ?? 'h2';
$heading_classes = $heading_classes ?? ['primary'];
wp_enqueue_script('testimonials-carousel');
?>
<section class="testimonials carousel<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
	<div class="content">
		<div class="inner-content">
			<? if (!empty($reviews)) : ?>
				<div class="main-container">
					<div class="content-container">
						<? if ($heading): ?><<?= $htag ?> class="<?= implode(' ', $heading_classes) ?>"><?= esc_html($heading) ?></<?= $htag ?>><? endif ?>
						<div class="content-carousel">
							<? foreach($reviews as $review): ?>
								<?
								$excerpt = $review->post_content;
								?>
								<div class="carousel-item">
									<div class="content">
										<p class="name"><?= $review->post_title; ?></p>
										<p class="testimonial" data-content="<?= esc_attr($review->post_content) ?>">
										<?
											if ($excerpt != $review->post_content) {
												$more = str_replace($excerpt, '', $review->post_content);
												echo $excerpt . '<span class="more hidden"> ' . $more . '</span>';
											} else {
												echo $review->post_content;
											}
										?>
										</p>
									</div>
								</div>
							<? endforeach; ?>
						</div>
						<div class="pagination-container">
							<div class="pagination">
								<i class="icon-left-arrow-thick"></i>
								<i class="icon-right-arrow-thick"></i>
							</div>
						</div>
					</div>
					<div class="images-container">
						<div class="images-carousel">
							<? foreach($reviews as $review) : ?>
								<div class="img-container">
									<img<?= !empty($review->image_left_border['src']) ? ' src="'.(brand_host().$review->image_left_border['src']).'"' : ''; ?><?= !empty($review->image_left_border['width']) ? ' width="'.$review->image_left_border['width'].'"' : ''; ?><?= !empty($review->image_left_border['height']) ? ' height="'.$review->image_left_border['height'].'"' : ''; ?><?= !empty($review->image_left_border['srcset']) ? ' srcset="'.$review->image_left_border['srcset'].'"' : ''; ?><?= !empty($review->image_left_border['sizes']) ? ' sizes="'.$review->image_left_border['sizes'].'"' : ''; ?><?= !empty($review->image_left_border['alt']) ? ' alt="'.$review->image_left_border['alt'].'"' : ''; ?><?= !empty($review->image_left_border['classes']) ? ' class="'.implode(' ', $review->image_left_border['classes']).'"' : ''; ?> />
								</div>
							<? endforeach; ?>
						</div>
					</div>
				</div>
			<? endif; ?>
		</div>
	</div>
</section>
