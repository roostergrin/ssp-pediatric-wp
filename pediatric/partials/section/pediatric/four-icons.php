<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('four-icons-carousel');
$static_icons = $static_icons ?? false;
?>

<section class="four-icons<?= $classes ? ' ' . implode(' ', $classes) : '';?>" <? if(!empty($section_id)): ?>id="<?= $section_id; ?>"<? endif; ?>>
    <div class="content">
        <div class="inner-content">
	            <? if(!empty($heading)) : ?>
                    <h2<?= $heading_classes ? ' class="' . implode(' ', $heading_classes) . '"' : '';?>><?= $heading; ?></h2>
	            <? endif; ?>
	            <?php if(!empty($copy)): ?>
                    <div class="copy">
			            <?= apply_filters('the_content', $copy); ?>
                    </div>
	            <?php endif; ?>
            <div class="icons-container four-icons-carousel<?= $carousel_container_classes ? ' ' . implode(' ', $carousel_container_classes) : '';?>">
                <? foreach($icons as $icon) :?>
                    <div class="icon-container">
                        <div class="img-container <?= $icon['bg_color'] ? $icon['bg_color'] : '' ; ?>">
                            <?php if($static_icons): ?>
                            <i class="widget static icon-<?= $icon['img']; ?>"></i>
                            <?php else: ?>
	                        <?= partial('widget.icons-hover.'.$icon['img']); ?>
                            <?php endif; ?>
                        </div>
                        <div class="heading-container <?= $icon['bg_color'] ? $icon['bg_color'] : '' ; ?>">
		                    <?= apply_filters('the_content', $icon['heading']); ?>
                        </div>
                        <div class="copy-container <?= $copy_classes ? ' ' . implode(' ', $copy_classes) : '';?>">
                            <?= apply_filters('the_content', $icon['copy']); ?>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</section>
