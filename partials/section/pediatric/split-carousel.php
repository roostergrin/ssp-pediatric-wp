<?
wp_enqueue_script('split-carousel');
?>
<section class="split-carousel<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
	<div class="content">
		<? if (!empty($slides)) : ?>
			<div class="main-container">
                <div class="images-container">
				<? if(!empty($heading)) : ?>
						<h2 class="menu-min-show<?= !empty($heading_classes) ? ' ' . implode(' ', $heading_classes) : '';?>"><?= $heading; ?></h2>
					<? endif; ?>
					<div class="images-carousel">
						<? foreach($slides as $key => $slide) : ?>
							<div class="img-container">
								<img<?= !empty($slide['image']['src']) ? ' src="'.$slide['image']['src'].'"' : ''; ?><?= !empty($slide['image']['width']) ? ' width="'.$slide['image']['width'].'"' : ''; ?><?= !empty($slide['image']['height']) ? ' height="'.$slide['image']['height'].'"' : ''; ?><?= !empty($slide['image']['srcset']) ? ' srcset="'.$slide['image']['srcset'].'"' : ''; ?><?= !empty($slide['image']['sizes']) ? ' sizes="'.$slide['image']['sizes'].'"' : ''; ?><?= !empty($slide['image']['alt']) ? ' alt="'.$slide['image']['alt'].'"' : ''; ?><?= !empty($slide['image']['classes']) ? ' class="'.implode(' ', $slide['image']['classes']).'"' : ''; ?> />
							</div>
						<? endforeach; ?>
					</div>
				</div>
				<div class="content-container">
					<? if(!empty($heading)) : ?>
						<h2 class="menu-max-hide<?= !empty($heading_classes) ? ' ' . implode(' ', $heading_classes) : '';?>"><?= $heading; ?></h2>
					<? endif; ?>
					<div class="content-carousel">
						<? foreach($slides as $key => $slide): ?>
							<div class="carousel-item">
								<div class="content <?= !empty($heading) ? 'mt-0' : '' ?>">
									<?php if($is_questions): ?>
                                    <h2 class="h4 question-heading">Questions we commonly hear</h2>
									<?php endif; ?>
									<h3 class="heading"><?= $slide['heading']; ?></h3>
									<div class="copy"><?= $slide['copy'] ?></div>
								</div>
							</div>
						<? endforeach; ?>
					</div>
					<div class="pagination-container">
						<div class="pagination">
						<div class="page-left"><span>Previous</span><i class="icon-left-arrow providers"></i></div>
						<div class="page-right"><i class="icon-right-arrow providers"></i><span>Next</span></div>
						</div>
					</div>
				</div>
			</div>
		<? endif; ?>
	</div>
</section>
