<?
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('treatments-carousel');

$h_tag = !empty($h_tag) ? $h_tag : 'h2';
$h_class = !empty($h_class) ? $h_class : 'h1';
?>

<section class="treatments-carousel <?= !empty($copy_classes) ? implode(' ', $copy_classes) : '';  ?>">
    <div class="content">
        <? if(!empty($image)) :?>
            <img<?= !empty($image['src']) ? ' src="'.$image['src'].'"' : ''; ?><?= !empty($image['width']) ? ' width="'.$image['width'].'"' : ''; ?><?= !empty($image['height']) ? ' height="'.$image['height'].'"' : ''; ?><?= !empty($image['srcset']) ? ' srcset="'.$image['srcset'].'"' : ''; ?><?= !empty($image['sizes']) ? ' sizes="'.$image['sizes'].'"' : ''; ?><?= !empty($image['alt']) ? ' alt="'.$image['alt'].'"' : ''; ?><?= !empty($image['classes']) ? ' class="'.implode(' ', $image['classes']).'"' : ''; ?> />
        <? endif;?>
        <div class="inner-content">
            <div class="copy">
                <? if(!empty($heading)) :?>
                <<?= $h_tag; ?> class="<?= $h_class; ?>"><?= $heading; ?></<?= $h_tag; ?>>
            <? endif;?>
            <? if(!empty($content)) :?>
                <?= apply_filters('the_content',$content); ?>
            <? endif;?>
        </div>
    </div>
    </div>
    <? if(!empty($slides)) : ?>
        <div id="treatments-owl-carousel" class="owl-carousel">
            <?foreach($slides as $slide) :?>
                <? if(empty($slide['heading']) && empty($slide['content']) && empty($slide['cta'])) continue; ?>
                <div class="treatment">
                    <div class="treatment-container">
                        <?php if(!empty($slide['icon'])) : ?>
                            <div class="icon-container<?= !empty($slide['icon']['classes']) ? ' ' . implode(' ', $slide['icon']['classes']) : ''?>">
                                <div class="icon"><i class="widget static icon-<?= $slide['icon']['icon'];?><?= !empty($slide['icon']['classes']) ? ' ' . implode(' ', $slide['icon']['classes']) : ''?>"></i></div>
                            </div>
                        <?php endif; ?>
                        <div class="copy-container">
                            <h3><?= $slide['heading']?></h3>
                            <?= apply_filters('the_content', $slide['content']);?>
                        </div>
                        <?php if(!empty($slide['cta'])): ?>
                            <?= do_shortcode($slide['cta']); ?>
                            <!--                    <a class="cta red" href="--><?//= $slide['cta']?><!--">Learn more</a>-->
                        <?php endif; ?>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
        <div class="pagination-container">
            <div class="pagination">
                <div class="page-left"><span>Previous</span><i class="icon-left-arrow providers"></i></div>
                <div class="page-right"><i class="icon-right-arrow providers"></i><span>Next</span></div>
            </div>
        </div>
    <? endif; ?>
</section>