<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('icons-reveal-text');
?>
<section class="icons-reveal-text">
    <div class="slides owl-carousel">
        <? foreach($slides as $slide) :?>
        <div class="slide <?= $slide['color'];?>">
            <div class="icon"><i class="widget static icon-<?= $slide['icon'];?>"></i></div>
            <div class="heading"><?= $slide['heading'];?></div>
            <div class="copy"><?= $slide['copy'];?></div>
        </div>
        <? endforeach; ?>
    </div>
</section>