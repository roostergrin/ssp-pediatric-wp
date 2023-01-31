<section class="centered-heading-cta<?= $classes ? ' ' . implode(' ', $classes) : ''?>">
    <div class="content">
        <div class="inner-content">
            <? if(!empty($heading)) :?>
                <h2<?= $heading_classes ? ' class="' . implode(' ', $heading_classes) . '"' : ''; ?>><?= $heading; ?></h2>
            <? endif; ?>
            <? if(!empty($cta)) :?>
                <?= $cta ?>
            <? endif;?>
                <div class="bubble bubble-1"></div>
                <div class="bubble bubble-2"></div>
                <div class="bubble bubble-3"></div>
                <div class="bubble bubble-4"></div>
        </div>
    </div>
</section>