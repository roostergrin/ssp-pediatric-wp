<? if (empty($image) && empty($mobile_image)) return; ?>
<section class="hero full<?= !empty($classes) ? ' '.implode(' ', $classes) : ''; ?>"<?= !empty($background_image) ? ' style="background-image: url('.($background_image).');"' : ''; ?>>
	<? if (!empty($image)) : ?>
		<div class="img-container desktop">
			<img<?= !empty($image['src']) ? ' src="'.$image['src'].'"' : ''; ?><?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?> <?= !empty($image['object_position']) ? ' style="object-position:'.$image['object_position'].'"' : ''; ?> loading="lazy" />
			<? if (!empty($overlay)) : ?>
				<div class="overlay"></div>
			<? endif; ?>
		</div>
	<? endif; ?>
	<? if (!empty($mobile_image)) : ?>
		<div class="img-container<?= !empty($mobile_image['classes']) && in_array('mobile', $mobile_image['classes']) ? ' mobile' : ''; ?>">
			<img<?= !empty($mobile_image['src']) ? ' src="'.$mobile_image['src'].'"' : ''; ?><?= !empty($mobile_image['width']) ? ' width="'.$mobile_image['width'].'"' : ''; ?><?= !empty($mobile_image['height']) ? ' height="'.$mobile_image['height'].'"' : ''; ?><?= !empty($mobile_image['srcset']) ? ' srcset="'.$mobile_image['srcset'].'"' : ''; ?><?= !empty($mobile_image['sizes']) ? ' sizes="'.$mobile_image['sizes'].'"' : ''; ?><?= !empty($mobile_image['alt']) ? ' alt="'.$mobile_image['alt'].'"' : ''; ?><?= !empty($mobile_image['classes']) ? ' class="'.implode(' ', $mobile_image['classes']).'"' : ''; ?> loading="lazy" />
			<? if (!empty($overlay)) : ?>
				<div class="overlay"></div>
			<? endif; ?>
		</div>
	<? endif; ?>
	<? if (!empty($content)) : ?>
		<div class="content">
			<div class="inner-content">
				<div class="content-container<?= !empty($content["classes"]) ? ' '.implode(" ", $content["classes"]) : ''; ?>">
					<? if (!empty($content['h2'])) : ?>
						<h2<?= !empty($content['h2_classes']) ? ' class="'.implode(' ', $content['h2_classes']).'"' : ''; ?>><?= $content['h2']; ?></h2>
					<? endif; ?>
                    <? if (!empty($content['h3'])) : ?>
						<h3<?= !empty($content['h3_classes']) ? ' class="'.implode(' ', $content['h3_classes']).'"' : ''; ?>><?= $content['h3']; ?></h3>
					<? endif; ?>
					<? if (!empty($content['text'])) : ?>
						<h4><?= $content['text']; ?></h4>
					<? endif; ?>
					<? if (!empty($content['content'])) : ?>
						<?= apply_filters('the_content', $content['content']); ?>
					<? endif; ?>
					<? if (!empty($content['cta'])) : ?>
						<a<?= !empty($content['cta']['href']) ? ' href="'.$content['cta']['href'].'"' : ''; ?><?= !empty($content['cta']['classes']) ? ' class="'.implode(' ', $content['cta']['classes']).'"' : ''; ?>><?= $content['cta']['text']; ?></a>
					<? endif; ?>
					<? if (!empty($content['shortcode'])) : ?>
						<?= apply_filters('the_content', $content['shortcode']); ?>
					<? endif; ?>
				</div>
			</div>
		</div>
	<? endif; ?>
</section>
