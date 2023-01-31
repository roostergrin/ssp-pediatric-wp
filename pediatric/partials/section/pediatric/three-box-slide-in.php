<?
wp_enqueue_script('three-box-slide-in');
?>
<section class="three-box-slide-in<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <? if($heading) :?>
            <h2><?= $heading; ?></h2>
            <? endif; ?>
            <div class="box-container">
                <? foreach($boxes as $box) :?>
                <div class="box <? if( !empty($box_classes) ) echo implode(' ', $box_classes); ?>">
                    <h3><?= $box['heading']?></h3>
                    <?= apply_filters('the_content', $box['content']);?>
                </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</section>