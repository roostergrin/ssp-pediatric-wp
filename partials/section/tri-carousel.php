<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('internal-tri-carousel');
?>
<section class="tri-carousel<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
	<? if (!empty($images)) : ?> 
		<div class="container">
			<div class="tri-carousel owl-carousel">
				<? foreach ($images as $image) : ?>
					<div class="slide-container">
						<div class="img-container">
							<img<?= !empty($image['src']) ? ' src="'.$image['src'].'"' : ''; ?><?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['data-label']) ? ' data-label="'.$image['data-label'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?> />
						</div>
						<? if (!empty($image['content'])) : ?>
							<div class="content<?= !empty($image['content_classes']) ? ' class="'.implode(' ', $image['content_classes']).'"' : ''; ?>">
								<?= $image['content']; ?>
							</div>
						<? endif; ?>
					</div>
				<? endforeach; ?>
			</div>
			<div class="pagination-container">
				<div class="pagination">
					<div class="page-left"><span>Previous</span><i class="icon-left-arrow tri-carousel"></i></div>
					<div class="page-right"><i class="icon-right-arrow tri-carousel"></i><span>Next</span></div>
				</div>
			</div>
		</div>
	<? endif; ?>
</section>
