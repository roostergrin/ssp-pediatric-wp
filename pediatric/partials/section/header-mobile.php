<?
global $wp, $providers, $locations;

$relative_url = $wp->request;
$brand = is_brand();
$all_providers = get_providers();

?>
<section class="mobile-header">
	<? if( !ppc_slugs($relative_url)):?>
		<div id="mobile-navigation" class="mobile-nav">
			<ul class="primary-nav">
                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-330"><a class="cta primary" href="<?= brand_url('kids-dentist-appointment', $brand) ?>">Appointments</a></li>
				<? if (false) wp_nav_menu(['items_wrap' => '%3$s', 'theme_location' => 'mobile', 'container' => false, 'walker' => new ADA_Mobile]); ?>
				<? if (!empty($all_providers)): ?>
				<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-367"><a href="#">Our team<span class="dropdown mobile icon-close" aria-disabled="true"></span></a>
					<ul class="sub-menu">
						<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-331"><a href="<?= brand_url('pediatric-dental-team', $brand) ?>">Meet the team</a></li>
						<? foreach ($all_providers as $k => $v): ?>
							<li class="mobile-column menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('/'.get_relative_pediatric_provider_url($v->relative_url).'/', $brand); ?>"><?= $v->caption['name']; ?></a></li>
						<? endforeach ?>
					</ul>
				</li>
				<? endif ?>
				<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-369"><a href="<?= brand_url('dentist-office-for-kids', $brand) ?>">Our approach<span class="mobile" aria-disabled="true"></span></a>
				</li>
                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-368"><a href="#">Services<span class="dropdown mobile icon-close" aria-disabled="true"></span></a>
                    <ul class="sub-menu">
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('pediatric-dental-services', $brand) ?>">All services</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('kids-dental-exams', $brand) ?>">Dental exams</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page">
							<? /* SIM4KIDS different pediatric nav service title */ ?>
							<? if($brand->ID == 18088): ?>
							<a href="<?= _location_based_url('kids-fluoride-treatments/') ?>">Preventative treatments</a>
							<? else: ?>
							<a href="<?= _location_based_url('kids-fluoride-treatments/') ?>">Dental sealants</a>
							<? endif; ?>
						</li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('kids-dental-fillings-crowns', $brand) ?>">Restorative treatments</a></li>
						<? if($brand->ID == 18088): ?>
							<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= _location_based_url('frenectomy/') ?>">Frenectomies</a></li>
						<? endif; ?>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('emergency-kids-dentist', $brand) ?>">Dental emergencies</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('kids-custom-mouthguard', $brand) ?>">Custom mouthguards</a></li>                        
                    </ul>
                </li>
				<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-340"><a href="#">Your visit<span class="dropdown mobile icon-close" aria-disabled="true"></span></a>
					<ul class="sub-menu">
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('kids-dental-care-visits', $brand) ?>">Preparing for your visit</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('accepted-payment', $brand) ?>">Payment options</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= brand_url('patient-forms', $brand) ?>">Forms</a></li>
					</ul>
				</li>
			</ul>
		</div>
	<? endif; ?>
	<div id="mobile-header">
		<? if(true) :?>
			<div class="mobile-menu">
				<ul>
					<li>
						<a class="btn menu">
							<button class="hamburger--squeeze" type="button">
								<span class="hamburger-box">
									<span class="hamburger-inner"></span>
								</span>
							</button>
						</a>
					</li>
				</ul>
			</div>
		<? else : ?>
			<div id="mobile-header-ppc">
				<ul>
					<li><a href="tel:+1<?= ppc_fallback_tel($relative_url); ?>" class="inherit"><i class="icon-phone"></i> <span><?= ppc_fallback_tel($relative_url, 'dot'); ?></span></a></li>
				</ul>
			</div>
		<? endif; ?>
	</div>
</section>
