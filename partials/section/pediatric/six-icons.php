<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('six-icons-carousel');
?>

<section class="six-icons<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <? if(!empty($heading) || !empty($heading_classes)) : ?>
                <h2<?= $heading_classes ? ' class="' . implode(' ', $heading_classes) . '"' : '';?>><?= $heading; ?></h2>
            <? endif; ?>
            <div class="icons-container six-icons-carousel">
                <? foreach($icons as $icon) :?>
                    <div class="icon-container">
                        <div class="img-container">
                            <?= partial('widget.icons-hover.'.$icon['img']); ?>
                        </div>
                        <div class="copy-container">
                            <?= apply_filters('the_content', $icon['copy']); ?>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</section>