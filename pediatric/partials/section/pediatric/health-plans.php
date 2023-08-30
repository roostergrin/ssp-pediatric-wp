<section id="health-plans" class="copy health-plans<?= !empty($classes) ? ' '.implode(' ', $classes) : ''; ?>">
	<div class="content">
		<div class="inner-content">
			<div class="content-wrapper<?= !empty($content_classes) ? ' '.implode(' ', $content_classes) : ''; ?>">
				<? if (!empty($h2)) : ?>
					<h2<?= !empty($h2_classes) ? ' class="'.implode(' ', $h2_classes).'"' : ''; ?>><?= $h2; ?></h2>
                <? endif; ?>
                <? if (!empty($h3)) : ?>
                    <h3<?= !empty($h3_classes) ? ' class="'.implode(' ', $h3_classes).'"' : ''; ?>><?= $h3; ?></h3>
				<? endif; ?>
				<?= apply_filters('the_content', $content); ?>
			</div>
            <?php if(!empty($logos)): ?>
            <ul class="tiles">
                <?php foreach ($logos as $logo): ?>
                <li class="logo<?= empty($logo->logo) ? ' no-image' : ''?>">
                    <? if(!empty($logo->logo)) : ?>
                        <img src="<?= wp_get_attachment_image_src($logo->logo, 'full')[0]; ?>" alt="" width="136" height="26" />
                        <h3 class="primary hover"><?= $logo->post_title; ?></h3>
                    <? else: ?>
                        <h3 class="primary"><?= $logo->post_title; ?></h3>
                    <?  endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <ul class="tiles">
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/anthem.jpg" alt="" width="136" height="26" />
                        <h3 class="primary">Anthem Blue Cross Blue Shield</h3>
                    </li>
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/cigna.jpg" alt="" width="159" height="43" />
                        <h3 class="primary">Cigna dental insurance</h3>
                    </li>
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/delta-dental.jpg" alt="" width="202" height="23" />
                        <h3 class="primary">Delta Dental</h3>
                    </li>
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/power-health.jpg" alt="" width="176" height="59" />
                        <h3 class="primary">ForwardHealth Wisconsin Medicaid (BadgerCare)</h3>
                    </li>
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/health-partners.jpg" alt="" width="202" height="23" />
                        <h3 class="primary">HealthPartners dental insurance</h3>
                    </li>
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/humana.jpg" alt="" width="163" height="59" />
                        <h3 class="primary">Humana dental insurance</h3>
                    </li>
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/united-concordia-dental.jpg" alt="" width="163" height="59" />
                        <h3 class="primary">United Concordia Dental</h3>
                    </li>
                    <li class="logo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/images/placeholder/graphics/united-healthcare.jpg" alt="" width="163" height="59" />
                        <h3 class="primary">United HealthCare dental insurance</h3>
                    </li>
                </ul>
            <?php endif; ?>
		</div>
	</div>
</section>
