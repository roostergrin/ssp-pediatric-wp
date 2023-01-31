<?php
$brand = is_brand();

$button_color = (sanitize_title($brand->palette) == 'smiles-in-motion' ? 'white-trans' : 'green');
?>

<div class="widget search content active">
	<div class="inner-content">
		<div class="container active <? if(sanitize_title($brand->palette) == 'smiles-in-motion') echo 'orange'; ?>">
			<div class="no-overflow">
				<h3 class="heading">Find a location near you <i class="icon-search heading"></i></h3>
				<div class="office-results"></div>
				<div id="find-location">
					<form method="POST" id="form_location_autocomplete" class="form_location_search" autocomplete="off" novalidate>
						<input type="hidden" name="_gcla" value="">
						<input type="hidden" name="_gclo" value="">
						<input type="hidden" name="_gcer" value="">
						<input type="text" name="location" id="location_search_address" placeholder="Enter city or zip code" required="required">

						<button type="submit" class="cta white hover-<?= $button_color; ?> icon-search submit-location" id="location_search" > Search </button>
					</form>
					<a class="current-location" href="#">
						<i class="icon-location-pin"></i> Use my current location
					</a>
				</div>
			</div>
		</div>
	</div>
</div>