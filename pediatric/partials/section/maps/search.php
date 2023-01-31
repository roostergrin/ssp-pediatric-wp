<? $location = is_location(); ?>
<section class="maps search">
<?
	if (empty($location)) {
		partial('widget.maps.search');
		partial('widget.maps.initial');
	} else {
		partial('widget.maps.single');
	}
?>
</section>
