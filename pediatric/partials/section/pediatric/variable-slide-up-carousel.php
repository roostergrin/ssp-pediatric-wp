<?php
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('variable-slide-up-carousel');

$carousel_id = uniqid('variable-slide-up-owl-carousel-');
?>
<section id="<?= $carousel_id ?>" class="variable-slide-up-carousel<?= $classes ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <?php if(!empty($image)) :?>
            <img<?= !empty($image['src']) ? ' src="'.$image['src'].'"' : ''; ?><?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?> />
        <?php endif;?>
        <div class="inner-content">
            <?php if(!empty($heading) || !empty($content)) :?>
            <div class="copy">
                <?php if(!empty($heading)) :?>
                <h2 class="<?= !empty($heading_classes) ? implode(' ', $heading_classes) : '';?>"><?= $heading; ?></h2>
                <?php endif;?>
                <?php if(!empty($content)) :?>
                <?= apply_filters('the_content',$content); ?>
                <?php endif;?>
            </div>
            <?php endif;?>
            <?php if(!empty($slides)) : ?>
            <div id="<?= $carousel_id ?>-owl-carousel"<?= $carousel_classes ? ' class="caro ' . implode(' ', $carousel_classes) . '"' : ' class="caro"';?>>
                <?php foreach($slides as $slide) :?>
                    <?php $slide_type = !empty($slide['image']) ? 'image' : 'icon';?>
                    <div class="slide<?= !empty($slide_type) ? ' ' . $slide_type : '';?>">
                        <div class="slide-container">
                            <?php if($slide['image']) : ?>
                            <div class="image-container<?= !empty($slide['image']['classes']) ? ' ' . implode(' ', $slide['image']['classes']) : ''?>">
                                <img src="<?= $slide['image']['src']?>" alt="<?= $slide['image']['alt']?>" width="180" height="180">
                            </div>
                            <?php endif; ?>
                            <?php if($slide['icon']) : ?>
                            <div class="icon-container<?= !empty($slide['icon_classes']) ? ' ' . implode(' ', $slide['icon_classes']) : ''?>">
                                <div class="icon"><i class="widget static icon-<?= $slide['icon'];?>"></i></div>
                            </div>
                            <?php endif; ?>
                            <div class="copy-container">
                                <h3><?= $slide['heading']?></h3>
                                <?= apply_filters('the_content', $slide['content']);?>
                            </div>
                            <?php if(!empty($slide['cta'])): ?>
                                <div class="cta-container">
                                    <?= do_shortcode($slide['cta'],true); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination-container">
                <div class="pagination">
                    <div class="page-left"><span>Previous</span><i class="icon-left-arrow providers"></i></div>
                    <div class="page-right"><i class="icon-right-arrow providers"></i><span>Next</span></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
