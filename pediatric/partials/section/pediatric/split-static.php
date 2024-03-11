<?php if(!empty($heading) || !empty($copy)) : ?>
<section class="split-static<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="anchor"<?= !empty($id) ? 'id="' . $id . '" ' : '';?>></div>
    <div class="content">
        <div class="inner-content">
            <div class="content-wrapper">
                <div class="split left-side">
                    <?php if(!empty($video)) :?>
                        <video controls>
                            <source src="<?= $video; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php elseif(!empty($image)) :?>
                        <img src="<?= $image['src']; ?>" <?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?>/>
                    <?php endif; ?>
                </div>
                <div class="split right-side">
                    <div class="copy">
                        <?php if(!empty($h1)) : ?>
                            <h1 class="<?=  $h1_classes ? implode(' ', $h1_classes): '';?>"><?= $h1; ?></h1>
                        <?php endif;?>
                        <?php if(!empty($heading)) : ?>
                        <h2 class="<?=  $heading_classes ? implode(' ', $heading_classes): '';?>"><?= $heading; ?></h2>
                        <?php endif;?>
                        <?= !empty($copy) ? $copy : ''; ?>
                        <?php if(!empty($cta)) :?>
                            <?= $cta; ?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</section>
<?php endif;?>
