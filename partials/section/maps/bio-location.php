<?
global $locations;
$selected_location = $selected_location ?? 6;
$provider_locations_ids = unserialize($provider->location_relationship);
$provider_locations = [];

if(!empty($provider_locations_ids)) {
    $provider_locations = array_filter($locations->locations, function($location) use ($provider_locations_ids) {
        return in_array($location->ID, $provider_locations_ids);
    });
} 

usort($provider_locations, function($a, $b) {
	return $a->post_title <=> $b->post_title;
});

function prettyTel($num) {
	return preg_replace("/(\d{3})(\d{3})(\d{4})/", "\\1.\\2.\\3", $num);
}
?>

<section class="maps location<?= !empty($classes) ? ' ' . implode(' ', $classes) : ''?>">
	<form><input type="hidden" id="location_search_address" value=""></form>
	
    <? partial('widget.maps.initial', ['selected_location' => $selected_location, 'all_locations' => $provider_locations]); ?>
    
    <? if( !empty( $provider_locations ) ): ?>
    <div class="content">
        <div class="inner-content<? if (count($provider_locations) === 1) echo ' single-location' ?><? if (count($provider_locations) === 2) echo ' dual-location' ?>">
            <? if(!empty($info_box_copy)): ?>
                <div class="info-container">
                    <div class="content">
                        <h3><?= $info_box_copy;?></h3>
                    </div>
                </div>
                <? if(!empty($provider_locations) && count($provider_locations) > 1):?>
                <div class="locations-data">
                    <? foreach($provider_locations as $l): ?>
                        <div class="column">
                            <h4><?= $l->post_title; ?></h4>
                            <a href="<?= brand_url('/'.(get_relative_pediatric_location_url($l->relative_url)).'/'); ?>" class="cta secondary">Learn more</a>
                            <? if(!empty($l->phone)): ?>
                                <a href="tel:+<?= $l->phone; ?>" class="cta secondary">Call <?= prettyTel($l->phone); ?></a><br>
                            <? elseif(!empty($l->toll_free_phone)): ?>
                                <a href="tel:+1<?= $l->toll_free_phone; ?>" class="cta secondary">Call <?= prettyTel($l->toll_free_phone); ?></a><br>
                            <? endif; ?>

                        </div>
                    <? endforeach; ?>
                </div>
                <? endif; ?>
                <? if (count($provider_locations) === 1): ?>
                <div class="locations-data">
                    <? foreach($provider_locations as $l): ?>
                        <div class="column">
                            <h4><?= $l->post_title; ?></h4>
                            <a href="<?= brand_url('/'.(get_relative_pediatric_location_url($l->relative_url)).'/'); ?>" class="cta secondary">Learn more</a>
                            <? if(!empty($l->phone)): ?>
                                <a href="tel:+<?= $l->phone; ?>" class="cta secondary">Call <?= prettyTel($l->phone); ?></a><br>
                            <? elseif(!empty($l->toll_free_phone)): ?>
                                <a href="tel:+1<?= $l->toll_free_phone; ?>" class="cta secondary">Call <?= prettyTel($l->toll_free_phone); ?></a><br>
                            <? endif; ?>
                        </div>
                    <? endforeach; ?>
                </div>
                <? endif ?>
            <? endif; ?>
        </div>
    </div>
    <? endif; ?>
</section>
