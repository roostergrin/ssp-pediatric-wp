<section class="copy full<?= $classes ? ' ' . implode(' ', $classes) : ''; ?>">
    <div class="content">
        <div class="inner-content">
            <?php if ($heading): ?>
                <h2 class="<?= !empty($heading_classes) ? implode(' ', $heading_classes) : ''; ?>"><?= $heading; ?></h2>
            <?php endif; ?>

            <?php if ($copy): ?>
                <?= $copy; ?>
            <?php endif; ?>

            <?php if (!empty($video_link)): ?>
                <div class="video-grid"
                    style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; margin: 0 auto; width: 75%;">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="video-container" style="margin: 0 auto;">
                            <iframe width="460" height="215" src="<?= esc_url($video_link); ?>" title="Video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                            </iframe>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>


        </div>
    </div>
</section>