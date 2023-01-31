<section class="photos-staggered<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <div class="cols col-left">
                <div class="img-container">
                    <img src="<?= $left['src']; ?>" alt="<?= $left['alt']; ?>" class="<?= !empty($left['classes']) ? implode(' ', $left['classes']) : '';?>">
                </div>
            </div>
            <div class="cols col-right">
                <div class="img-container">
                    <img src="<?= $right['src']; ?>" alt="<?= $right['alt']; ?>" class="<?= !empty($right['classes']) ? implode(' ', $right['classes']) : '';?>">
                </div>
            </div>
        </div>
    </div>
</section>