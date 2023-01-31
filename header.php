<?
global $is_IE, $is_edge;
$brand = is_brand();
$colors = explode(', ', $brand->colors);
$fonts = explode('+', $brand->fonts);

$html_classes = $body_classes = [];
if(is_user_logged_in()) $html_classes[] = 'admin-bar';
if($is_IE || $is_edge) $body_classes[] = 'IE';

$html_classes = array_filter(array_unique($html_classes));
$html_classes = empty($html_classes) ? '' : ' class="'.implode(' ', $html_classes).'" ';
?>
<!DOCTYPE html>
<html <? language_attributes(); echo $html_classes; ?>>
	<head>
		<title><? wp_title(' | ', true, 'right'); ?></title>
		<meta name="format-detection" content="telephone=no">
		<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
		<? wp_head(); ?>
		<style>
			:root {
				--color-primary: <?= !empty($colors[0]) ? $colors[0] : '#418ce1'; ?>;
				--color-secondary:<?= !empty($colors[1]) ? $colors[1] : '#9FC5F0'; ?>;
				--color-tertiary:<?= !empty($colors[2]) ? $colors[2] : '#66A2E7'; ?>;
				--color-gray-1:<?= !empty($colors[3]) ? $colors[3] : '#46464a'; ?>;
				--font-primary: <?= !empty($fonts[0]) ? $fonts[0] : 'usual, sans-serif'; ?>;
				--font-secondary: <?= !empty($fonts[1]) ? $fonts[1] : 'urbane, sans-serif'; ?>;
			}
		</style>
	</head>
	<body <? body_class($body_classes); ?>>
		<main>
			<? do_action('body'); ?>
            <? partial('section.header-mobile'); ?>
			<? partial('section.header'); ?>
