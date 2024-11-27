<section class="copy full<?= $classes ? ' ' . implode(' ', $classes) : ''; ?>">
    <div class="content">
        <div class="inner-content">
            <?php if ($heading): ?>
                <h2 class="<?= !empty($heading_classes) ? implode(' ', $heading_classes) : ''; ?>"><?= $heading; ?></h2>
            <?php endif; ?>

            <?php if ($copy): ?>
                <?= $copy; ?>
            <?php endif; ?>

            <?php if (!empty($video_links)): ?>
                <div class="video-grid">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="video-container-1">
                            <h3 class="video-title">Placeholder Title <?= $i + 1; ?></h3>
                            <iframe width="" height="" src="https://www.youtube.com/watch?v=B9w836TKHn4" title="Video player"
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