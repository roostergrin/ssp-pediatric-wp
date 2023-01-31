<section class="static-three-col<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <? if(!empty($heading)) :?>
            <h2><?= $heading; ?></h2>
            <? endif; ?>
            <div class="three-cols">
                <? foreach($cols as $col) :?>
                <div class="col"><?= $col; ?></div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</section>