<section class="copy full<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <?if($heading) : ?>
            <h2 class="<?= !empty($heading_classes) ? implode(' ', $heading_classes) : '' ;?>"><?= $heading; ?></h2>
            <? endif; ?>
            <?if($copy) : ?>
            <?= $copy; ?>
            <? endif; ?>
        </div>
    </div>
</section>