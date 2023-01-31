<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('providers-tri-carousel');
?>
<section class="providers-tri-carousel<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
	<? if (!empty($providers)) : ?>
		<div class="container">
			<? if(!empty($heading)) :?>
				<div class="heading-container">
					<h2><?= $heading; ?></h2>
				</div>
			<? endif; ?>
			<div class="providers owl-carousel">
				<? foreach ($providers as $provider) : ?>
					<div class="img-container">
						<? if (!empty($provider->image)) : ?>
							<img<?= !empty($provider->image['src']) ? ' src="'.(brand_host().$provider->image['src']).'"' : ''; ?><?= !empty($provider->image['width']) ? ' width="'.$provider->image['width'].'"' : ''; ?><?= !empty($provider->image['height']) ? ' height="'.$provider->image['height'].'"' : ''; ?><?= !empty($provider->image['data-label']) ? ' data-label="'.$provider->image['data-label'].'"' : ''; ?><?= !empty($provider->image['alt']) ? ' alt="'.$provider->image['alt'].'"' : ''; ?><?= !empty($provider->image['classes']) ? ' class="'.implode(' ', $provider->image['classes']).'"' : ''; ?> loading="lazy" />
						<? endif; ?>
						<? if (!empty($provider->mobile_image)) : ?>
							<img<?= !empty($provider->mobile_image['src']) ? ' src="'.(brand_host().$provider->mobile_image['src']).'"' : ''; ?><?= !empty($provider->mobile_image['width']) ? ' width="'.$provider->mobile_image['width'].'"' : ''; ?><?= !empty($provider->mobile_image['height']) ? ' height="'.$provider->mobile_image['height'].'"' : ''; ?><?= !empty($provider->mobile_image['data-label']) ? ' data-label="'.$provider->mobile_image['data-label'].'"' : ''; ?><?= !empty($provider->mobile_image['alt']) ? ' alt="'.$provider->mobile_image['alt'].'"' : ''; ?><?= !empty($provider->mobile_image['classes']) ? ' class="'.implode(' ', $provider->mobile_image['classes']).'"' : ''; ?> loading="lazy" />
						<? endif; ?>
						<div class="overlay">
							<p class="name"><a href="<?= brand_url('/'.get_relative_pediatric_provider_url($provider->relative_url).'/'); ?>"><?= $provider->caption['name']; ?></a></p>
							<p class="specialty desktop"><?= $provider->caption['specialty']; ?></p>
							<p class="about desktop"><?= $provider->caption['content']; ?></p>
							<a href="<?= brand_url('/'.get_relative_pediatric_provider_url($provider->relative_url).'/'); ?>" class="cta text link">View doctor bio</a>
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
	<? endif; ?>
</section>
