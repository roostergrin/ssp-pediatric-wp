<?
wp_enqueue_script('three-bubble-carousel');
?>

<section class="three-bubble-hero<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <div class="bubble-photo-container three-bubble-carousel">
                <? foreach($images as $image) :?>
                <div class="bubble-photo">
                    <img src="<?= $image['src']; ?>" alt="<?= $image['alt']; ?>"<?= $image['classes'] ? ' class="' . implode(' ', $image['classes']) . '"' : '';?>">
                </div>
                <? endforeach; ?>
            </div>
            <div class="copy-container">
                <? if($heading) :?>
                <h1><?= $heading; ?></h1>
                <? endif; ?>
                <?= $copy ? apply_filters('the_content', $copy) : '';?>
                <div class="cta-container">
                    <a href="#section-1" class="cta white hover-white-trans"><?= $link_text_1; ?></a>
                    <a href="#section-2" class="cta white hover-white-trans"><?= $link_text_2; ?></a>
                    <a href="#section-3" class="cta white hover-white-trans"><?= $link_text_3; ?></a>
                </div>
            </div>
        </div>
    </div>
</section>