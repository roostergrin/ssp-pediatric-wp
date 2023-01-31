<?
$brand = is_brand();
$relative_url = get_relative_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$location = is_location();
?>
<section class="footer">
	<div class="content">
		<div class="inner-content">
			<div class="main-container">
				<div class="bottom-row">
					<div id="footer-copyright-utility-navigation">
						<div class="desktop" id="footer-copyright">&copy; <?= date('Y'); ?> <?= $brand->corporate_name; ?>. All rights reserved.</div>
					</div>
				</div>
			</div>
			<div class="mobile" id="footer-copyright-mobile">&copy; <?= date('Y'); ?> <?= $brand->corporate_name; ?>. All rights reserved.</div>
		</div>
	</div>
</section>
