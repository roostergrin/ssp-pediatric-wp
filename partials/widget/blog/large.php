<article class="blog-post large">
	<? if (!empty($image)) : ?>
		<div class="img-container">
			<a<?= !empty($content['cta']['href']) ? ' href="'.$content['cta']['href'].'"' : ''; ?>><?= $image; ?></a>
		</div>
	<? endif; ?>
	<? if (!empty($content)) : ?>
		<div class="content">
			<div class="copy">
				<? if (!empty($content['h2'])) : ?>
					<h2<?= !empty($content['h2_classes']) ? ' class="'.implode(' ', $content['h2_classes']).'"' : ''; ?>><a<?= !empty($content['cta']['href']) ? ' href="'.$content['cta']['href'].'"' : ''; ?> class="white"><?= $content['h2']; ?></a></h2>
				<? endif; ?>
				<? if (!empty($content['copy'])) : ?>
					<?= apply_filters('the_content', $content['copy']); ?>
				<? endif; ?>
				<? if (!empty($content['cta'])) : ?>
					<a<?= !empty($content['cta']['href']) ? ' href="' . $content['cta']['href'] . '"' : ''; ?><?= !empty($content['cta']['classes']) ? ' class="'.implode(' ', $content['cta']['classes']).'"' : ''; ?>><?= $content['cta']['text']; ?></a>
				<? endif; ?>
			</div>
		</div>
	<? endif; ?>
</article>
