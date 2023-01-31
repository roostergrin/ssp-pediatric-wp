<section class="hero standard<?= !empty($classes) ? ' '.implode(' ', $classes) : ''; ?>">
	<? if (!empty($image)) : ?>
		<? endif; ?>
		<? if (!empty($h1) || !empty($content)) : ?>
			<div class="content<?= !empty($content_classes) ? ' '.implode(' ', $content_classes) : ''; ?>">
				<img src="<?= $image['src']; ?>" <?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?>/>
				<div class="inner-content">
				<div class="wrapper<?= !empty($wrapper_classes) ? ' '.implode(' ', $wrapper_classes) : ''; ?>">
					<div class="container<?= !empty($container_classes) ? ' '.implode(' ', $container_classes) : ''; ?>">
						<? if (!empty($h1)) : ?>
							<h1<?= !empty($h1_classes) ? ' class="'.implode(' ', $h1_classes).'"' : ''; ?>><?= $h1; ?></h1>
						<? endif; ?>
						<? if (!empty($content)) : ?>
							<?= apply_filters('the_content', $content); ?>
						<? endif; ?>
						<? if (!empty($cta)) : ?>
							<?= apply_filters('the_content', $cta); ?>
						<? endif; ?>
					</div>
				</div>
			</div>
		</div>
		<? endif; ?>
	</section>
