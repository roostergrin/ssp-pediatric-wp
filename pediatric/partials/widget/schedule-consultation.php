<?
global $wp;
if ($wp->request === 'pediatric-dentist' || $wp->request === 'orthodontic-office' || is_404()) return;
$template_array = explode('/', get_page_template());
if (in_array(end($template_array), ['careers.php', 'refer-a-friend.php', 'patient-forms.php', 'free-consultations.php', 'emergency-care-and-repair.php', 'share-feedback.php', 'community-involvement.php', 'custom-mouthguard.php', 'book-appointment.php'])) return;
$brand = is_brand();
$location = is_location();
?>
<div class="widget schedule-consultation<?= !empty($widget_classes) ? ' '.implode(' ', $widget_classes) : ''; ?>">
	<h2>Schedule your appointment today!</h2>
	<div class="container<?= empty($location) ? ' brand' : ''; ?>">
		<?
		$phone;
		if( empty($location) ) {
			$phone = null;
		} else {
			$phone = $location->toll_free_phone ? $location->toll_free_phone : $location->phone;
		}
		if(!empty($phone)) :?>
			<a class="cta white" href="tel:+1<?= toTouchTone($phone); ?>">Call <?= hyphenatePhoneNumber($phone); ?></a>
			<?= do_shortcode('[appointments_link text="Schedule online" class="cta openchair-widget"]');
		else:
			echo do_shortcode('[appointments_link text="Schedule online" class="cta openchair-widget"]');
		endif;
		?>
	</div>
</div>

