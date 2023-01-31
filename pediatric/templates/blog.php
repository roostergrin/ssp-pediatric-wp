<?
# Template Name: Blog

global $wp;
$brand = is_brand();
$relative_url = $wp->request;
$posts_per_page = 5;
$page_number = $page_number ?? 1;
$first_post_query_args = [
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => 1,
	'paged' => $page_number,
	'meta_query' => [
		[
			'key' => 'post_brand_relationship',
			'value' => $brand->ID,
			'compare' => 'LIKE'
		]
	]
];
if (strstr($relative_url, 'kids-dentist-blog/category/')) {
	$relative_array = explode('/', $relative_url);
	$first_post_query_args['category_name'] = end($relative_array);
}
$first_post_query = new WP_Query($first_post_query_args);
$first_post = $first_post_query->posts[0];
$excluded_ids = [$first_post->ID];
$post_query_args = [
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => - 1,
	'post__not_in' => $excluded_ids,
	'paged' => $page_number,
	'meta_query' => [
		[
			'key' => 'post_brand_relationship',
			'value' => $brand->ID,
			'compare' => 'LIKE'
		]
	]
];

if (strstr($relative_url, 'kids-dentist-blog/category/')) {
	$relative_array = explode('/', $relative_url);
	$post_query_args['category_name'] = end($relative_array);
}
$posts_query = new WP_Query($post_query_args);

get_header();

partial('section.blog.archive', [
	'large_post' => $first_post,
	'small_posts' => $posts_query->posts,
	'found_posts' => $posts_query->found_posts,
]);

get_footer();
