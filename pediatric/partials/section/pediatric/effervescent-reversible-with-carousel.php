<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('effervescent-reversible-carousel');
?>

<section class="effervescent-reversible-with-carousel<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <? if(!empty($top_image)) :?>
            <div class="top-image-container">
                <div class="top-image-circle"></div>
                <img src="<?= $top_image['src'] ? $top_image['src'] : '';?>"<?= $top_image['alt'] ? 'alt="' . $top_image['alt'] . '"' : '';?><?= $top_image['classes'] ? 'class="' . implode(' ', $top_image['classes']) . '"' : '';?>>
            </div>
            <? endif; ?>
            <? if(!empty($heading)) :?>
            <h2 class="<?= !empty($heading_classes) ? implode(' ', $heading_classes) : '';?>"><?= $heading; ?></h2>
            <? endif; ?>
            <div class="heading-container">
                <div class="pc-circle medium"></div>
                <div class="pc-circle small"></div>
            </div>
            <div class="box">
                <div class="inner-box-left">
                    <div class="heading">
                        <h3><?= $wrapper_left_heading; ?></h3>
                        <h4><?= $wrapper_left_subheading; ?></h4>
                    </div>
                    <div class="copy">
                        <?= $wrapper_left_copy; ?>
                    </div>
                </div>
                <div class="inner-box-right">
                    <div class="sub-box sub-box-left">
                        <p><?= $eyebrow_text; ?></p>
                        <p class="demographic">children</p>
                        <div class="slides owl-carousel">
                            <? foreach($sub_wrapper_left['slides'] as $slide) :?>
                            <div class="slide">
                                <div class="icon"><i class="widget static icon-<?= $slide['icon'];?>"></i></div>
                                <div class="heading"><?=$slide['heading'];?></div>
                                <div class="copy"><?=$slide['copy'];?></div>
                            </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="sub-box sub-box-right">
                        <p><?= $eyebrow_text; ?></p>
                        <p class="demographic">parents</p>
                        <div class="slides owl-carousel">
                            <? foreach($sub_wrapper_right['slides'] as $slide) : ?>
                            <div class="slide">
                            <div class="icon"><i class="widget static icon-<?= $slide['icon'];?>"></i></div>
                                <div class="heading"><?= $slide['heading']; ?></div>
                                <div class="copy"><?= $slide['copy']; ?></div>
                            </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>