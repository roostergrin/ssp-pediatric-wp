<?
global $wp;

$brand = is_brand();
$relative_url = get_relative_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$location = is_location();
$brand_locations = get_locations_for_brand($brand->ID);
usort($brand_locations, function($a, $b) {
	return $a->post_title <=> $b->post_title;
});
if(empty($location)){
    $facebook_link = $brand->facebook_link;
} else {
    $facebook_link = $location->facebook_link;
}
$instagram_link = $brand->instagram_link;
?>
<section class="footer <?=sanitize_title($brand->post_title)?>">
	<div class="content">
		<div class="inner-content">
			<div class="main-container">
				<? 
				if(!strstr($relative_url, 'book-appointment') && !strstr($relative_url, 'patient-forms') && !strstr($relative_url, 'kids-dentist-appointment') && !strstr($relative_url, 'pediatric-dentist-referral')) {
					partial('widget.schedule-consultation', [
						'widget_classes' => ($brand->ID == 18088 ? ['smiles-in-motion'] : '') // Smiles in Motion
					]); 
				}
				
				?>
				<? if(!ppc_slugs($relative_url)) : ?>
				<div id="footer-navigation">
					<div class="nav-container">
						<div class="all-locations">
							<ul id="locations">
								<? foreach($brand_locations as $brand_location) : ?>
									<li><a href="<?= brand_url('/'.get_relative_pediatric_location_url($brand_location->relative_url).'/'); ?>"><?= $brand_location->post_title; ?></a></li>
								<? endforeach; ?>
							</ul>
							<ul class="main-nav">
									<li><a href="<?= brand_url('/contact-us/', $brand); ?>">Contact</a></li>
									<? if (!empty($facebook_link) || !empty($instagram_link)) : ?>
                                    	<li class="social-navigation">
                                    		<? if (!empty($facebook_link)) : ?>
                                    			<a href="<?= $facebook_link; ?>" target="_blank">
													<?php
													$fb_src = content_url() . '/uploads/2022/01/facebook_icon.png';
													if(sanitize_title($brand->palette) == 'smiles-in-motion') $fb_src = get_stylesheet_directory_uri(). '/images/icons/Facebook_FooterIcon.svg'; // Smiles in Motion
													?>
                                                    <img src="<?= $fb_src; ?>" alt="facebook icon" width="20px">
                                                </a>
                                    		<? endif; ?>
                                    		<? if (!empty($instagram_link)) : ?>
                                    			<a href="<?= $instagram_link; ?>" target="_blank">
													<?php
													$ig_src = content_url() . '/uploads/2022/01/instagram_icon2.png';
													if(sanitize_title($brand->palette) == 'smiles-in-motion') $ig_src = get_stylesheet_directory_uri(). '/images/icons/Instagram_FooterIcon.svg'; // Smiles in Motion
													?>
                                                    <img src="<?= $ig_src; ?>" alt="instagram icon" width="20px">
												</a>
                                    		<? endif; ?>
                                    	</li>
                                    <? endif; ?>
								</ul>
						</div>
						<div class="main-footer-navigation">
							<? if (!empty($location)): ?>
								<ul class="main-info">
									<li><a href="<?= blog_url('kids-dentist-blog/', $brand); ?>">Blog</a></li>
									<li><a href="<?= brand_url('/pediatric-dentist-referral/', $brand); ?>">Doctor referral</a></li>
									<li><a href="<?= brand_url('/careers/', $brand); ?>">Careers</a></li>
								</ul>
							<? else:?>
								<ul class="main-info">
									<li><a href="<?= blog_url('kids-dentist-blog/', $brand); ?>">Blog</a></li>
									<li><a href="<?= brand_url('/pediatric-dentist-referral/', $brand); ?>">Doctor referral</a></li>
									<li><a href="<?= brand_url('/careers/', $brand); ?>">Careers</a></li>
								</ul>
							<? endif ?>
						</div>
					</div>
				</div>
				<? endif; ?>
				<div class="bottom-row">
					<div id="footer-copyright-utility-navigation">
						<div id="footer-copyright">&copy; <?= date('Y'); ?> <?= $brand->corporate_name; ?>. All rights reserved.</div>
						<? if(!ppc_slugs($relative_url)) : ?>
						<div>
							<ul id="footer-utility-navigation">
                                <li><a href="/nondiscrimination/">Non-discrimination</a></li>
								<li><a href="/privacy-policy/">Privacy policy</a></li>
								<li><a href="<?= brand_url('/site-map/', $brand) ?>">Site map</a></li>
							</ul>
						</div>
						<? endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
