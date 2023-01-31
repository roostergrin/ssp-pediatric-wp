<section class="image-bubble-content<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
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
                    <div class="bubble-container">
                        <div class="copy">
                            <h2><?php echo $heading; ?></h2>
                            <?php echo $cta; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</section>