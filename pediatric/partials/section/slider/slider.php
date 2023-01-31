<?
$heading = $heading ?? false;
$htag = $htag ?? 'h2';
$heading_classes = $heading_classes ?? ['primary'];
wp_enqueue_script('image-slider');
?>
<section class="slider<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
	<div class="content">
		<div class="inner-content">
			<? if (!empty($heading) || !empty($static_copy)) : ?>
				<div class="main-container">
				<div class="images-container">
						<div class="images-carousel">
							<? foreach($slides as $slide) : ?>
								<div class="img-container">
									<img src="<?= $slide['src'];?>" alt="<?= $slide['alt'];?>">
								</div>
							<? endforeach; ?>
						</div>
					</div>
					<div class="content-container">
						<? if (!empty($heading)) : ?><<?= $htag ?> class="<?= implode(' ', $heading_classes) ?>"><?= esc_html($heading) ?></<?= $htag ?>><? endif; ?>
                        <?= $static_copy ? $static_copy : '';
                        if(!empty($pagination)) :
                        ?> 
						<div class="pagination-container">
							<div class="pagination">
								<i class="icon-left-arrow-thick"></i>
								<i class="icon-right-arrow-thick"></i>
							</div>
						</div>
                        <? endif;?>
					</div>
				</div>
			<? endif; ?>
		</div>
	</div>
</section>
