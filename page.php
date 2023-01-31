<?
the_post();
get_header();
partial('section.copy.full', [
	'heading' => 'page.php',
	'content' => 'Lorem ipsum dolor sit amet...',
]);
partial('section.copy.full', [
	'heading' => 'page.php - copy 2',
	'content' => 'Lorem ipsum dolor sit amet...',
]);
partial('section.copy.full', [
	'heading' => 'page.php - copy 3',
	'content' => 'Lorem ipsum dolor sit amet...',
]);
get_footer();