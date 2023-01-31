<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('four-icons-with-image-carousel');
?>

<section class="four-icons-with-image<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <? if($heading) : ?>
                <div class="heading-container">
                    <h2<?= $heading_classes ? ' class="' . implode(' ', $heading_classes) . '"' : '';?>><?= $heading; ?></h2>
                </div>
            <? endif; ?>
            <div class="content-box">
                <div class="icons-container four-icons-with-image-carousel">
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
                <div class="image-container">
                    <img src="<?= $image['src'] ? $image['src'] : '';?>"<?= $image['alt'] ? 'alt="' . $image['alt'] . '"' : '';?><?= $image['classes'] ? 'class="' . implode(' ', $image['classes']) . '"' : '';?>>
                </div>
            </div>
        </div>
    </div>
</section>
