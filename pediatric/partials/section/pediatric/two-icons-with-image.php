<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('two-icons-with-image-carousel');
?>

<section class="two-icons-with-image<?= !empty($classes) ? ' ' . implode(' ', $classes) : ''; ?>">
    <div class="content">
        <div class="inner-content">
            <div class="image-container">
                <?php if (!empty($video_link)): ?>
                    <iframe style="margin-left: 50px;" width="560" height="315" src="<?= $video_link; ?>" title="Video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                    </iframe>
                <?php elseif (!empty($image['src'])): ?>
                    <img src="<?= $image['src']; ?>" <?= !empty($image['alt']) ? 'alt="' . $image['alt'] . '"' : ''; ?>
                        <?= !empty($image['classes']) ? 'class="' . implode(' ', $image['classes']) . '"' : ''; ?>>
                <?php endif; ?>
            </div>
            <div class="content-box">
                <? if (!empty($heading)): ?>
                    <h3<?= !empty($heading_classes) ? ' class="' . implode(' ', $heading_classes) . '"' : ''; ?>>
                        <?= $heading; ?></h3>
                    <? endif; ?>
                    <? if ($copy): ?>
                        <?= $copy; ?>
                    <? endif; ?>
                    <? if (!empty($slides)): ?>
                        <div class="slides-container two-icons-with-image-carousel">
                            <? foreach ($slides as $slide): ?>
                                <div class="slide">
                                    <div class="icon"><i class="widget static icon-<?= $slide['icon']; ?>"></i></div>
                                    <div class="heading"><?= $slide['heading']; ?></div>
                                    <div class="copy"><?= $slide['copy']; ?></div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    <? endif; ?>
                    <? if (!empty($sub_and_cta)): ?>
                        <? if (!empty($sub_and_cta['subheading'])): ?>
                            <h4 class="h5"><?= $sub_and_cta['subheading']; ?></h4>
                        <? endif; ?>
                        <? if (!empty($sub_and_cta['cta'])): ?>
                            <?= $sub_and_cta['cta']; ?>
                        <? endif; ?>
                    <? endif; ?>
            </div>
        </div>
    </div>
</section>