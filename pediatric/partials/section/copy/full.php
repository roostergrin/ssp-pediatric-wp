<?php
// Define an array of video links
$vid_links = [
    "https://www.youtube.com/embed/eTsjjettpVE",
    "https://youtube.com/embed/B9w836TKHn4",
    "https://youtube.com/embed/M0o2DKD4M1c",
    "https://youtube.com/embed/YBnN8DNSu4U",
];
?>

<section class="copy full<?= $classes ? ' ' . implode(' ', $classes) : ''; ?>">
    <div class="content">
        <div class="inner-content">
            <?php if ($heading): ?>
                <h2 class="<?= !empty($heading_classes) ? implode(' ', $heading_classes) : ''; ?>"><?= $heading; ?></h2>
            <?php endif; ?>

            <?php if ($copy): ?>
                <div class="<?= !empty($copy_classes) ? implode(' ', $copy_classes) : ''; ?>">
                    <?= $copy; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($video_links) && is_array($video_links)): ?>
                <div class="video-grid">
                    <?php foreach ($video_links as $index => $video_link): ?>
                        <?php if (!empty($video_link) && $index < 4): // Ensure the video link is not empty and limit to 4 ?>
                            <div class="video-container-1">
                                <h3 class="video-title"><?= $video_link['title'] ?></h3>
                                <iframe width="560" height="315" src="<?= esc_url($video_link['link']); ?>" title="Video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                                </iframe>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
