<?
global $forms;

$location = is_location();
$content = $content ?? get_the_content();
$classes[] = $form;
$staggered_image = (isset($staggered_image) ? $staggered_image : false);

empty($heading['tag']) ? $heading['tag'] = 'h1' : $heading['tag'] = $heading['tag'];
?>
<section class="form<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>" id="form">
	<div class="content">
		<div class="inner-content">
			<article>
				<div class="copy-container">
					<?= !empty($heading) ? '<' . $heading['tag'] .  (!empty($heading['classes']) ? ' class="' . implode(' ', $heading['classes']) . '"' : '') . '>'.($heading['text']).'</' . $heading['tag'] . '>' : ''; ?>
					<?= apply_filters('the_content', $content) ?>
				</div>
				<? if(!empty($image)) :?>
					<? if($staggered_image): ?>
					<div class="cols col-left">
						<div class="img-container">
							<img<?= !empty($image['src']) ? ' src="'.$image['src'].'"' : ''; ?><?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?> />
						</div>	
					</div>
					<? else: ?>
					<div class="img-container">
						<img<?= !empty($image['src']) ? ' src="'.$image['src'].'"' : ''; ?><?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?> />
					</div>
					<? endif; ?>
				<? endif;?>
			</article>
			<aside>
                <div class="form-wrapper">
                    <? $forms->generateForm($form); ?>
                </div>
				<? if(!empty($disclaimer)) : ?>
					<div class="disclaimer">
						<?= $disclaimer; ?>
					</div>
				<? endif; ?>
			</aside>
		</div>
	</div>
</section>
