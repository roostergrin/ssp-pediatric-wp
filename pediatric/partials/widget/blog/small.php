<?
$classes = $classes ?? [];
?>
<article class="blog-post small<?= !empty($classes) ? ' '.implode(' ', $classes) : '' ?>">
	<? if (!empty($image)) : ?>
		<div class="img-container">
			<a<?= !empty($content['cta']['href']) ? ' href="'.$content['cta']['href'].'"' : ''; ?>><?= $image; ?></a>
		</div>
	<? endif; ?>
	<? if (!empty($content)) : ?>
		<div class="content">
			<? if (!empty($content['h3'])) : ?>
				<h3<?= !empty($content['h3_classes']) ? ' class="'.implode(' ', $content['h3_classes']).'"' : ''; ?>><a<?= !empty($content['cta']['href']) ? ' href="'.$content['cta']['href'].'"' : ''; ?> class="white"><?= $content['h3']; ?></a></h3>
			<? endif; ?>
			<? if (!empty($content['copy'])) : ?>
				<?= apply_filters('the_content', $content['copy']); ?>
			<? endif; ?>
			<? if (!empty($content['cta'])) : ?>
				<a<?= !empty($content['cta']['href']) ? ' href="'.$content['cta']['href'].'"' : ''; ?><?= !empty($content['cta']['classes']) ? ' class="'.implode(' ', $content['cta']['classes']).'"' : ''; ?>><?= $content['cta']['text']; ?></a>
			<? endif; ?>
		</div>
	<? endif; ?>
</article>
