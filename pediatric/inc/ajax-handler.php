<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

$valid_actions = [
	'blog_page',
	'upload_review_files',
	'remove_review_files',
];

define('DOING_AJAX', true);

include '../../../../wp-load.php';

header('Content-Type: text/html');
send_nosniff_header();
header('Cache-Control: no-cache');
header('Pragma: no-cache');


include_once ABSPATH.WPINC.'/meta.php';
include_once ABSPATH.WPINC.'/class-wp-post.php';
include_once ABSPATH.WPINC.'/class-wp-term.php';
include_once ABSPATH.WPINC.'/post.php';
include_once ABSPATH.WPINC.'/formatting.php';
include_once ABSPATH.WPINC.'/kses.php';
include_once ABSPATH.WPINC.'/link-template.php';
include_once ABSPATH.WPINC.'/theme.php';
include_once ABSPATH.WPINC.'/rewrite.php';
include_once ABSPATH.WPINC.'/default-constants.php';
include_once ABSPATH.WPINC.'/class-wp-rewrite.php';

include_once ABSPATH.WPINC.'/meta.php';
include_once ABSPATH.WPINC.'/class-wp-post.php';
include_once ABSPATH.WPINC.'/class-wp-term.php';
include_once ABSPATH.WPINC.'/post.php';
include_once ABSPATH.WPINC.'/formatting.php';
include_once ABSPATH.WPINC.'/kses.php';
include_once ABSPATH.WPINC.'/link-template.php';
include_once ABSPATH.WPINC.'/theme.php';
include_once WP_CONTENT_DIR.'/mu-plugins/utility-functions.php';
include_once ABSPATH.WPINC.'/rewrite.php';
include_once ABSPATH.WPINC.'/default-constants.php';
include_once ABSPATH.WPINC.'/class-wp-rewrite.php';
wp_plugin_directory_constants();

$action = sanitize_title($_POST['action']);
if(empty($action) || !in_array($action, $valid_actions)) die('-1');

// $candidate_img_id = 9396;

$ret = [];

switch($action) {
	case 'blog_page':
		include_once ABSPATH.WPINC.'/pluggable.php';
		include_once ABSPATH.WPINC.'/class-wp-tax-query.php';
		include_once ABSPATH.WPINC.'/taxonomy.php';
		include_once ABSPATH.WPINC.'/taxonomies.php';
		include_once ABSPATH.WPINC.'/class-wp-query.php';
		include_once ABSPATH.WPINC.'/class-wp-taxonomies.php';
		include_once ABSPATH.WPINC.'/class-wp-meta-query.php';
		include_once ABSPATH.WPINC.'/user.php';
		include_once ABSPATH.WPINC.'/class-wp-user.php';
		include_once ABSPATH.WPINC.'/capabilities.php';
		include_once ABSPATH.WPINC.'/l10n.php';
		include_once ABSPATH.WPINC.'/shortcodes.php';
		include_once ABSPATH.WPINC.'/media.php';
		include_once ABSPATH.WPINC.'/query.php';

		$page = $_POST['page'] ?? 2;
		$post_type = 'post';
		$post_status = 'publish';
		$posts_per_page = $_POST['posts_per_page'] ?? 4;
		$query_args = [
			'paged' => $page,
			'post_type' => $post_type,
			'post_status' => $post_status,
			'posts_per_page' => $posts_per_page,
		];
		if (!empty($_POST['category'])) $query_args['category_name'] = $_POST['category'];
		$posts = new WP_Query($query_args);
		// print_r($query_args);
		// die;
		var_dump($posts->have_posts());
		print_r($query_args);
		if ($posts->have_posts()) {
			while ($posts->have_posts()) {
				$p = $posts->the_post();
				$small_image = !empty(get_post_thumbnail_id($p)) ? responsive_img(get_post_thumbnail_id($p)) : '';
				partial('widget.blog.small', [
					'image' => $small_image,
					'classes' => ['animate__animated animate__zoomInDown'],
					'content' => [
						'h3' => get_the_title(),
						'h3_classes' => ['h4', 'white'],
						'categories' => wp_get_post_categories(get_the_ID()),
						'copy' => excerptizeCharacters(get_the_content(), 198),
						'cta' => [
							'href' => get_permalink(get_the_ID()),
							'classes' => ['cta', 'text', 'white'],
							'text' => 'Read article',
						]
					]
				]);
			}
		}
		wp_reset_postdata();
		die;
		break;

	case 'upload_review_files':
		include_once get_stylesheet_directory().'/lib/fileuploader/src/php/class.fileuploader.php';
		if (!file_exists('../../../uploads/review_files/')) mkdir('../../../uploads/review_files/', 0775, true);
		$FileUploader = new FileUploader('review_files', [
			'limit' => 20,
			'maxSize' => null,
			'fileMaxSize' => 4000,
			'extensions' => ['mp4','mov','mpeg','mpg','xvid','m4v','mpeg4','avchd','avi','wmv','mpegps','3gp','3gpp','webm','dnxhr','hevc','mkv','jpg','jpeg','gif','png','svg','tif','tga','bmp','jfif'],
			'required' => false,
			'uploadDir' => realpath('../../../uploads/review_files').'/',
			'title' => 'name',
			'replace' => false,
			'listInput' => true,
			'files' => null
		]);

		// call to upload the files
		$data = $FileUploader->upload();

		// export to js
		header('Content-Type: application/json');
		die(json_encode($data));
		break;

	case 'remove_review_files':
		if (isset($_POST['file'])) {
			$file = realpath('../../../uploads/review_files').'/'.str_replace(['/', '\\', '..'], '', urldecode($_POST['file']));
			if (file_exists($file)) unlink($file);
		}
		break;
}

die(json_encode($ret));
