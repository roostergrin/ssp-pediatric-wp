<?php
wp_enqueue_style('lib-owl-carousel');
wp_enqueue_script('internal-logo-carousel');
?>
<section class="professional-associations">
    <div class="content">
        <div class="inner-content">
            <h2>Education and professional associations</h2>
            <?php if(!empty($edu_associations)): ?>
                <div class="car">
                    <div class="univ-logos owl-carousel">
                        <?php foreach ($edu_associations as $edu): ?>
                        <div class="logo-container">
                            <? if (!empty($edu->image['src']) && !empty($edu->heading) && !empty($edu->content)) : ?>
                            <div class="image">
                                <img<?= !empty($edu->image['src']) ? ' src="'.(brand_host().$edu->image['src']).'"' : ''; ?><?= !empty($edu->image['width']) ? ' width="'.$edu->image['width'].'"' : ''; ?><?= !empty($edu->image['height']) ? ' height="'.$edu->image['height'].'"' : ''; ?><?= !empty($edu->image['srcset']) ? ' srcset="'.$edu->image['srcset'].'"' : ''; ?><?= !empty($edu->image['sizes']) ? ' sizes="'.$edu->image['sizes'].'"' : ''; ?><?= !empty($edu->image['alt']) ? ' alt="'.$edu->image['alt'].'"' : ''; ?><?= !empty($edu->image['classes']) ? ' class="'.implode(' ', $edu->image['classes']).'"' : ''; ?> />
                            </div>
                            <div class="copy">
                                <h3><?= $edu->heading; ?></h3>
                                <?= apply_filters('the_content', $edu->content); ?>
                            </div>
                            <? elseif(empty($edu->image) && !empty($edu->heading) && !empty($edu->content)) :?>
                                <div class="image">
                                    <h3><?= $edu->heading; ?></h3>
                                </div>
                                <div class="copy">
                                <h3><?= $edu->heading; ?></h3>
                                <?= apply_filters('the_content', $edu->content); ?>
                            </div>
                            <? else:?>
                                <div class="static">
                                    <h3 class="primary static"><?= $edu->heading; ?></h3>
                                </div>
                            <? endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="pagination-container <?= count($edu_associations);?> <? if(count($edu_associations) <= 3): ?>limited-amount<? endif; ?>">
                        <div class="pagination">
                            <div class="page-left"><span>Previous</span><i class="icon-left-arrow tri-carousel"></i></div>
                            <div class="page-right"><i class="icon-right-arrow tri-carousel"></i><span>Next</span></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="copy-content">
                <p>Through my professional affiliations, I’m able to connect and collaborate with fellow orthodontists and dentists to allow for continued growth and sharing of knowledge, ensuring that we all provide quality care to the patients we serve. I’m proud to be a professional member of the following associations:</p>
            </div>
            <?php if(!empty($pro_affiliations)): ?>
                <div class="car">
                    <div class="univ-logos owl-carousel">
                        <?php foreach ($pro_affiliations as $pro): ?>
                            <div class="logo-container">
                            <? if (!empty($pro->image['src']) && !empty($pro->heading) && !empty($pro->content)) : ?>
                                <div class="image">
                                    <img<?= !empty($pro->image['src']) ? ' src="'.(brand_host().$pro->image['src']).'"' : ''; ?><?= !empty($pro->image['width']) ? ' width="'.$pro->image['width'].'"' : ''; ?><?= !empty($pro->image['height']) ? ' height="'.$pro->image['height'].'"' : ''; ?><?= !empty($pro->image['srcset']) ? ' srcset="'.$pro->image['srcset'].'"' : ''; ?><?= !empty($pro->image['sizes']) ? ' sizes="'.$pro->image['sizes'].'"' : ''; ?><?= !empty($pro->image['alt']) ? ' alt="'.$pro->image['alt'].'"' : ''; ?><?= !empty($pro->image['classes']) ? ' class="'.implode(' ', $pro->image['classes']).'"' : ''; ?> />
                                </div>
                                <div class="copy">
                                    <h3><?= $pro->heading; ?></h3>
                                    <?= apply_filters('the_content', $pro->content); ?>
                                </div>
                            <? elseif(empty($pro->image['src']) && !empty($pro->heading) && !empty($pro->content)) :?>
                                <div class="image static">
                                    <h3 class="primary static"><?= $pro->heading; ?></h3>
                                </div>
                                <div class="copy">
                                    <h3><?= $pro->heading; ?></h3>
                                    <?= apply_filters('the_content', $pro->content); ?>
                                </div>
                            <? else:?>
                                <div class="static">
                                    <h3 class="primary static"><?= $pro->heading; ?></h3>
                                </div>
                            <? endif; ?>
    
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="pagination-container <?= count($pro_affiliations);?> <? if(count($pro_affiliations) <= 3): ?>limited-amount<? endif; ?>">
                        <div class="pagination">
                            <div class="page-left"><span>Previous</span><i class="icon-left-arrow tri-carousel"></i></div>
                            <div class="page-right"><i class="icon-right-arrow tri-carousel"></i><span>Next</span></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
