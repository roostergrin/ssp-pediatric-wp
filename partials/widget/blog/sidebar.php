<?
global $wp; 

$brand = is_brand();			
$categories = list_brand_categories();
$catOrder = array('1', '9', '38', '39', '40', '41', '42');

usort($categories, function($a, $b) use ($catOrder) {
    return array_search($a->term_id, $catOrder) - array_search($b->term_id, $catOrder);
});

?>
<div class="widget blog sidebar-container">
	<div class="inner-container">
		<? if (isset($back)) : ?>
			<? if (isset($label)) : ?>
				<a href="<?= brand_url('kids-dentist-blog'); ?>" class="cta primary green back-to-blog"><?= $label; ?></a>
			<? else: ?>
				<a href="<?= brand_url('kids-dentist-blog'); ?>" class="cta primary green back-to-blog">Back to blog</a>
			<? endif; ?>
		<? endif; ?>
		<h3 class="h5 primary"><?= $brand->blog_sidebar_heading; ?></h3>
		<?= property_exists($brand, 'blog_sidebar_content') ? apply_filters('the_content', $brand->blog_sidebar_content) : ''; ?>
		<ul class="category-list">			
			<? foreach ($categories as $category) : ?>
				<li><a href="<?= blog_url(get_relative_url((get_category_link($category))).'/'); ?>"<?= basename($wp->request) == $category->slug ? ' class="active"' : ''; ?>><?= $category->name; ?></a></li>
			<? endforeach; ?>
		</ul>
	</div>
</div>
