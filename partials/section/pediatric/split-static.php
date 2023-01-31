<? if(!empty($heading) || !empty($copy)) : ?>
<section class="split-static<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="anchor"<?= !empty($id) ? 'id="' . $id . '" ' : '';?>></div>
    <div class="content">
        <div class="inner-content">
            <div class="content-wrapper">
                <div class="split left-side">
                    <? if(!empty($image)) :?>
                    <img src="<?= $image['src']; ?>" <?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?>/>
                    <? endif; ?>
                </div>
                <div class="split right-side">
                    <div class="copy">
                        <? if(!empty($h1)) : ?>
                            <h1 class="<?=  $h1_classes ? implode(' ', $h1_classes): '';?>"><?= $h1; ?></h1>
                        <? endif;?>
                        <? if(!empty($heading)) : ?>
                        <h2 class="<?=  $heading_classes ? implode(' ', $heading_classes): '';?>"><?= $heading; ?></h2>
                        <? endif;?>
                        <?= !empty($copy) ? $copy : ''; ?>
                        <? if(!empty($cta)) :?>
                            <?= $cta; ?>
                        <? endif;?>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</section>
<? endif;?>