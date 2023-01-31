<section class="  copy side-by-side-with-box<?= !empty($classes) ? ' '.implode(' ', $classes) : ''; ?>">
	<div class="bubbles content<?= !empty($content_classes) ? ' '.implode(' ', $content_classes) : ''; ?>">
		<div class="inner-content">
			<div class="content-container">
				<div class="column1">
					<div class="content-wrapper">
						<?= !empty($column1) ? $column1 : ''; ?>
					</div>
				</div>
				<div class="column2">
					<div class="bg-container">
						<div class="content-wrapper">
							<?= !empty($column2) ? $column2 : ''; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>