
<?
	wp_enqueue_script('locations-grid'); 
	$brand = is_brand();		
?>
<section class="locations-grid">
	<div class="content">
		<div class="inner-content">
			<? if (!empty($providers) || true) : ?>
				<div class="container">
					<h1 class="white h2"><?= !empty($heading) ? $heading : 'Personalized service and treatment at our convenient locations'; ?></h1>
					<div class="locations-list num-<?= count($locations);?>">
						<? foreach ($locations as $location) : 
							$doctors = $location->doctors;							
							$doctors = array_reverse( $doctors );
							$address = '';
							
							if (!empty($location->address) && !empty($location->address_line_2)) {
								$address = $location->address . '<br>' . $location->address_line_2 . '<br>' . $location->city . ', ' . $location->state . ' ' . $location->zip;
							} else {
								$address = $location->address . '<br>' . $location->city . ', ' . $location->state . ' ' . $location->zip;
							}
						?>
							<article class="location-tile">
								<div class="img-container">
									<img<?= !empty($location->image['src']) ? ' src="'.(brand_host().'/'.$location->image['src']).'"' : ''; ?><?= !empty($location->image['width']) ? ' width="'.($location->image['width']).'"' : ''; ?><?= !empty($location->image['height']) ? ' height="'.($location->image['height']).'"' : ''; ?><?= !empty($location->image['data-label']) ? ' data-label="'.($location->image['data-label']).'"' : ''; ?><?= !empty($location->image['alt']) ? ' alt="'.($location->image['alt']).'"' : ''; ?><?= !empty($location->image['classes']) ? ' class="'.(implode(' ', $location->image['classes'])).'"' : ''; ?> />
								</div>
								<div class="location-content">
									<div class="col col-left">
										<? if(!empty($location->post_title)) : ?>
											<div class="heading">
												<h3 class="h3"><a href="<?= brand_url('/'.get_relative_pediatric_location_url($location->relative_url).'/', $brand); ?>"><?= $location->post_title; ?></a></h3>
											</div>
										<? endif; ?>
										<? if(!empty($location->address)) : ?>
											<div class="address">
												<address><?= $address; ?></address>
											</div>
										<? endif; ?>
										<? if(!empty($doctors)) : ?>
											<ul>
												<? foreach ($doctors as $doctor) { ?>
													<li><?= $doctor; ?></li>
												<? } ?>
											</ul>
										<? endif; ?>
									</div>
									<div class="col col-right">
										<? $directions_link = !empty($location->google_cid) ? 'https://www.google.com/maps/?cid='.($location->google_cid) : $location->directions_link; ?>
										<? if( !empty($directions_link) ) : ?>											
											<a target="_blank" href="<?= $directions_link; ?>" class="cta green">Get directions</a>
										<? endif; ?>
										<? if( !empty($location->schedule_consultation_link) ) : ?>
											<a href="<?= brand_url('/pediatric-dentist/'.($location->post_name).'/kids-dentist-appointment/'); ?>" class="cta green" title="Schedule appointment">Schedule appointment</a>
										<? endif; ?>
										<?
										/**
										 * Check for SIM4KIDS brand if each office location has a virtual tour 
										 */
										?>
										<? 
											if( $brand->ID == 18088 ) {
												$location_children = get_children( $location->ID );
												$location_virtual_tour_page = 0;												

												foreach ($location_children as $key => $child) {
													if( strpos($child->post_name, 'virtual-tour') !== false ) {
														$location_virtual_tour_page = $child->ID;
														break;
													}
												}												

												if( !empty( get_field( 'virtual_tour_embed_source', $location_virtual_tour_page ) ) ): ?>
													<a href="<?= brand_url('/pediatric-dentist/'.($location->post_name).'/virtual-tour/'); ?>" class="cta green">Virtual tour</a>
												<? endif;
											}
										?>										
										<? if(!empty($location->email_address)) : ?>
											<a href="<?= brand_url('/pediatric-dentist/'.($location->post_name).'/contact-us/'); ?>" class="cta green" title="Email us">Email us</a>
										<? endif; ?>
										<? if( !empty($location->phone) || !empty($location->toll_free_phone)) : 
											$phone = !empty($location->toll_free_phone) ? $location->toll_free_phone : $location->phone;
										?>
											<a href="tel:+1<?= toTouchTone($phone); ?>" class="cta green">Call <?= hyphenatePhoneNumber($phone); ?></a></li>
										<? endif; ?>
									</div>
								</div>
							</article>
						<? endforeach; ?>
					</div>
				</div>
			<? endif; ?>
		</div>
	</div>
</section>
