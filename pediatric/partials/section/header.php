<?
global $wp, $providers, $locations, $wp_post_types;

$relative_url = $wp->request;
$brand = is_brand();
$location = is_location();
$all_providers = get_providers();

$phone = !empty($location) ? $location->phone : get_locations_for_brand($brand->ID);
?>
<section class="header">
	<div class="content">
		<div class="inner-content">
			<div id="header-logo">
				<a class="block" href="<?= brand_url('/', $brand) ?>"><img src="<?= wp_get_attachment_image_src($brand->logo_desktop)[0]; ?>" alt="<?= get_post_meta($brand->logo_desktop, '_wp_attachment_image_alt', true); ?>" title="<?= get_post_meta($brand->logo_desktop, '_wp_attachment_image_alt', true); ?>" width="134" height="60" /></a>
			</div>
			<div id="header-primary-navigation">
				<? if (!ppc_slugs($relative_url)) : ?>
					<ul class="primary-nav">
						<? if (!empty($all_providers)) : ?>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-367<? if (
								strstr($relative_url, 'pediatric-dental-team')
							) echo ' current-menu-ancestor'; ?>"><a href="#">Our team<span class="dropdown mobile icon-plus" aria-disabled="true"></span><div class="hover-bar"></div></a>
								<ul class="sub-menu">
									<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-331"><a href="<?= provider_url('pediatric-dental-team/', $relative_url); ?>">Meet the team</a></li>
									<? foreach ($all_providers as $k => $v): ?>
										<li class="mobile-column menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= provider_url(get_relative_pediatric_provider_url($v->relative_url).'/', $relative_url); ?>"><?= $v->caption['name']; ?></a></li>
									<? endforeach ?>
								</ul>
							</li>
						<? endif; ?>
						<li id="menu-item-366" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-child-free menu-item-366">
                            <a href="<?= brand_url('dentist-office-for-kids', $brand) ?>">Our approach</a><div class="hover-bar"></div>
						</li>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-368">
                            <a href="#">Services<span class="dropdown mobile icon-plus" aria-disabled="true"></span><div class="hover-bar"></div></a>
                            <ul class="sub-menu">
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('pediatric-dental-services/') ?>">All services</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('kids-dental-exams/') ?>">Dental exams</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page">
									<? /* SIM4KIDS different pediatric nav service title */ ?>
									<? if($brand->ID == 18088): ?>
									<a href="<?= _location_based_url('kids-fluoride-treatments/') ?>">Preventative treatments</a>
									<? else: ?>
									<a href="<?= _location_based_url('kids-fluoride-treatments/') ?>">Dental sealants</a>
									<? endif; ?>
								</li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('kids-dental-fillings-crowns/') ?>">Restorative treatments</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('emergency-kids-dentist/') ?>">Dental emergencies</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('kids-custom-mouthguard/') ?>">Custom mouthguards</a></li>
								<? if($brand->ID == 18088): ?>
								<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('frenectomy/') ?>">Frenectomies</a></li>
								<? endif; ?>
                            </ul>
                        </li>
                        <li class="menu-item menu-item-has-children">
                            <a href="#">Your visit<span class="dropdown mobile icon-plus" aria-disabled="true"></span><div class="hover-bar"></div></a>
                            <ul class="sub-menu">
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('kids-dental-care-visits/') ?>">Preparing for your visit</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('financing/') ?>">Payment options</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('patient-forms/') ?>">Forms</a></li>
                            </ul>
                        </li>
						<li id="menu-item-295" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-295">
                            <a class="cta orange" href="<?= _location_based_url('kids-dentist-appointment/') ?>">Appointments</a>
                        </li>
					</ul>
				<? else : ?>
					<ul class="primary-nav-ppc">
						<li><a href="tel:+1<?= ppc_fallback_tel($relative_url); ?>" class="inherit"><i class="icon-phone"></i> <span><?= ppc_fallback_tel($relative_url, 'dot'); ?></span></a></li>
					</ul>
				<? endif; ?>
			</div>
		</div>
	</div>
</section>
<? if (!ppc_slugs($relative_url)) : ?>
	<section class="header-utility">
		<div class="content">
			<div class="inner-content">
				<div id="header-location-tagline-utility-navigation">					
					<?if(!is_single_location_brand()) :?>						
						<? if ($location): ?><div id="header-location-tagline"><?= $location->post_title; ?><i class="icon-list-triangle mobile"></i></div><? endif; ?>
						<div id="header-utility-navigation">
							<ul>
								<li><a href="<?= brand_url('pediatric-dentist', $brand);?>"><?= empty($location) ? 'Find a location' : 'View more locations'; ?></a></li>
								<li><a target="_blank" href="https://pay.yourdentistoffice.com/">Pay online</a></li>
								<li><a href="<?= brand_url('patient-forms', $brand); ?>">Patient forms</a></li>
							</ul>
						</div>
						<? if (empty($location)) : ?>
							<div id="header-mobile-utility-navigation" class="mobile">
								<ul>
									<li><a class="brand-mobile-find-location" href="<?= brand_url('pediatric-dentist'); ?>">Find a location</a></li>
								</ul>
							</div>
						<? else: ?>
							<div id="header-mobile-utility-navigation-more-locations" class="mobile">
								<ul>
									<li><a href="<?= brand_url('pediatric-dentist'); ?>">View more locations</a></li>
								</ul>
							</div>
						<? endif; ?>
					<? endif; ?>
				</div>
				<? if (empty($location)) :?>									
					<div id="header-location-contact">
						<ul>
                            <? 
                            if($brand->ID == 18088): ?>
                                <li><a href="tel:+1<?= toTouchTone($brand->corporate_phone); ?>" class="inherit"><?= hyphenatePhoneNumber($brand->corporate_phone); ?></a></li>
                            <? else: ?>
                            <? foreach($phone as $p) : ?>
                                <li><a href="tel:+1<?= toTouchTone($p->phone); ?>" class="inherit"><span class="location-name"><?= $p->post_title; ?></span><span class="location-num"><?= hyphenatePhoneNumber($p->phone); ?></span><i class="icon-cellphone"></i></a></li>
                                <? endforeach; ?>
                            <? endif; ?>
						</ul>
					</div>
				<? elseif( !empty($phone) ) :?>
					<div id="header-location-contact">
						<ul>
							<li><a href="tel:+1<?= toTouchTone($phone); ?>" class="inherit"><span><?= hyphenatePhoneNumber($phone); ?></span></a></li>
						</ul>
					</div>
				<? else :?>
					<div id="header-location-contact" >
						<ul>
							<li><?= do_shortcode('[appointments_link text="Free consultation" class="text menu-min-show"]')?></li>
						</ul>
					</div>
				<? endif ;?>
			</div>
		</div>
	</section>
<? endif; ?>
