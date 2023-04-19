<?

//=====================================[AUTOLOADER]=====================================//
spl_autoload_register(function($class_name) {
	if (strstr($class_name, '\\')) { // This allows us to use namespaces
		$class_name = explode('\\', $class_name);
		$class_name = end($class_name);
	}
	$file = get_stylesheet_directory().'/inc/classes/'.$class_name.'.php'; if(file_exists($file)) { include_once $file; return; }
});

//=====================================[GLOBAL CLASS INSTANCES]=====================================//
add_action('login_enqueue_scripts', function() {
	$brand = is_brand();
	ob_start();
	?>
	<style type="text/css">
		.login {
			background: #eaeaea;
		}
		.login #backtoblog a, .login #nav a {
			color: #418ce1 !important;
		}
		.login form {
			background: #418ce1 !important;
		}
		.login label {
			color: #fff !important;
		}
		#login h1 a, .login h1 a {
			background-image: url("<?= wp_get_attachment_url($brand->logo_desktop) ?>");
			height:111px;
			width:320px;
			background-size: 320px 111px;
			background-repeat: no-repeat;
		}
		.wp-core-ui .button-primary {
			background: #ffffff !important;
			border-color: #ffffff !important;
			box-shadow: none !important;
			color: #418ce1 !important;
			text-decoration: none !important;
			text-shadow: none !important;
			font-weight: bold;
			border-radius: 0 !important;
		}
		.login #login_error {
			border-left: 4px solid #eb5847 !important;
		}
		.login .message {
			border-left: 4px solid #418ce1 !important;
		}
	</style>
	<?
	echo ob_get_clean();
});

add_filter('login_headerurl', function() { return home_url(); });

add_action('init', function() {
	if(isset($_GET['rebuild'])) flush_rewrite_rules(true);
	elseif(isset($_GET['purge-cache']) && function_exists('w3tc_flush_all')) {
		w3tc_flush_all();
		echo 'Cache purged!';
		die;
	}
});

add_action('wp_footer', function() {
	echo '<div id="google-recaptcha-container"></div>';

});

$header_classes = [];
$navigation = null;

add_action('wp_head', function() {
	$brand = is_brand();

	if(get_page_template_slug() === 'templates/leg-three.php' || get_page_template_slug() === 'templates/leg-four.php') {
		?>
			<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
		<?
	}
	?>

	<link rel="shortcut icon" href="<?= wp_get_attachment_image_url($brand->favicon); ?>" type="image/png" />

	<? if(!is_live()) return; ?>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','<?= $brand->gtm_container_id ?>');</script>

	<?
        $g_measurement_id = get_field('brand_ga4_measurement_id', $brand->ID);
        if($g_measurement_id):
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $g_measurement_id; ?>"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= $g_measurement_id; ?>');
    </script>
    <? endif;
});

add_action( 'wp_head', function(){
	global $post;

	if (is_brand() == 'southmoor' && $post->post_type == 'attachment' && $post->post_mime_type = 'application/pdf') : ?>
		<link rel="shortcut icon" href="<?= wp_get_attachment_image_url($brand->favicon); ?>" type="image/png" />'
	<? endif;
});

add_action('body', function() {
	if(!is_live()) return;
	$brand = is_brand();
?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $brand->gtm_container_id ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?
});

if (is_dev() || is_sim_admin()) {
	add_action('wp_footer', function() { ?>
		<script>
			window.markerConfig = {
			destination: '6179df7b0a9edf2626211e1f',
			source: 'snippet'
			};
		</script>
		<script>
			!function(e,r,a){if(!e.__Marker){e.__Marker={};var t=[],n={__cs:t};["show","hide","isVisible","capture","cancelCapture","unload","reload","isExtensionInstalled","setReporter","setCustomData","on","off"].forEach(function(e){n[e]=function(){var r=Array.prototype.slice.call(arguments);r.unshift(e),t.push(r)}}),e.Marker=n;var s=r.createElement("script");s.async=1,s.src="https://edge.marker.io/latest/shim.js";var i=r.getElementsByTagName("script")[0];i.parentNode.insertBefore(s,i)}}(window,document);
		</script>
<?
	});
}

// Fix WordPress file naming conventions for media uploads
// add_action('init', function() {
// 	if ($_SERVER['REMOTE_ADDR']) {
// 		add_filter('wp_get_attachment_metadata', function($data) {
// 			foreach ($data['sizes'] as $image_size => $data) {
// 				$width = $data['width'];
// 				$height = $data['height'];
// 				$data['sizes'][$image_size]['file'] = str_replace('-'.$width.'x'.$height, $image_size, '-'.$data['sizes'][$image_size]['file']);
// 			}
// 			return $data;
// 		});

// 		add_filter('wp_handle_upload_prefilter', function($file) {
// 			if (!empty($_REQUEST['post'])) {
// 				// Gutenberg REST request
// 				$parent = get_post(absint($_REQUEST['post']));
// 				$post_name = $parent->post_title;
// 			} elseif (!empty($_REQUEST['post_id'])) {
// 				// Media Manager AJAX request
// 				$parent = get_post(absint($_REQUEST['post_id']));
// 				$post_name = $parent->post_title;
// 			} else {
// 				$post_name = 'No parent';
// 			}

// 			if (is_user_logged_in()) {
// 				$user = wp_get_current_user();
// 				$user_name = $user->display_name;
// 			} else {
// 				$user_name = 'Not logged in';
// 			}

// 			$pathinfo = pathinfo($file['name']);
// 			$file['name'] = $post_name.'.'.$pathinfo['extension'];
// 			return $file;
// 		}, 10, 1);
// 	}
// });

add_action('after_setup_theme', function() {
	add_theme_support('post-thumbnails');

	// Blog hero 1x and 2x image sizes with 16 cropped orientations
	foreach (['left', 'center', 'right'] as $w) {
		foreach (['top', 'center', 'bottom'] as $h) {
			foreach ([1, 2] as $density) {
				add_image_size('blog_'.$w.'_'.$h.'_'.$density.'x', 817*$density, 450*$density, [$w, $h]);
			}
		}
	}

	require_once get_stylesheet_directory().'/lib/MDG/autoload.php';
	require_once get_stylesheet_directory().'/inc/utility-functions.php';

	# Theme-related
	global $brands; 					$brands = new Brands();
	global $providers; 					$providers = new Providers();
	global $locations; 					$locations = new Locations();
	global $regions; 					$regions = new Regions();
	global $edu_associations; 			$edu_associations = new EducationalAssociations();
	global $pro_affiliations; 			$pro_affiliations = new ProfessionalAffiliations();
	global $reviews; 					$reviews = new Reviews();
	global $smile_transformations; 		$smile_transformations = new SmileTransformations();
	global $insurance_providers; 		$insurance_providers = new InsuranceProviders();
	global $faqs; 						$faqs = new Faqs();

	# Preserve ordering for the following:
	global $site_404_settings;			$site_404_settings = new \MDG\Site404Settings();
	global $forms; 						$forms = new \MDG\Forms();
	global $structured_data;			$structured_data = new \MDG\StructuredData();

	# Custom template/fields for SIM4KIDS
	new VirtualTours();

	if (is_live()) {
		global $optimize;				$optimize = new \MDG\Optimize();
	}

	include_once get_stylesheet_directory() . '/inc/acf.php';
});

//=====================================[SELECT 2 HOOKS]=====================================//
function add_icon_selection_fields($key) {
	add_filter($key, function($field) {
		$field['choices'] = [
			'none' => '<span>&nbsp;&nbsp;Select static icons</span>',
//			'apple' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/apple.svg" height="20" />&nbsp;&nbsp;Apple</span>',
			'books' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/books.svg" height="20" />&nbsp;&nbsp;Books</span>',
			'building' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/building.svg" height="20" />&nbsp;&nbsp;Building</span>',
			'calendar' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/calendar.svg" height="20" />&nbsp;&nbsp;Calendar</span>',
			'cellphone' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/cellphone.svg" height="20" />&nbsp;&nbsp;Cellphone</span>',
			'certificate' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/certificate.svg" height="20" />&nbsp;&nbsp;Certificate</span>',
			'chair' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/chair.svg" height="20" />&nbsp;&nbsp;Chair</span>',
			'chipped-baby-tooth' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/chipped-baby-tooth.svg" height="20" />&nbsp;&nbsp;Chipped baby tooth</span>',
			'chipped-tooth' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/chipped-tooth.svg" height="20" />&nbsp;&nbsp;Chipped tooth</span>',
			'clock' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/clock.svg" height="20" />&nbsp;&nbsp;Clock</span>',
			'cup-bubbles' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/cup-bubbles.svg" height="20" />&nbsp;&nbsp;Cup bubbles</span>',
			'equalizer' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/equalizer.svg" height="20" />&nbsp;&nbsp;Equalizer</span>',
			'financing-waves' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/financing-waves.svg" height="20" />&nbsp;&nbsp;Financing waves</span>',
			'happy-face' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/happy-face.svg" height="20" />&nbsp;&nbsp;Happy face</span>',
			'heart' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/heart.svg" height="20" />&nbsp;&nbsp;Heart</span>',
			'left-arrow' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/left-arrow.svg" height="20" />&nbsp;&nbsp;Left arrow</span>',
			'location-pin' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/location-pin.svg" height="20" />&nbsp;&nbsp;Location-pin</span>',
			'med-kit' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/med-kit.svg" height="20" />&nbsp;&nbsp;Med kit</span>',
			'mouthgaurd' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/mouthgaurd.svg" height="20" />&nbsp;&nbsp;Mouthgaurd</span>',
			'mouth-open' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/mouth-open.svg" height="20" />&nbsp;&nbsp;Mouth open</span>',
			'pacifier' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/pacifier.svg" height="20" />&nbsp;&nbsp;Pacifier</span>',
			'paint-brush' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/paint-brush.svg" height="20" />&nbsp;&nbsp;Paint brush</span>',
			'parent-child' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/parent-child.svg" height="20" />&nbsp;&nbsp;Parent child</span>',
			'parent-child-checkmark' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/parent-child-checkmark.svg" height="20" />&nbsp;&nbsp;Parent child checkmark</span>',
			'person' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/person.svg" height="20" />&nbsp;&nbsp;Person</span>',
			'plain-tooth' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/plain-tooth.svg" height="20" />&nbsp;&nbsp;Plain Tooth</span>',
			'plant' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/plant.svg" height="20" />&nbsp;&nbsp;Plant</span>',
			'right-arrow' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/right-arrow.svg" height="20" />&nbsp;&nbsp;Right arrow</span>',
			'shield' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/shield.svg" height="20" />&nbsp;&nbsp;Shield</span>',
			'star-burst' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/star-burst.svg" height="20" />&nbsp;&nbsp;Star burst</span>',
			'talk-bubble-person' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/talk-bubble-person.svg" height="20" />&nbsp;&nbsp;Talk bubble person</span>',
			'talking-checkmark-bubble' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/talking-checkmark-bubble.svg" height="20" />&nbsp;&nbsp;Talking checkmark bubble</span>',
			'talking-tooth-bubble' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/talking-tooth-bubble.svg" height="20" />&nbsp;&nbsp;Talking tooth bubble</span>',
			'thought-cloud' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/thought-cloud.svg" height="20" />&nbsp;&nbsp;Thought cloud</span>',
			'tooth-and-smile' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-and-smile.svg" height="20" />&nbsp;&nbsp;Tooth and smile</span>',
			'tooth-bar-across' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-bar-across.svg" height="20" />&nbsp;&nbsp;Tooth bar across</span>',
			'toothbrush-toothpaste' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/toothbrush-toothpaste.svg" height="20" />&nbsp;&nbsp;Toothbrush toothpaste</span>',
			'tooth-in-circle' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-in-circle.svg" height="20" />&nbsp;&nbsp;Tooth in circle</span>',
			'tooth-with-checkmark' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-with-checkmark.svg" height="20" />&nbsp;&nbsp;Tooth with checkmark</span>',
			'tooth-with-magnifying-glass' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-with-magnifying-glass.svg" height="20" />&nbsp;&nbsp;Tooth with magnifying glass</span>',
			'tooth-with-plus-sign' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-with-plus-sign.svg" height="20" />&nbsp;&nbsp;Tooth with plus sign</span>',
			'tooth-with-stars' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-with-stars.svg" height="20" />&nbsp;&nbsp;Tooth with stars</span>',
			'tooth-xray' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-xray.svg" height="20" />&nbsp;&nbsp;Tooth xray</span>',
			'two-crosses' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/two-crosses.svg" height="20" />&nbsp;&nbsp;Two crosses</span>',
			'two-people' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/two-people.svg" height="20" />&nbsp;&nbsp;Two people</span>',
			'two-talk-bubbles' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/two-talk-bubbles.svg" height="20" />&nbsp;&nbsp;Two talk bubbles</span>',



//			'books' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/books.svg" height="20" />&nbsp;&nbsp;Books</span>',
//			'brush-and-paste' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/brush-and-paste.svg" height="20" />&nbsp;&nbsp;Brush and paste</span>',
//			'calendar' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/calendar.svg" height="20" />&nbsp;&nbsp;Calendar</span>',
//			'caution' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/caution.svg" height="20" />&nbsp;&nbsp;Caution</span>',
//			'certificate' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/certificate.svg" height="20" />&nbsp;&nbsp;Certificate</span>',
//			'chair' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/chair.svg" height="20" />&nbsp;&nbsp;Chair</span>',
//			'clock' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/clock.svg" height="20" />&nbsp;&nbsp;Clock</span>',
//			'frenectomy' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/frenectomy.svg" height="20" />&nbsp;&nbsp;Frenectomy</span>',
//			'med-kit' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/med-kit.svg" height="20" />&nbsp;&nbsp;Med kit</span>',
//			'overlapping-thoughts' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/overlapping-thoughts.svg" height="20" />&nbsp;&nbsp;Overlapping thoughts</span>',
//			'pacifier' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/pacifier.svg" height="20" />&nbsp;&nbsp;Pacifier</span>',
//			'parent-and-child' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/parent-and-child.svg" height="20" />&nbsp;&nbsp;Parent and child</span>',
//			'parent-education' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/parent-education.svg" height="20" />&nbsp;&nbsp;Parent education</span>',
//			'patient-education' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/patient-education.svg" height="20" />&nbsp;&nbsp;Patient education</span>',
//			'piercing' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/piercing.svg" height="20" />&nbsp;&nbsp;Piercing</span>',
//			'plain-tooth' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/plain-tooth.svg" height="20" />&nbsp;&nbsp;Plain tooth</span>',
//			'plant' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/plant.svg" height="20" />&nbsp;&nbsp;Plant</span>',
//			'shield' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/shield.svg" height="20" />&nbsp;&nbsp;Shield</span>',
//			'thought-bubble-kid' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/thought-bubble-kid.svg" height="20" />&nbsp;&nbsp;Thought bubble kid</span>',
//			'thought-bubble-tooth' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/thought-bubble-tooth.svg" height="20" />&nbsp;&nbsp;Thought bubble tooth</span>',
//			'thought-cloud' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/thought-cloud.svg" height="20" />&nbsp;&nbsp;Thought cloud</span>',
//			'tooth-cross' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-cross.svg" height="20" />&nbsp;&nbsp;Tooth cross</span>',
//			'tooth-crown' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-crown.svg" height="20" />&nbsp;&nbsp;Tooth crown</span>',
//			'tooth-flag' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-flag.svg" height="20" />&nbsp;&nbsp;Tooth flag</span>',
//			'tooth-stars' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-stars.svg" height="20" />&nbsp;&nbsp;Tooth stars</span>',
//			'tooth-with-brace' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-with-brace.svg" height="20" />&nbsp;&nbsp;Tooth with brace</span>',
//			'tooth-with-shield' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tooth-with-shield.svg" height="20" />&nbsp;&nbsp;Tooth with shield</span>',
//			'tray' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/tray.svg" height="20" />&nbsp;&nbsp;Tray</span>',
//			'two-crosses' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/two-crosses.svg" height="20" />&nbsp;&nbsp;Two crosses</span>',
//			'two-people' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons/two-people.svg" height="20" />&nbsp;&nbsp;Two people</span>',

		];

		return $field;
	}, 10, 3);
}

// Homepage
add_icon_selection_fields('acf/load_field/key=three_icons_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=three_icons_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=three_icons_slides_2_icon');

add_icon_selection_fields('acf/load_field/key=about_our_practice_section_three_left_col_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_three_left_col_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_three_left_col_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_three_right_col_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_three_right_col_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_three_right_col_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_four_left_col_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_four_left_col_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_four_left_col_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_four_right_col_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_four_right_col_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_four_right_col_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_five_left_col_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_five_left_col_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_five_left_col_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_five_right_col_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_five_right_col_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_five_right_col_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_seven_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_seven_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_seven_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_seven_slides_3_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_seven_slides_4_icon');
add_icon_selection_fields('acf/load_field/key=about_our_practice_section_seven_slides_5_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_two_slide_0_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_two_slide_1_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_two_slide_2_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_two_slide_3_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_five_slide_0_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_five_slide_1_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_six_slide_0_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_six_slide_1_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_seven_slide_0_icon');
add_icon_selection_fields('acf/load_field/key=service_template_1_section_seven_slide_1_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_two_slide_0_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_two_slide_1_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_two_slide_2_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_two_slide_3_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_five_slide_0_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_five_slide_1_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_six_slide_0_icon');
add_icon_selection_fields('acf/load_field/key=service_template_2_section_six_slide_1_icon');
add_icon_selection_fields('acf/load_field/key=location_section_two_icon_1_icon');
add_icon_selection_fields('acf/load_field/key=location_section_two_icon_2_icon');
add_icon_selection_fields('acf/load_field/key=location_section_two_icon_3_icon');
add_icon_selection_fields('acf/load_field/key=services_overview_section_two_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=services_overview_section_two_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=services_overview_section_two_slides_2_icon');
add_icon_selection_fields('acf/load_field/key=services_overview_section_two_slides_3_icon');
add_icon_selection_fields('acf/load_field/key=services_overview_section_two_slides_4_icon');
add_icon_selection_fields('acf/load_field/key=services_overview_section_two_slides_5_icon');
add_icon_selection_fields('acf/load_field/key=services_overview_section_two_slides_6_icon');
add_icon_selection_fields('acf/load_field/key=book_appointment_section_two_slides_0_icon');
add_icon_selection_fields('acf/load_field/key=book_appointment_section_two_slides_1_icon');
add_icon_selection_fields('acf/load_field/key=book_appointment_section_two_slides_2_icon');


function add_hover_icon_selection_fields($key) {
	add_filter($key, function($field) {
		$field['choices'] = [
			'none' => '<span>&nbsp;&nbsp;Select icons with hover states</span>',
			'apple' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-apple.svg" height="20" />&nbsp;&nbsp;Apple</span>',
			'building-tooth' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-building-tooth.svg" height="20" />&nbsp;&nbsp; Building - Tooth</span>',
			'caution-question-mark' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-caution-question-mark.svg" height="20" />&nbsp;&nbsp; Caution - Question mark</span>',
			'chat-two-talk-bubbles' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-chat-two-talk-bubbles.svg" height="20" />&nbsp;&nbsp; Chat two talk bubbles</span>',
			'credit-card' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-credit-card.svg" height="20" />&nbsp;&nbsp;Credit card</span>',
			'financing-waves' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-financing-waves.svg" height="20" />&nbsp;&nbsp;Financing waves</span>',
			'pacifier' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-pacifier.svg" height="20" />&nbsp;&nbsp;Pacifier</span>',
			'personal-check' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-personal-check.svg" height="20" />&nbsp;&nbsp;Personal check</span>',
			'phone' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-phone.svg" height="20" />&nbsp;&nbsp;Phone</span>',
			'piercing' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-piercing.svg" height="20" />&nbsp;&nbsp;Piercing</span>',
			'plain-tooth' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-plain-tooth.svg" height="20" />&nbsp;&nbsp;Plain tooth</span>',
			//'right-arrow' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-right-arrow.svg" height="20" />&nbsp;&nbsp;Right arrow</span>',
			'toothbrush-toothpaste' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-toothbrush-toothpaste.svg" height="20" />&nbsp;&nbsp;Toothbrush & toothpaste</span>',
			'tooth-brace' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-tooth-brace.svg" height="20" />&nbsp;&nbsp;Tooth brace</span>',
			'tooth-flag' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-tooth-flag.svg" height="20" />&nbsp;&nbsp;Tooth flag</span>',
			'tooth-plus' => '<span class="icon-adjust" style="display:flex;align-items:center;line-height:20px;"><img src="'.(get_stylesheet_directory_uri()).'/images/icons-hover/h-tooth-plus.svg" height="20" />&nbsp;&nbsp;Tooth plus</span>',
		];

		return $field;
	}, 10, 3);
}
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_4_icons_0_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_4_icons_1_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_4_icons_2_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_4_icons_3_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_4_icons_4_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_4_icons_5_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_4_icons_6_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_7_icons_0_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_7_icons_1_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_7_icons_2_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_7_icons_3_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_7_icons_4_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_7_icons_5_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_10_icons_0_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_10_icons_1_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_10_icons_2_icon');
add_hover_icon_selection_fields('acf/load_field/key=preparing_for_your_visit_section_10_icons_3_icon');

//Dental Emergencies page
add_hover_icon_selection_fields('acf/load_field/key=section_3_emergency_contact_slides_0_icon');
add_hover_icon_selection_fields('acf/load_field/key=section_3_emergency_contact_slides_1_icon');
add_hover_icon_selection_fields('acf/load_field/key=section_3_emergency_contact_slides_2_icon');

// Payment Options
add_hover_icon_selection_fields('acf/load_field/key=payment_options_section_three_slide_0_icon');
add_hover_icon_selection_fields('acf/load_field/key=payment_options_section_three_slide_1_icon');
add_hover_icon_selection_fields('acf/load_field/key=payment_options_section_three_slide_2_icon');

//=====================================[Custom Columns for Posts & Pages]=====================================//
add_filter('manage_post_posts_columns', function($columns) {
	$columns = [
		'cb' => $columns['cb'],
		'title' => $columns['title'],
		'author' => $columns['author'],
		'categories' => $columns['categories'],
		'brands' => __('Brands Relationship', 'post'),
		'date' => $columns['date'],
		'seotitle' => $columns['seotitle'],
		'seodesc' => $columns['seodesc'],
	];

	return $columns;
}, 10, 1);

add_action('manage_post_posts_custom_column', function($column, $post_id) {
	switch ($column) {
		case 'brands':
			$brands = get_brands_for_post($post_id, 1);
			echo !empty($brands) ? implode(', ', $brands) : '—';
			break;
	}
}, 10, 2);

add_filter('manage_page_posts_columns', function($columns) {
	$columns = [
		'cb' => $columns['cb'],
		'title' => $columns['title'],
		'author' => $columns['author'],
		'brand' => __('Brand Relationship', 'post'),
		'parent' => __('Page Parent', 'post'),
		'date' => $columns['date'],
		'seotitle' => $columns['seotitle'],
		'seodesc' => $columns['seodesc'],
	];

	return $columns;
}, 10, 1);

add_action('manage_page_posts_custom_column', function($column, $post_id) {
	switch ($column) {
		case 'brand':
			$brand = get_brand_for_page($post_id, 1);
			echo !empty($brand) ? implode(', ', $brand) : '-';
			break;
		case 'parent':
			$parent = get_parent_for_page($post_id, 1);
			echo !empty($parent) ? $parent : '-';
			break;
	}
}, 10, 2);

//=====================================[Filter Relationship & Post_Object fields]=====================================//
function filter_acf_queries($key) {
	add_filter($key, function($args, $field, $post_id) {
		$brand = is_brand();
		$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID');
		$pattern = '("'.implode('"|"', $brand_location_ids).'")';
		foreach ($args['post_type'] as $post_type) {
			if ($post_type == 'post') {
				$args['meta_query'] = [
					[
						'key' => 'post_brand_relationship',
						'value' => '"'.$brand->ID.'"',
						'compare' => 'LIKE'
					]
				];
			} elseif ($post_type == 'page') {
				$args['meta_query'] = [
					[
						'key' => 'page_brand_relationship',
						'value' => '"'.$brand->ID.'"',
						'compare' => 'LIKE'
					]
				];
			} elseif ($post_type == 'provider') {
				$args['meta_query'] = [
					[
						'key' => 'provider_location_relationship',
						'value' => $pattern,
						'compare' => 'REGEXP'
					]
				];
			} elseif ($post_type == 'location') {
				$args['meta_query'] = [
					[
						'key' => 'location_brand_relationship',
						'value' => '"'.$brand->ID.'"',
						'compare' => 'LIKE'
					]
				];
			}
		}

		return $args;
	}, 10, 3);
}
filter_acf_queries('acf/fields/post_object/query');

/**
 * custom ACF relationship field filters
 */
add_filter('acf/fields/relationship/result/name=page_brand_relationship', function( $text, $post, $field, $post_id ) {
    $text .= ' - ' . ' brand';
    return $text;
}, 10, 4 );
add_filter('acf/fields/relationship/result/name=provider_brand_relationship', function( $text, $post, $field, $post_id ) {
    $text .= ' - ' . ' brand';
    return $text;
}, 10, 4 );
add_filter('acf/fields/relationship/result/name=provider_location_relationship', function( $text, $post, $field, $post_id ) {
    $text .= ' - ' . 'post ID: ' . $post->ID;
    return $text;
}, 10, 4 );
add_filter('acf/fields/relationship/result/name=insurance_provider_page_relationship', function( $text, $post, $field, $post_id ) {
    global $locations, $brands;

    $brand_id = get_post_meta($post->ID, 'page_brand_relationship', true);
    $page_location_id = get_post_meta($post->ID, 'page_location_parent', true);
    if( !empty( $page_location_id ) ) {
        $text .= ' - ' .  $locations->locations[$page_location_id]->post_title;
    } else {
        if( !empty($brand_id)) {
            $brand_name = $brands->brands[$brand_id[0]]->post_title;
            $brand_name = str_replace('Orthodontics', '', $brand_name);
            $text .= ' - ' .  $brand_name . ' brand level';
        }
    }

    return $text;
}, 10, 4 );


function sim_acf_relationship_query( $args, $field, $post_id ) {
    $args['meta_query'] = [];
    return $args;
};
add_filter('acf/fields/relationship/query/name=meet_the_team_providers_relationship', 'sim_acf_relationship_query', 10, 3 );
add_filter('acf/fields/relationship/query/name=provider_location_relationship', function($args, $field, $post_id) {
    $args['posts_per_page'] = 40;
    return $args;
}, 10, 3 );
/* end ACF relationship field filters */


//=====================================[Shortcode List]=====================================//
add_action('add_meta_boxes', function() {
	$screens = ['page', 'brand', 'location', 'region', 'provider', 'edu_association', 'pro_affiliation', 'review', 'smile_transformation', 'insurance_provider', 'faq'];
	foreach ($screens as $screen) {
		add_meta_box(
			'mdg_shortcode_key', // ID
			'Shortcode List', // Title
			'mdg_shortcode_list_html', // Content callback
			$screen, // Post type
			'side', // Context
			'low' // Priority
		);
	}
});

function mdg_shortcode_list_html($post) {
	?>
	<h2>SHORT CODES</h2>
	<h2>Hints</h2>
	<p>Add<code>target="_blank"</code> before the closing <code>]</code>bracket to launch the link in a new window</p>
	<img src="<?= get_stylesheet_directory_uri(); ?>/images/placeholder/link-classes.jpg" style="width:100%;max-width:100%;height:auto;">
	<h2>BRAND HOME PAGE</h2>
	<p><code>[brand_link text="Any text here" class="Any class here" title="Any text here"]</code></p>
	<h2>FIND A LOCATION PAGE</h2>
	<p><code>[locations_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<h2>OUR TEAM</h2>
	<p><code>[meet_our_orthodontic_team_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[dr_bradley_smith_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[dr_scott_smith_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[dr_john_rubenstrunk_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<h2>CONTACT</h2>
	<p><code>[appointments_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[contact_us_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[phone_link text="Call %number%" class="Any class here"]</code></p>
	<p><code>[after_hours_phone_link text="Call %number%" class="Any class here"]</code></p>
	<p><code>[toll_free_phone_link text="Call %number%" class="Any class here"]</code></p>
	<h2>OUR APPROACH</h2>
	<p><code>[our_approach_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<h2>SERVICES</h2>
	<p><code>[all_services_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[dental_exams_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[dental_sealants_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[restorative_treatments_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[dental_emergencies_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[custom_mouthguard_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<h2>YOUR VISIT</h2>
	<p><code>[preparing_for_your_visit_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[payment_options_link text="Any text here" class="cta text" title="Any text here" anchor="#some-anchor-id"]</code></p>
	<p><code>[forms_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<h2>UTILITY PAGES</h2>
	<p><code>[doctor_referral_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[careers_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[privacy_policy_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[non_discrimination_link text="Any text here" class="cta text" title="Any text here"]</code></p>
	<p><code>[site_map_link text="Any text here" class="cta text" title="Any text here" target="_blank"]</code></p>
	<p><code>[community_involvement_link text="Any text here" class="cta text" title="Any text here" target="_blank"]</code></p>
	<p><code>[blog_link text="Any text here" class="cta text" title="Any text here" target="_blank"]</code></p>
	<?
}

//=====================================[ACTIONS & FILTERS]=====================================//
add_action('admin_head', function() {
?>
<style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
	-webkit-appearance: none;
	margin: 0;
}
input[type=number] {
	-moz-appearance: textfield;
}
.acf-field-vertical-position .acf-label,
.acf-field-vertical-position .acf-input,
.acf-field-horizontal-position .acf-label,
.acf-field-horizontal-position .acf-input {
	display: flex;
	align-content: center;
	justify-content: center;
	flex-direction: column;
}
</style>
<?
});

add_filter('pre_option_upload_url_path', function($path) {
	if(is_local()) {
		global $HOSTNAMES_LOCAL;
		$hostname = reset($HOSTNAMES_LOCAL); # TODO: change to use website_entity
		return 'http://'.$hostname.'/wp-content/uploads';
	}
	return $path;
});

add_action('admin_footer', function() {
	?><script type="text/javascript" src="<?= get_stylesheet_directory_uri().'/js/admin-scripts.js'; ?>"></script><?
});

// Roostergrin
add_action('wp_footer', function() {
	$brand = is_brand();
	$location = is_location();

	if(( is_sim_admin() || is_local() ) && ( gr_roostergrin_flag() || kristo_roostergrin_flag() || isset($_GET['rg_test']))) {
		//This part is used by all
		?>
		<script src="https://onlineschedulingv2.threadcommunication.com" type="text/javascript"></script>
		<?

		if( gr_roostergrin_flag() ) {
			?>
			<script type="text/javascript">OpenChair.init({token: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvcmlnaW4iOiJncmVhdHJpdmVyb3J0aG8uY29tIiwicHJhY3RpY2VfaWQiOiI3MTYifQ.4IphnjbQ0m5Qw4jaQYFaaeOjXpLAN1jxrYDWKOWXHQw", hideMainButton: true, brandName: "Great River Orthodontics", hideTooltipHeader: true, specificLocations: ["716-4b29b43b-3698-4f51-bc4c-dd4b58da32a0","716-7427cabc-ee2f-4fa1-bbc6-d3ef9730bc7d"]})</script>
			<?
		}
		if( kristo_roostergrin_flag() || isset($_GET['rg_test']) ) {
			?>
			<script type="text/javascript">OpenChair.init({token: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvcmlnaW4iOiJodHRwczovL2FkbWluLWtyaXN0b29ydGhvZG9udGljcy1jb20ubWRnYWR2ZXJ0aXNpbmcuY29tLyIsInByYWN0aWNlX2lkIjoiNzE2In0.d8KH42C_erMzQ5Zgjt3TBrBgs8gZFDevkoZYTxi6Pj4", mainButtonLeft: true, brandName: "Kristo Orthodontics", hideTooltipHeader: true, specificLocations: ["716-1defe3e0-0943-44a2-96c3-62ca6b55e3da","716-bb80b5d9-51e1-4906-8240-d200f0095db4","716-e88d2c50-c573-495e-b614-5035556744f7","716-4a7b0010-1d80-4f65-80b8-b82165ecc747","716-f4a9f662-d545-4ce6-8c1c-2be7f396dcec","716-39c50ed1-148b-4387-8ab6-a465496d8bc9","716-b0705c88-e868-430e-be9b-9ce3a9e9b5b0","716-18980ef6-04ad-4d88-ad2e-44d92a9cd4cc","716-a3e07650-8457-475e-9716-e2653cfb18ec","716-ce480622-d2a0-4640-bc1e-ef17e477fe16","716-d2b5dca2-68ef-41f6-8bac-f080f8985342","716-fdefd083-017e-4c80-a94b-7f277633ae3d","716-69b03089-1c0c-432c-ae9e-f88d1350c050","716-51a19554-0af0-4e79-b667-ff7378f1aa88"], }) </script>
			<?
		}
	} elseif( (is_live() && kristo_roostergrin_flag()) || (is_live() && isset($_GET['rg_test'])) ) {
		//This part is used by all
		?>
		<script src="https://onlineschedulingv2.threadcommunication.com" type="text/javascript"></script>
		<?

		if( kristo_roostergrin_flag() || isset($_GET['rg_test'])) {
			if(in_array($location->ID, [1253, 1251, 1257, 1265, 1249, 1269, 1247])) {
				// Chippewa Valley Region - Eau Claire, Chippewa Falls, Menomonie, Rice Lake, Bloomer, Stanley, Black River Falls
				?>
				<script type="text/javascript"> OpenChair.init({ token:"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvcmlnaW4iOiJodHRwczovL2tyaXN0b29ydGhvZG9udGljcy5jb20iLCJwcmFjdGljZV9pZCI6IjcxNiJ9.Bc8Bc_x7fnX-Ai9Z2O-XQSDdKhOXxfJa6NdRJ7oJ9lM", mainButtonLeft: true, brandName: "Kristo Orthodontics", hideTooltipHeader: true, specificLocations:["716-f4a9f662-d545-4ce6-8c1c-2be7f396dcec","716-a3e07650-8457-475e-9716-e2653cfb18ec","716-4a7b0010-1d80-4f65-80b8-b82165ecc747","716-51a19554-0af0-4e79-b667-ff7378f1aa88","716-d2b5dca2-68ef-41f6-8bac-f080f8985342","716-ce480622-d2a0-4640-bc1e-ef17e477fe16","716-b0705c88-e868-430e-be9b-9ce3a9e9b5b0"], }) </script>
				<?
			} elseif(in_array($location->ID, [1243, 1245, 1263, 1267])) {
				// St Croix Region - Amery, Baldwin, New Richmond, River Falls
				?>
				<script type="text/javascript"> OpenChair.init({ token:"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvcmlnaW4iOiJodHRwczovL2tyaXN0b29ydGhvZG9udGljcy5jb20iLCJwcmFjdGljZV9pZCI6IjcxNiJ9.Bc8Bc_x7fnX-Ai9Z2O-XQSDdKhOXxfJa6NdRJ7oJ9lM", mainButtonLeft: true, brandName: "Kristo Orthodontics", hideTooltipHeader: true, specificLocations:["716-39c50ed1-148b-4387-8ab6-a465496d8bc9","716-e88d2c50-c573-495e-b614-5035556744f7","716-1defe3e0-0943-44a2-96c3-62ca6b55e3da","716-18980ef6-04ad-4d88-ad2e-44d92a9cd4cc"], }) </script>
				<?
			} elseif($location->ID === 1255) {
				// Marinette Region - Marinette
				?>
				<script type="text/javascript"> OpenChair.init({ token:"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvcmlnaW4iOiJodHRwczovL2tyaXN0b29ydGhvZG9udGljcy5jb20iLCJwcmFjdGljZV9pZCI6IjcxNiJ9.Bc8Bc_x7fnX-Ai9Z2O-XQSDdKhOXxfJa6NdRJ7oJ9lM",mainButtonLeft: true, brandName: "Kristo Orthodontics", hideTooltipHeader: true, specificLocations:["716-fdefd083-017e-4c80-a94b-7f277633ae3d"], }) </script>
				<?
			} elseif(in_array($location->ID, [1271, 1259])) {
				// Wausau Region - Wausau, Merrill
				?>
				<script type="text/javascript"> OpenChair.init({ token:"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvcmlnaW4iOiJodHRwczovL2tyaXN0b29ydGhvZG9udGljcy5jb20iLCJwcmFjdGljZV9pZCI6IjcxNiJ9.Bc8Bc_x7fnX-Ai9Z2O-XQSDdKhOXxfJa6NdRJ7oJ9lM",mainButtonLeft: true, brandName: "Kristo Orthodontics", hideTooltipHeader: true, specificLocations:["716-69b03089-1c0c-432c-ae9e-f88d1350c050","716-bb80b5d9-51e1-4906-8240-d200f0095db4"],}) </script>
				<?
			} elseif(empty($location->ID)) {
				// Kristo Brand Home
				?>
				<script type="text/javascript"> OpenChair.init({ token:"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJvcmlnaW4iOiJodHRwczovL2tyaXN0b29ydGhvZG9udGljcy5jb20vIiwicHJhY3RpY2VfaWQiOiI3MTYifQ.6v2LhPU2pEgI0iHry7DqivU7Q2yBTDHQTh56eNGdj_4", mainButtonLeft: true, brandName: "Kristo Orthodontics", hideTooltipHeader: true, specificLocations:["716-4a7b0010-1d80-4f65-80b8-b82165ecc747","716-a3e07650-8457-475e-9716-e2653cfb18ec","716-51a19554-0af0-4e79-b667-ff7378f1aa88","716-bb80b5d9-51e1-4906-8240-d200f0095db4","716-f4a9f662-d545-4ce6-8c1c-2be7f396dcec","716-e88d2c50-c573-495e-b614-5035556744f7","716-d2b5dca2-68ef-41f6-8bac-f080f8985342","716-ce480622-d2a0-4640-bc1e-ef17e477fe16","716-b0de1228-769e-47e1-a29d-2f18e4024003","716-39c50ed1-148b-4387-8ab6-a465496d8bc9","716-18980ef6-04ad-4d88-ad2e-44d92a9cd4cc","716-b0705c88-e868-430e-be9b-9ce3a9e9b5b0","716-fdefd083-017e-4c80-a94b-7f277633ae3d","716-1defe3e0-0943-44a2-96c3-62ca6b55e3da","716-69b03089-1c0c-432c-ae9e-f88d1350c050"], })</script>
				<?
			}
		}
	}
});

//=====================================[INCLUDES]=====================================//
include_once get_stylesheet_directory().'/lib/MDG/autoload.php';
include_once get_stylesheet_directory().'/inc/less-compiler.php';
include_once get_stylesheet_directory().'/inc/reset.php';
include_once get_stylesheet_directory().'/inc/scripts.php';
include_once get_stylesheet_directory().'/inc/shortcodes.php';
include_once get_stylesheet_directory().'/inc/utility-functions.php';
include_once get_template_directory().'/inc/nav-walker.php';

//=====================================[ROUTING]=====================================//
# Update the post link based on the based on the brand relationship
add_filter('post_link', function($post_link, $post, $leavename) {
	global $brands;

	$brand_relationship = get_post_meta($post->ID, 'post_brand_relationship', true);
	if (!empty($brand_relationship)) {
		$current_brand = $brands->brands[$brand_relationship[0]];
		$brand_host_name = brand_host($current_brand);
		$url_array = explode('/', $post_link);
		$current_host_name = $url_array[0].'//'.$url_array[1].$url_array[2];
		$post_link = str_replace($current_host_name, $brand_host_name, $post_link);
		apply_filters('the_permalink', $post_link, $post->ID);
		apply_filters('edit_post_link', $post_link, $post->ID, 'Edit Page');
	}

	// updates admin slug for blog post
	if( wp_get_theme() == 'PEDIATRIC') {
		$post_link = trailingslashit( home_url('/kids-dentist-blog/'. $post->post_name . '/' ) );
	}

	return $post_link;
}, 10, 3);

add_filter( 'category_link', function( $termlink ) {
	if( wp_get_theme() == 'PEDIATRIC') {
		$termlink = str_replace('orthodontic-blog', 'kids-dentist-blog', $termlink);
	}

	return $termlink;
});

# For brand pages and posts, disable auto-incrementing slugs; allow duplicate slugs for brand-level pages and posts
add_filter('pre_wp_unique_post_slug', function($override, $slug, $post_ID, $post_status, $post_type, $post_parent) {
	if($post_type == 'post' || empty($post_type)) return $slug;
	elseif($post_type != 'page' || wp_get_post_parent_id($post_ID) !== 0) return null;
	return $slug;
}, 10, 6);

# Manipulate query for get_page_by_path; defined in wp-includes/post.php:5348
# Resolves duplicate slugs for brand-level pages
add_filter('query', function($query) {
	if(
		!is_admin()
		&& strpos(trim($query), 'SELECT ID, post_name, post_parent, post_type') === 0
		&& strstr($query, "WHERE post_name IN ('")
		&& strstr($query, "AND post_type IN ('page'")
	) {
		global $wpdb;

		$brand = is_brand();
		if(empty($brand) || !is_object($brand) || empty($brand->ID)) return $query;

		$brand_id = absint($brand->ID);

		if(empty($brand_id)) return $query;

		$where = "ID IN (
		SELECT
			DISTINCT i_p.ID
		FROM {$wpdb->postmeta} i_pm
		JOIN {$wpdb->posts} i_p ON
			i_p.ID = i_pm.post_id
			AND i_p.post_type = 'page'
			AND i_p.post_status='publish'
			AND i_p.post_parent=0
			AND i_pm.meta_key = 'page_brand_relationship'
			AND i_pm.meta_value LIKE '%:\"{$brand_id}\";%'
		GROUP BY
			i_p.ID
		) AND";
		$query = str_replace('WHERE post_name IN (', "WHERE {$where} post_name IN (", $query);
	}
	return $query;
}, 10, 1);

# Restrict providers from being accessed directly via URL, unless explicity tagged to the brand/office
add_action('pre_get_posts', function($query) {
	if(!is_admin() && $query->is_main_query() && $query->get('post_type') == 'provider') {
		$provider_ids = array_filter(array_map('absint', array_unique(wp_list_pluck(get_providers() ?? [], 'ID'))));
		if(!empty($provider_ids)) $query->set('post__in', $provider_ids);
	} elseif (!is_admin() && $query->is_main_query() && $query->get('post_type') == 'location') {
		$brand_location_ids = wp_list_pluck(get_locations_for_brand(is_brand()->ID), 'ID');
		if(!empty($brand_location_ids)) $query->set('post__in', $brand_location_ids);
	}
}, 10, 1);

# Restrict blog posts from being accessed directly via URL, unless explicity tagged to the brand/office
add_filter('posts_where', function($where, $query) {
	if(
		!is_admin()
		&& $query->is_main_query()
		&& strstr($where, ".post_type = 'post'")
		&& strstr($where, ".post_name = '")
		&& $query->get('post_type') == ''
	) {
		global $wpdb;
		$brand = is_brand();

		$q = new WP_Query([
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => 'post_brand_relationship',
					'value' => $brand->ID,
					'compare' => 'LIKE'
				],
			],
			'fields' => 'ids',
		]);

		$post_ids = array_unique(array_filter(array_map('absint', $q->posts)));
		if(!empty($post_ids)) {
			$where .= ' AND '.$wpdb->posts.'.ID IN ('.implode(',', $post_ids).')';
		}

		# Disable canonical redirects (based on GUID) when the URL is truly a 404 on the website requested
		add_filter('redirect_canonical', function($redirect_url, $requested_url) {
			return null;
		}, 10, 2);
	}
	return $where;
}, 10, 2);

# Update the page link based on the brand relationship
add_filter('page_link', function($page_link, $post_id, $sample) {
	global $brands;
	$brand_relationship = get_post_meta($post_id, 'page_brand_relationship', true);
	if (!empty($brand_relationship)) {
		$current_brand = $brands->brands[$brand_relationship[0]];
		$brand_host_name = brand_host($current_brand);
		$url_array = explode('/', $page_link);
		$current_host_name = $url_array[0].'//'.$url_array[1].$url_array[2];
		$page_link = str_replace($current_host_name, $brand_host_name, $page_link);
		apply_filters('the_permalink', $page_link, $post_id);
		apply_filters('edit_post_link', $page_link, $post_id, 'Edit Page');
	}

	if(is_brand() == 'southmoor' && get_post_type($post_id) == 'post'){
		print_stmt($page_link);
	}

	return $page_link;
}, 10, 3);

# Adding allowed origins for cors policy
add_filter('allowed_http_origins', function($origins) {
	$origins[] = 'https://local-southmoorkids-com.mdgadvertising.com';
	$origins[] = 'https://local-greatriverortho-com.mdgadvertising.com';
	$origins[] = 'https://dev-greatriverortho-com.mdgadvertising.com';
	$origins[] = 'https://admin-greatriverortho-com.mdgadvertising.com';
	$origins[] = 'https://greatriverortho.com';
	$origins[] = 'https://local-kristoorthodontics-com.mdgadvertising.com';
	$origins[] = 'https://dev-kristoorthodontics-com.mdgadvertising.com';
	$origins[] = 'https://admin-kristoorthodontics-com.mdgadvertising.com';
	$origins[] = 'https://kristoorthodontics.com';
	return $origins;
});

# Adding to ignore cors policy
add_action('wp_headers', function($headers) {
	$headers['access-control-allow-origin'] = '*';
	return $headers;
});

# Prepend "orthodontist-office" to the office name in URL permastructs where pages are tagged to offices
add_filter('get_page_uri', function($uri, $page) {
	global $wp_post_types;
	if (
		get_post_type($page) === 'page'
		&& get_post_type($page->post_parent) === 'location'
		&& !empty($wp_post_types['location']->rewrite['slug'])
	) {
		return $wp_post_types['location']->rewrite['slug'].'/'.$uri;
	}

	return $uri;
}, 10, 2);

# Update the post_parent of a page to an office ("location") ID when tagged to an office
add_action('wp_after_insert_post', function($post_id, $post, $update) {
	if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
		return;
	}

	if ($post->post_type == 'page') {
		if (!empty($_POST['acf']['page_location_parent']) && empty($GLOBALS['__original_wp_after_insert_post'])) {
			$post_data = [
				'ID' => $post_id,
				'post_name' => $_POST['post_name'],
				'post_parent' => $_POST['acf']['page_location_parent'],
			];
			$GLOBALS['__original_wp_after_insert_post'] = true;
			wp_update_post($post_data);
		}
	}
}, 10, 3);

# Virtualize pages tagged to locations (URL segment length = 3)
add_action('wp', function($template) {
	global $wp, $wp_post_types;

	$brand = is_brand();
	$url_segments = explode('/', $wp->request);
	$this_type = null;
	$post_parent = null;
	$post_name = null;

	$blog_key = 'kids-dentist-blog';
	$provider_key = 'pediatric-dental-team';
	$location_key = 'pediatric-dentist';


	if( is_404() ) {

		if( count( $url_segments ) == 2 ) {
			$post_name = $url_segments[1];
			$post_subject = $url_segments[0];
			$post_parent = 0;
			$post_type = get_post_type_from_subject($post_subject);

			$p = virtual_redirect_get_post( $post_type, $post_parent, $post_name, $post_subject, $url_segments );

			if(!empty($p) && !empty($p->ID)) {
				do_virtual_page(get_post($p->ID));
				virtual_redirect_seo( $p->ID );
				virtual_redirect_template_assignment( $post_type, $this_type );
			}
		}

		if ( !empty($wp_post_types['location']->rewrite['slug']) && !is_single_location_brand() ) {
			if (count($url_segments) == 3) {
				if($url_segments[0] == $blog_key){
					if($url_segments[1] == 'category') {
						$post_type = 'post';
						$this_type = 'archive';
						$term = get_term_by('slug', $url_segments[2], 'category');
						$meta_title = $term->name;

						status_header(200);
						add_filter('wp_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
						add_filter('aioseop_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
						virtual_redirect_template_assignment( $post_type, $this_type );
						exit;
					}
				}

				if($url_segments[0] == $location_key) {
					$location = get_page_by_path($url_segments[1], OBJECT, 'location');
					$post_name = $url_segments[2];
					$post_parent = $location->ID ?? 0;
					$post_type = 'page';
				}
			}

			if (count($url_segments) == 4) {
				if($url_segments[0] == $location_key) {
					$location = get_page_by_path($url_segments[1], OBJECT, 'location');
					$post_name = $url_segments[3];
					$post_subject = $url_segments[2];
					$post_parent = 0;
					$post_type = get_post_type_from_subject($post_subject);
				}
			}

			if (count($url_segments) == 5) {
				if($url_segments[3] === 'category') {
					$post_type = 'post';
					$this_type = 'archive';

					$term = get_term_by('slug', $url_segments[4], 'category');
					$meta_title = $term->name;

					status_header(200);
					add_filter('wp_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
					add_filter('aioseop_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
					virtual_redirect_template_assignment( $post_type, $this_type );
					exit;
				}
			}

			$p = virtual_redirect_get_post( $post_type, $post_parent, $post_name, $post_subject, $url_segments );
			if(!empty($p) && !empty($p->ID)) {
				do_virtual_page(get_post($p->ID));
				virtual_redirect_seo( $p->ID );
				virtual_redirect_template_assignment( $post_type, $this_type );
			}
		}

		if( $url_segments[0] === $location_key ) {
			if(count($url_segments) == 2) {
				$post_name = $url_segments[1];
				$post_parent = 0;
				$post_type = 'location';
				$p = virtual_redirect_get_post( $post_type, $post_parent, $post_name );

				// don't know if this is ever accessed???
				print_stmt('entered inside the location function', 1);

				if(!empty($p) && !empty($p->ID)) {
					do_virtual_page(get_post($p->ID));
					virtual_redirect_seo( $p->ID );
					virtual_redirect_template_assignment( $post_type );
				}
			} elseif( count($url_segments) === 1 ) {
				$meta_title = 'Locations';
				$meta_description = 'Find an pediatric dentist near you! Serving communities in Centennial and Denver.';

				add_filter('wp_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
				add_filter('aioseop_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
				add_filter('aioseop_description', function($text) use ($meta_description) { return $meta_description; });

				status_header(200);

				include_once get_stylesheet_directory().'/archive-location.php';
				exit;
			}
		}

		if( $url_segments[0] === $blog_key ) {
			if(count($url_segments) == 2) {
				$post_name = $url_segments[1];
				$post_subject = $url_segments[0];
				$post_parent = 0;
				$post_type = get_post_type_from_subject($post_subject);

				$p = virtual_redirect_get_post( $post_type, $post_parent, $post_name );

				if(!empty($p) && !empty($p->ID)) {
					do_virtual_page(get_post($p->ID));
					virtual_redirect_seo( $p->ID );
					virtual_redirect_template_assignment( $post_type );
				}
			}
		}

		if( $url_segments[0] === $provider_key ) {
			if(count($url_segments) == 2) {
				$post_name = $url_segments[1];
				$post_parent = 0;
				$post_type = get_post_type_from_subject($provider_key);

				$p = virtual_redirect_get_post( $post_type, $post_parent, $post_name );

				if(!empty($p) && !empty($p->ID)) {
					do_virtual_page( get_post( $p->ID ) );
					virtual_redirect_seo( $p->ID );
					virtual_redirect_template_assignment( $post_type );
				}
			}
		}
	}
});

function get_post_type_from_subject($subject) {
	$blog_key = 'kids-dentist-blog';
	$provider_key = 'pediatric-dental-team';
	$location_key = 'pediatric-dentist';

	$post_type = '';

	if ($subject === $blog_key) $post_type = 'post';
	if ($subject === $provider_key) $post_type = 'provider';
	if ($subject === $location_key) $post_type = 'location';

	return $post_type;
}

function virtual_redirect_seo($id) {
	$meta_title = get_post_meta($id, '_aioseop_title', true);
	$meta_description = get_post_meta($id, '_aioseop_description', true);

	add_filter('wp_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
	add_filter('aioseop_title', function($text) use ($meta_title) { return $meta_title.' | '.do_shortcode('[BRAND_TITLE]'); });
	add_filter('aioseop_description', function($text) use ($meta_description) { return $meta_description; });
}

function virtual_redirect_template_assignment( $post_type, $this_type = null ) {

	if( $this_type != null ){
		if ($post_type === 'post' && $this_type === 'archive') {
			include_once get_stylesheet_directory().'/templates/blog.php';
			exit;
		}
	}

	switch ($post_type) {
		case 'post':
			include_once get_stylesheet_directory().'/single.php';
			break; exit;

		case 'provider':
			include_once get_stylesheet_directory().'/single-provider.php';
			break; exit;

		case 'location':
			include_once get_stylesheet_directory().'/single-location.php';
			break; exit;
	}
}

function virtual_redirect_get_post( $post_type, $post_parent, $post_name, $post_subject = null, $url_segments = null ) {
	if( !empty($post_type) ){
		$query_params = [
			'post_type' => $post_type,
			'post_parent' => $post_parent,
			'name' => $post_name,
			'posts_per_page' => 1,
			'post_status' => 'publish',
		];

		if( $url_segments != null && $post_subject == 'kids-dentist-blog' ){
			$brand = is_brand();
			$query_params['meta_query'] = [
				[
					'key' => 'post_brand_relationship',
					'value' => $brand->ID,
					'compare' => 'LIKE'
				]
			];
		}

		$q = new WP_Query($query_params);
		return current($q->posts);
	} else {
		return array(); // is this the right empty type to send back?
	}
}

// Send location home page for single-location brands to 404
add_action('wp', function() {
	if (is_singular('location') && is_single_location_brand()) {
	  global $wp_query;
	  $wp_query->set_404();
	}
});

add_filter('template_include', function($template) {
	if(is_404()) return get_stylesheet_directory().'/templates/404.php';
	return $template;
});

// Theme defaults
add_action('init', function() {
	unregister_taxonomy_for_object_type('post_tag', 'post');
	register_nav_menus([
		'super' => 'Super Navigation',
		'header' => 'Header Navigation',
		'mobile' => 'Mobile Navigation',
		'footer' => 'Footer Navigation',
	]);

	add_filter('pre_option_siteurl', function() {
		return empty(brand_host()) ? '' : rtrim(brand_host(), '/');
	});

	add_filter('pre_option_home', function() {
		return empty(brand_host()) ? '' : rtrim(brand_host(), '/');
	});
});

//=====================================[TEMPLATES]=====================================//
function ppc_slugs($relative_url, $all = true) {
	$main = ['schedule-orthodontic-consultation'];
	$exceptions = ['schedule-free-consultation-cwi', 'schedule-free-consultation-ewi', 'schedule-your-free-consultation-scv', 'free-consultation-minnesota', 'free-consultation-wisconsin', 'no-cost-consultation'];
	if($all) {
		//Returns true if any ppc region
		return in_array($relative_url, array_merge($main, $exceptions));
	} else {
		//Returns true only for the exceptions. Currently the exceptions do not get the Kristo_Bestof_2014 image.
		return in_array($relative_url, $exceptions);
	}
}

function ppc_fallback_tel($relative_url, $format = NULL) {
	$val = '';
	switch($relative_url) {
		default;
		case 'schedule-orthodontic-consultation':
			$val = '7158355182';
			break;
		case 'schedule-free-consultation-cwi':
			$val = '7158425459';
			break;
		case 'schedule-free-consultation-ewi':
			$val = '7157357666';
			break;
		case 'schedule-your-free-consultation-scv':
			$val = '7152465165';
			break;
		case 'free-consultation-wisconsin':
			$val = '6087826778';
			break;
		case 'free-consultation-minnesota':
			$val = '6517778182';
			break;
	}
	if(!$format) {
		return $val;
	} else {
		return format_tel($val, $format);
	}
}

function format_tel($tel, $format) {
	switch ($format) {
		default:
		case 'dot':
			return  (substr($tel, 0, 3)) . '.' . (substr($tel, 3, 3)) . '.' . (substr($tel, 6, 4));
			break;
		case 'dash':
			return  (substr($tel, 0, 3)) . '-' . (substr($tel, 3, 3)) . '-' . (substr($tel, 6, 4));
			break;
		case 'parentheses':
			return  '(' . (substr($tel, 0, 3)) . ') ' . (substr($tel, 3, 3)) . '-' . (substr($tel, 6, 4));
			break;
	}
}

// Filter open graph images
add_filter('aiosp_opengraph_meta', function($value, $property, $subproperty) {
	global $post;

	$brand = is_brand();

	if(in_array($subproperty, ['published_time', 'modified_time'])) {
		return;
	}

	if ($post->post_type == 'post') {
		$attachment_url = get_the_post_thumbnail_url($post);

		switch($subproperty) {
			case 'thumbnail':
				$value = $attachment_url;
				break;
			case 'thumbnail_1':
				$value = $attachment_url;
				break;
			case 'twitter_thumbnail':
				$value = $attachment_url;
				break;
		}
	} else {
		if ($brand->post_title === 'Kristo Orthodontics') {
			switch($subproperty) {
				case 'thumbnail':
					$value = brand_host().'/wp-content/uploads/2021/02/Kristo_Orthodontics_RGB_color-1200x628-1.png';
					break;
				case 'thumbnail_1':
					$value = brand_host().'/wp-content/uploads/2021/02/Kristo_Orthodontics_RGB_color-1200x628-1.png';
					break;
				case 'twitter_thumbnail':
					$value = brand_host().'/wp-content/uploads/2021/02/Kristo_Orthodontics_RGB_color-1200x628-1.png';
					break;
			}
		} elseif ($brand->post_title === 'Prairie Grove Orthodontics') {
			switch($subproperty) {
				case 'thumbnail':
					$value = brand_host().'/wp-content/uploads/2021/08/PGO_OG_1200x628.jpeg';
					break;
				case 'thumbnail_1':
					$value = brand_host().'/wp-content/uploads/2021/08/PGO_OG_1200x628.jpeg';
					break;
				case 'twitter_thumbnail':
					$value = brand_host().'/wp-content/uploads/2021/08/PGO_OG_1200x628.jpeg';
					break;
			}
		} elseif ($brand->post_title === 'Great River Orthodontics') {
			switch($subproperty) {
				case 'thumbnail':
					$value = brand_host().'/wp-content/uploads/2021/05/GreatRiver_1200x628-01.png';
					break;
				case 'thumbnail_1':
					$value = brand_host().'/wp-content/uploads/2021/05/GreatRiver_1200x628-01.png';
					break;
				case 'twitter_thumbnail':
					$value = brand_host().'/wp-content/uploads/2021/05/GreatRiver_1200x628-01.png';
					break;
			}
		}
	}

	return $value;
}, 10, 3);

add_filter('aioseop_canonical_url', function($url) {
	global $wp;

	$segments = explode('/', $wp->request);
	$relative_url = get_relative_url($wp->request);
	$url = empty($relative_url) ? brand_url('/') : brand_url('/'.get_relative_pediatric_location_url($relative_url).'/');
	$this_type = $post_name = null;
	$post_parent = 0;

	if(count($segments) == 3 || count($segments) == 4 || count($segments) == 5) {
		$location = get_page_by_path($segments[1], OBJECT, 'location');
		if(empty($location)) return $url;

		if (count($segments) == 3) {
			$post_name = $segments[2];
			$post_parent = $location->ID;
			$post_type = 'page';
		}

		if (count($segments) == 4) {
			$post_name = $segments[3];
			$post_subject = $segments[2];

			if ($post_subject === 'kids-dentist-blog') $post_type = 'post';
			if ($post_subject === 'pediatric-dental-team') $post_type = 'provider';
			if ($post_subject === 'pediatric-dentist') $post_type = 'location';
		}

		if (count($segments) == 5 && $segments[3] === 'category') {
			$post_type = 'post';
			$this_type = 'archive';
		}

		if($this_type == 'archive') {
            $term = get_term_by('slug', $segments[4], 'category');
            $term_path = get_relative_url( get_term_link( $term->term_id ) );
            $url = brand_host().'/'.get_relative_pediatric_location_url($segments[0]).'/'.$segments[1].'/'.($term_path).'/';
            return $url;
        }

		$q = new WP_Query([
			'post_type' => $post_type,
			'post_parent' => $post_parent,
			'name' => $post_name,
			'posts_per_page' => 1,
			'post_status' => 'publish',
		]);

		$p = current($q->posts);
		$relative_url = get_relative_url(get_permalink($p->ID));
		$url = brand_host().'/'.get_relative_pediatric_location_url($relative_url).'/';

		if(
            ($post_subject === 'pediatric-dental-team' && $post_type === 'provider') ||
            ($post_type === 'post' && $this_type != 'archive')
          ) {
            $url = brand_host().'/'.get_relative_pediatric_location_url($segments[0]).'/'.$segments[1].'/'.get_relative_pediatric_provider_url($relative_url).'/';
        }
	}

	return $url;
}, 1e6, 1);

// All in One SEO sitemap.xml filters
add_filter('aioseo_sitemap_indexes', function( $indexes ) {
	// echo $indexes;
	$brand = is_brand();
	$indexes = [];
	$location_objects = get_locations_for_brand($brand->ID);
	usort($location_objects, function($a, $b) {
		return $a->post_title <=> $b->post_title;
	});
	if ($sitemap_type == 'root') {
		$indexes[] = [
			'loc' => brand_host().'/post-sitemap.xml',
			'changefreq' => 'weekly',
			'priority' => 0.7,
		];
		$indexes[] = [
			'loc' => site_url('/page-sitemap.xml'),
			'changefreq' => 'weekly',
			'priority' => 0.7,
		];
		$indexes[] = [
			'loc' => brand_host().'/location-sitemap.xml',
			'changefreq' => 'weekly',
			'priority' => 0.7,
		];
		$indexes[] = [
			'loc' => site_url('/provider-sitemap.xml'),
			'changefreq' => 'weekly',
			'priority' => 0.7,
		];
	} elseif ($sitemap_type == 'post') {
		// Filter out posts for brand
		$posts_sitemap = [];
		$post_slugs = list_all_blogs(true);
		if (!empty($post_slugs)) {
			foreach ($post_slugs as $post) {
				$post_url = get_permalink($post->ID);
				$relative_url = get_relative_url($post_url);
				$posts_sitemap[] = [
					'loc' => brand_url('/'.($relative_url).'/'),
					'changefreq' => 'weekly',
					'priority' => 0.7,
				];
				if (!empty($location_objects && !is_single_location_brand())) {
					foreach ($location_objects as $location) {
						$location_url = brand_url('/'.get_relative_pediatric_location_url($location->relative_url).'/kids-dentist-blog/'.$post->post_name.'/');
						$posts_sitemap[] = [
							'loc' => $location_url,
							'changefreq' => 'weekly',
							'priority' => 0.7,
						];
					}
				}
			}
		}
		$indexes = array_merge($indexes, $posts_sitemap);
	} elseif ($sitemap_type == 'page') {
		// Filter out pages for brand
		$pages_sitemap = [];
		$exclusions = sitemap_exclusions();
		$page_slugs = list_all_pages(true);
		if (!empty($page_slugs)) {
			usort($page_slugs, function($a, $b) {
				return $a->post_title <=> $b->post_title;
			});
			foreach ($page_slugs as $page) {
				$url = get_permalink($page->ID);
				$relative_url = get_relative_url($url);
				if (in_array($page->post_name, $exclusions)) continue;
				$pages_sitemap[] = [
					'loc' => brand_url('/'.get_relative_pediatric_location_url($relative_url).'/'),
					'changefreq' => 'weekly',
					'priority' => 0.7,
				];
			}
		}
		$indexes = array_merge($indexes, $pages_sitemap);
	} elseif ($sitemap_type == 'location') {
		// Filter out pages for brand
		$locations_sitemap = [];
		if (!empty($location_objects)) {
			foreach ($location_objects as $location) {
				$url = !is_single_location_brand() ? brand_url('/'.get_relative_pediatric_location_url($location->relative_url).'/') : brand_url('/');
				$locations_sitemap[] = [
					'loc' => $url,
					'changefreq' => 'weekly',
					'priority' => 0.7,
				];
			}
		}
		$indexes = array_merge($indexes, $locations_sitemap);
	} elseif ($sitemap_type == 'provider') {
		// Filter out pages for brand
		$providers_sitemap = [];
		$provider_objects = get_providers();
		if (!empty($provider_objects)) {
			usort($provider_objects, function($a, $b) {
				return $a->menu_order <=> $b->menu_order;
			});
			foreach ($provider_objects as $provider) {
				$provider_url = brand_url('/'.get_relative_pediatric_provider_url($provider->relative_url).'/');
				$location_provider_relationship = !empty($provider->location_relationship) ? unserialize($provider->location_relationship) : false;
				$providers_sitemap[] = [
					'loc' => $provider_url,
					'changefreq' => 'weekly',
					'priority' => 0.7,
				];
				if (!empty($location_objects && !is_single_location_brand())) {
					foreach ($location_objects as $location) {
						if (in_array($location->ID, $location_provider_relationship)) {
							$location_url = brand_url('/'.get_relative_pediatric_location_url($location->relative_url).'/pediatric-dental-team/'.$provider->post_name.'/');
							$providers_sitemap[] = [
								'loc' => $location_url,
								'changefreq' => 'weekly',
								'priority' => 0.7,
							];
						}
					}
				}
			}
		}

		$indexes = array_merge($indexes, $providers_sitemap);
	}

	return $indexes;
}, 10, 4);

add_filter('body_class', function($class) {
	global $wp;
	$brand = is_brand();
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$current_url = explode('?', $current_url)[0];
	$location = is_location();
	$relative_url = get_relative_url($current_url);
	$relative_array = explode('/', $relative_url);
	$posts = list_all_blogs(true);
	$post_slugs = list_all_blogs();
	$body_class = [];
	if (starts_with($relative_url, 'orthodontic-blog/category/')) {
		// Post category
		$body_class = ['archive'];
		if (is_date()) $body_class[] = 'date';
		if (absint(basename($relative_url))) $body_class[] = 'page';
		if (strstr($relative_url, '/category/') && !in_array('page', $body_class)) $body_class = array_merge($body_class, ['category', 'category-'.basename($relative_url)]);
	} elseif (in_array(end($relative_array), $post_slugs)) {
		// Posts
		$current_post = false;
		if (in_array(end($relative_array), $post_slugs)) {
			foreach ($posts as $p) {
				if ($p->post_name === end($relative_array)) $current_post = $p;
			}
		}
		$body_class = array_merge($body_class, ['post-template-default', 'single', 'single-post', 'postid-'.$current_post->ID]);
	} elseif (rtrim($current_url, '/') === brand_host()) {
		$body_class = array_merge($body_class, ['page-template', 'page-template-templates', 'page-template-brand-home', 'default-map-view']);
	} elseif (!empty($location) && $current_url === get_permalink($location->ID)) {
		$body_class = array_merge($body_class, ['page-template', 'page-template-templates', 'page-template-location-home']);
	} elseif (strstr($relative_url, 'orthodontic-team') || strstr($relative_url, 'pediatric-dental-team')) {
		$body_class = array_merge($body_class, ['page-template', 'page-template-templates', 'page-template-bio', 'default-map-view', end($relative_array)]);
	} else {
		// Pages
		if (ppc_slugs(end($relative_array))) {
			$body_class = array_merge($body_class, ['page-template', 'page-template-templates', 'ppc-nav', 'page-template-'.str_replace('-2', '', end($relative_array))]);
		} else {
			$body_class = array_merge($body_class, ['page-template', 'page-template-templates', 'page-template-'.str_replace('-2', '', end($relative_array))]);
		}
	}

	// add brand to body tag class
    if($brand != false) $body_class = array_merge($body_class, [$brand->post_name]);

	return $body_class;
});

add_filter('robots_txt', function($output) {
	if(!is_live()) {
	ob_start();
?>
User-agent: *
Disallow: /
<?
	return ob_get_clean();
	}

	return $output;
});

// Noindex, nofollow
add_filter('option_blog_public', function($val) {
	return is_live() ? 1 : 0;
});

add_action('wp_dashboard_setup', function() {
	wp_add_dashboard_widget(
		sanitize_title('Form Submissions Summary'), 'Form Submissions Summary', function() {
			include_once get_stylesheet_directory().'/inc/form-submissions/form_submission_manager.php';
			$table = new Form_Submissions_Table();
			$forms = $table->mapping;
			?>
			<div id="dashboard_right_now">
				<p><strong>Today&rsquo;s Submissions</strong></p>
				<ul>
					<?
						foreach($forms as $form):
							$form_total = $table->get_total_for_form($form, false, true);
					?>
					<li class="page-count">
						<a href="<?= admin_url('admin.php?page=form-submissions&form-name='.$form) ?>"><?= get_form_name($form) ?> Form: <?= number_format($form_total) ?></a>
					</li>
					<? endforeach ?>
				</ul>
				<hr />
				<p><strong>All-Time Submissions</strong></p>
				<ul>
					<?
						foreach($forms as $form):
							$form_total = $table->get_total_for_form($form, false, false);
					?>
					<li class="page-count">
						<a href="<?= admin_url('admin.php?page=form-submissions&form-name='.$form) ?>"><?= get_form_name($form) ?> Form: <?= number_format($form_total) ?></a>
					</li>
					<? endforeach ?>
				</ul>
			</div>
			<?
		}
	);
});

function load_more_blog_posts() {
	if (empty($_POST) || empty($_POST['page'])) wp_send_json_error('You must have a page set');
	global $locations;

	$brand = is_brand();
	$location = is_location();
	$page = $_POST['page'] ?? 2;
	$post_type = 'post';
	$post_status = 'publish';
	$posts_per_page = $_POST['posts_per_page'] ?? 4;
	$query_args = [
		'paged' => $page,
		'post_type' => $post_type,
		'post_status' => $post_status,
		'posts_per_page' => $posts_per_page + 1,
		'meta_query' => [
			[
				'key' => 'post_brand_relationship',
				'value' => $brand->ID,
				'compare' => 'LIKE'
			]
		]
	];
	$term_id = absint($_POST['t_id'] ?? '');
	$entity_id = absint($_POST['e_id'] ?? '');
	if (!empty($term_id)) $query_args['cat'] = $term_id;

	$current_entity = null;
	if($brand->ID == $entity_id) $current_entity = $brand;
	elseif(empty($location) && !empty($locations->locations ?? [])) {
		foreach($locations->locations as $office) {
			if($office->ID == $entity_id) {
				$current_entity = $office;
				break;
			}
		}
	}

	if(empty($current_entity)) {
		wp_send_json_error([]);
	}

	# Entity context switch
	$original_entity = $platform_entity;
	$platform_entity = $current_entity;

	ob_start();
	$posts = new WP_Query($query_args);
	if ($posts->have_posts()) {
		$results = $posts->posts;
		$first_post = array_pop($posts->posts);
		foreach ($results as $r) {
			$p = get_post($r->ID);

			$small_image_src_1x = wp_get_attachment_image_src(get_post_thumbnail_id($p), 'medium_large', false);
			$small_image_src_2x = wp_get_attachment_image_src(get_post_thumbnail_id($p), '1536x1536', false);
			$small_image = !empty(get_post_thumbnail_id($p)) ? responsive_static_img(['src' => str_replace('http:', 'https:', $small_image_src_1x[0]), 'srcset' => str_replace('http:', 'https:', $small_image_src_1x[0]).' 1x, '.str_replace('http:', 'https:', $small_image_src_2x[0]).' 2x', 'sizes' => '100vw', 'width' => $small_image_src_1x[1], 'height' => $small_image_src_1x[2], 'alt' => !empty(get_post_meta(get_post_thumbnail_id($p), '_wp_attachment_image_alt', true)) ? get_post_meta(get_post_thumbnail_id($p), '_wp_attachment_image_alt', true) : str_replace('_', ' ', get_the_title(get_post_thumbnail_id($p))), 'class' => '']) : '';
			partial('widget.blog.small', [
				'image' => $small_image,
				'classes' => ['animate__animated animate__zoomInDown'],
				'content' => [
					'h3' => $p->post_title,
					'h3_classes' => ['h4', 'white'],
					'categories' => wp_get_post_categories($p->ID),
					'copy' => excerptizeCharacters($p->post_content, 198),
					'cta' => [
						'href' => brand_url('/kids-dentist-blog/'.basename(get_permalink($p->ID)).'/'),
						'classes' => ['cta', 'primary', 'white'],
						'text' => 'Read article',
					]
				]
			]);
		}
	}
	wp_reset_postdata();

	# Restore current entity context
	$platform_entity = $original_entity;

	$html = ob_get_clean();
	$response = (object)[
		'more' => $page < $posts->max_num_pages,
		'html' => $html,
		'max_pages' => $posts->max_num_pages,
	];
	die(json_encode($response));
}
add_action('wp_ajax_blog_page', 'load_more_blog_posts');
add_action('wp_ajax_nopriv_blog_page', 'load_more_blog_posts');

/*	Add back end filters to post types	*/
//	Custom dropdown meta_box_cb
function cb_taxonomy_brand_select_meta_box($post, $box) {
	$defaults = ['taxonomy' => 'brand'];

	if (!isset($box['args']) || !is_array($box['args'])) $args = array();
	else $args = $box['args'];

	extract(wp_parse_args($args, $defaults), EXTR_SKIP);

	$tax = get_taxonomy($taxonomy);
	$selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
	$hierarchical = $tax->hierarchical;
	?>
	<div id="taxonomy-<? echo $taxonomy; ?>" class="selectdiv">
		<?
			if (current_user_can($tax->cap->edit_terms)):
				if ($hierarchical) {
					wp_dropdown_categories(array(
						'taxonomy' => $taxonomy,
						'class' => 'widefat',
						'hide_empty' => false,
						'name' => "tax_input[$taxonomy][]",
						'selected' => count($selected) >= 1 ? $selected[0] : '',
						'orderby' => 'name',
						'hierarchical' => false,
						'show_option_all' => "All"
					));
				} else {
		?>
		<select name="<?= "tax_input[$taxonomy][]"; ?>" class="widefat">
			<option value="0">Select a brand</option>
			<? foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term): ?>
				<option value="<?= esc_attr($term->slug); ?>" <?= selected($term->term_id, count($selected) >= 1 ? $selected[0] : ''); ?>><?= esc_html($term->name); ?></option>
			<? endforeach; ?>
		</select>
		<? 	}
			endif;
		?>
	</div>
	<?
}
function cb_taxonomy_page_name_select_meta_box($post, $box) {
	$defaults = ['taxonomy' => 'page_name'];

	if (!isset($box['args']) || !is_array($box['args'])) $args = array();
	else $args = $box['args'];

	extract(wp_parse_args($args, $defaults), EXTR_SKIP);

	$tax = get_taxonomy($taxonomy);
	$selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
	$hierarchical = $tax->hierarchical;
	?>
	<div id="taxonomy-<? echo $taxonomy; ?>" class="selectdiv">
		<?
			if (current_user_can($tax->cap->edit_terms)):
				if ($hierarchical) {
					wp_dropdown_categories(array(
						'taxonomy' => $taxonomy,
						'class' => 'widefat',
						'hide_empty' => false,
						'name' => "tax_input[$taxonomy][]",
						'selected' => count($selected) >= 1 ? $selected[0] : '',
						'orderby' => 'name',
						'hierarchical' => false,
						'show_option_all' => "All"
					));
				} else {
		?>
		<select name="<?= "tax_input[$taxonomy][]"; ?>" class="widefat">
			<option value="0">Select a page</option>
			<? foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term): ?>
				<option value="<?= esc_attr($term->slug); ?>" <?= selected($term->term_id, count($selected) >= 1 ? $selected[0] : ''); ?>><?= esc_html($term->name); ?></option>
			<? endforeach; ?>
		</select>
		<? 	}
			endif;
		?>
	</div>
	<?
}
function cb_taxonomy_placement_select_meta_box($post, $box) {
	$defaults = ['taxonomy' => 'placement'];

	if (!isset($box['args']) || !is_array($box['args'])) $args = array();
	else $args = $box['args'];

	extract(wp_parse_args($args, $defaults), EXTR_SKIP);

	$tax = get_taxonomy($taxonomy);
	$selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
	$hierarchical = $tax->hierarchical;
	?>
	<div id="taxonomy-<? echo $taxonomy; ?>" class="selectdiv">
		<?
			if (current_user_can($tax->cap->edit_terms)):
				if ($hierarchical) {
					wp_dropdown_categories(array(
						'taxonomy' => $taxonomy,
						'class' => 'widefat',
						'hide_empty' => false,
						'name' => "tax_input[$taxonomy][]",
						'selected' => count($selected) >= 1 ? $selected[0] : '',
						'orderby' => 'name',
						'hierarchical' => false,
						'show_option_all' => "All"
					));
				} else {
		?>
		<select name="<?= "tax_input[$taxonomy][]"; ?>" class="widefat">
			<option value="0">Select a placement</option>
			<? foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term): ?>
				<option value="<?= esc_attr($term->slug); ?>" <?= selected($term->term_id, count($selected) >= 1 ? $selected[0] : ''); ?>><?= esc_html($term->name); ?></option>
			<? endforeach; ?>
		</select>
		<? 	}
			endif;
		?>
	</div>
	<?
}
//	Register custom taxonomies for attachments
add_action('init', function() {
	register_taxonomy('brand', ['attachment'], [
		'labels' => [
			'name'              => 'Brands',
			'singular_name'     => 'Brand',
			'search_items'      => 'Search Brands',
			'all_items'         => 'All Brands',
			'parent_item'       => 'Parent Brand',
			'parent_item_colon' => 'Parent Brand:',
			'edit_item'         => 'Edit Brand',
			'update_item'       => 'Update Brand',
			'add_new_item'      => 'Add New Brand',
			'new_item_name'     => 'New Brand Name',
			'menu_name'         => 'Brand',
		],
		'show_ui' => true,
		'show_in_menu' => true,
		'meta_box_cb' => 'cb_taxonomy_brand_select_meta_box',
		'hierarchical' => false,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true,
	]);

	register_taxonomy('page_name', ['attachment'], [
		'labels' => [
			'name'              => _x('Pages', 'page_name'),
			'singular_name'     => _x('Page', 'page_name'),
			'search_items'      => _x('Search Pages', 'page_name'),
			'all_items'         => _x('All Pages', 'page_name'),
			'parent_item'       => _x('Parent Page', 'page_name'),
			'parent_item_colon' => _x('Parent Page:', 'page_name'),
			'edit_item'         => _x('Edit Page', 'page_name'),
			'update_item'       => _x('Update Page', 'page_name'),
			'add_new_item'      => _x('Add New Page', 'page_name'),
			'new_item_name'     => _x('New Page Name', 'page_name'),
			'menu_name'         => _x('Page', 'page_name'),
		],
		'show_ui' => true,
		'show_in_menu' => true,
		'meta_box_cb' => 'cb_taxonomy_page_name_select_meta_box',
		'hierarchical' => false,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true,
	]);

	register_taxonomy('placement', ['attachment'], [
		'labels' => [
			'name'              => _x('Placements', 'placement'),
			'singular_name'     => _x('Placement', 'placement'),
			'search_items'      => _x('Search Placements', 'placement'),
			'all_items'         => _x('All Placements', 'placement'),
			'parent_item'       => _x('Parent Placement', 'placement'),
			'parent_item_colon' => _x('Parent Placement:', 'placement'),
			'edit_item'         => _x('Edit Placement', 'placement'),
			'update_item'       => _x('Update Placement', 'placement'),
			'add_new_item'      => _x('Add New Placement', 'placement'),
			'new_item_name'     => _x('New Placement Name', 'placement'),
			'menu_name'         => _x('Placement', 'placement'),
		],
		'show_ui' => true,
		'show_in_menu' => true,
		'meta_box_cb' => 'cb_taxonomy_placement_select_meta_box',
		'hierarchical' => false,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true,
	]);
});
//	Enqueue and localize JS scripts to filter attachments
add_action('wp_enqueue_media', function() {
	wp_enqueue_script('media-library-taxonomy-filter', get_stylesheet_directory_uri().'/js/collection-filter.js', ['media-editor', 'media-views']);
	// Load 'terms' into a JavaScript variable that collection-filter.js has access to
	wp_localize_script('media-library-taxonomy-filter', 'BrandMediaLibraryTaxonomyFilterData', [
		'terms' => get_terms('brand', ['hide_empty' => false])
	]);

	wp_localize_script('media-library-taxonomy-filter', 'PageMediaLibraryTaxonomyFilterData', [
		'terms' => get_terms('page_name', ['hide_empty' => false])
	]);

	wp_localize_script('media-library-taxonomy-filter', 'PlacementMediaLibraryTaxonomyFilterData', [
		'terms' => get_terms('placement', ['hide_empty' => false])
	]);
	// Overrides back end code styling
	add_action( 'admin_footer', function(){
		echo '<style>
			.attachments-browser .media-toolbar-secondary {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flexbox;
				display: -webkit-flex;
				display: flex;
				width: 66%;
				position: relative;
			}
			.attachments-browser .media-toolbar-secondary select.attachment-filters {
				-webkit-flex-grow:0;
				-webkit-flex-shrink:1;
				-webkit-flex-basis:calc(20% - 12px);
				-webkit-box-flex: 0;
				-moz-box-flex: 0;
				-webkit-flex: 0 1 calc(20% - 12px);
				-ms-flex: 0 1 calc(20% - 12px);
				flex: 0 1 calc(20% - 12px);
				margin-right: 5px;
			}
			.attachments-browser .media-toolbar-secondary select.attachment-filters,
			.attachments-browser .media-toolbar-secondary button {
				-webkit-align-self: center;
				-ms-flex-item-align: center;
				align-self: center;
			}
			.media-toolbar.wp-filter .view-switch {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flexbox;
				display: -webkit-flex;
				display: flex;
			}
			.wp-filter .search-form input[type=search] {
				width: 80%;
			}
			.media-frame.mode-grid .spinner {
				position: absolute;
				right: -20px;
				top: 10px;
			}
			@media screen and (max-width:1450px) {
				.media-frame.mode-grid .media-toolbar {
					display: -webkit-box;
					display: -moz-box;
					display: -ms-flexbox;
					display: -webkit-flex;
					display: flex;
					-webkit-box-orient: vertical;
					-moz-box-orient: vertical;
					-webkit-flex-direction: column;
					-ms-flex-direction: column;
					flex-direction: column;
				}
				.attachments-browser .media-toolbar-secondary,
				.attachments-browser .media-toolbar-primary {
					width: 90%;
					max-width: 90%;
				}
			}
		</style>';
	});
});

//	Filter menus in list view
function filter_attachments_by_brand_filter() {
    $screen = get_current_screen();
    if ( 'upload' == $screen->id ) {
        $dropdown_options = array(
            'taxonomy' => 'brand',
            'show_option_all' => __( 'View all Brands', 'Brands' ),
            'hide_empty' => false,
            'hierarchical' => false,
            'value_field'       => 'slug',
            'name'              => 'brand',
            'orderby' => 'name', );
        wp_dropdown_categories( $dropdown_options );

		$dropdown_options_page = array(
            'taxonomy' => 'page_name',
            'show_option_all' => 'View all Pages',
            'hide_empty' => false,
            'hierarchical' => false,
            'value_field'       => 'slug',
            'name'              => 'page_name',
            'orderby' => 'name', );
        wp_dropdown_categories( $dropdown_options_page );

		$dropdown_options_placement = array(
            'taxonomy' => 'placement',
            'show_option_all' => __( 'View all Placements', 'Placements' ),
            'hide_empty' => false,
            'hierarchical' => false,
            'value_field'       => 'slug',
            'name'              => 'placement',
            'orderby' => 'name', );
        wp_dropdown_categories( $dropdown_options_placement );
    }
}
add_action( 'restrict_manage_posts', 'filter_attachments_by_brand_filter' );

//	Filter Pages and Posts by Brand
function filter_pages_by_brand_dropdown() {
    $scr = get_current_screen();
    if ( $scr->base == 'edit' && $scr->post_type == 'page' || $scr->post_type == 'post') {
		$scr->post_type == 'page' ? $meta_val = 'page_brand_relationship' : $meta_val = 'post_brand_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $brands;
		$choices = array();
			foreach($brands->brands as $brand) {
				$choices[$brand->ID] = $brand->post_title;
			}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Brands</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_pages_by_brand_dropdown');

function filter_pages_by_brand_filter($query) {
	if(is_admin() && array_key_exists( 'post_type', $_GET )) {
		if ( $query->is_main_query() && $_GET['post_type'] == 'page' || $_GET['post_type'] == 'post') {
			$_GET['post_type'] == 'page' ? $meta_val = 'page_brand_relationship' : $meta_val = 'post_brand_relationship';

			if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all') {
			$query->set('meta_query', array( array(
				'key' => $meta_val,
				'value' => serialize([$_GET[$meta_val]])
			) ) );
			}
		}
	}
}
add_action('pre_get_posts','filter_pages_by_brand_filter');

//	Filter providers
function filter_providers_by_brand_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'provider') {
		$meta_val = 'provider_brand_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $brands;
		$choices = array();
		foreach($brands->brands as $brand) {
			$choices[$brand->ID] = $brand->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Brands</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_providers_by_brand_dropdown');

function filter_providers_by_brand_filter($query) {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'provider' && $query->is_main_query()) {
		$meta_val = 'provider_brand_relationship';
		if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all') {
			$query->set('meta_query', array( array(
				'key' => $meta_val,
				'value' => '"' . $_GET[$meta_val] . '"',
				'compare' => 'LIKE'
			) ) );
		}
    }
}
add_action('pre_get_posts','filter_providers_by_brand_filter');

//	Filter Smile Transformations
function filter_smile_transformations_by_region_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'smile_transformation') {
		$meta_val = 'smile_transformation_region_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $regions;
		$choices = array();
		foreach($regions->regions as $region) {
			$choices[$region->ID] = $region->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All regions</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_smile_transformations_by_region_dropdown');

function filter_smile_transformations_by_region_filter($query) {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'smile_transformation' && $query->is_main_query()) {
		$meta_val = 'smile_transformation_region_relationship';
		if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all') {
			$query->set('meta_query', array( array(
				'key' => $meta_val,
				'value' => '"' . $_GET[$meta_val] . '"',
				'compare' => 'LIKE'
			) ) );
		}
    }
}
add_action('pre_get_posts','filter_smile_transformations_by_region_filter');

//	Filter locations
function filter_locations_by_brand_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'location') {
		$meta_val = 'location_brand_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $brands;
		$choices = array();
		foreach($brands->brands as $brand) {
			$choices[$brand->ID] = $brand->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Brands</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_locations_by_brand_dropdown');

function filter_locations_by_brand_filter($query) {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'location' && $query->is_main_query()) {
		$meta_val = 'location_brand_relationship';
		if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all') {
			$query->set('meta_query', array( array(
				'key' => $meta_val,
				'value' => '"' . $_GET[$meta_val] . '"',
				'compare' => 'LIKE'
			) ) );
		}
    }
}
add_action('pre_get_posts','filter_locations_by_brand_filter');

//	Filter reviews
function filter_reviews_by_brand_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'review') {
		$meta_val = 'review_relationships';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $brands;
		$choices = array();
		foreach($brands->brands as $brand) {
			$choices[$brand->ID] = $brand->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Brands</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_reviews_by_brand_dropdown');

function filter_reviews_by_brand_filter($query) {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'review' && $query->is_main_query()) {
		$meta_val = 'review_relationships';
		if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all') {
			$query->set('meta_query', array( array(
				'key' => $meta_val,
				'value' => '"' . $_GET[$meta_val] . '"',
				'compare' => 'LIKE'
			) ) );
		}
    }
}
add_action('pre_get_posts','filter_reviews_by_brand_filter');

//	Filter Edu associations by Provider
function filter_edu_associations_by_provider_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'edu_association') {
		$meta_val = 'edu_association_provider_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $providers;
		$choices = array();
		foreach($providers->providers as $provider) {
			$choices[$provider->ID] = $provider->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Providers</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_edu_associations_by_provider_dropdown');

function filter_edu_associations_by_provider_filter($query) {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'edu_association' && $query->is_main_query()) {
		$meta_val = 'edu_association_provider_relationship';
		if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all') {
			$query->set('meta_query', array( array(
				'key' => $meta_val,
				'value' => '"' . $_GET[$meta_val] . '"',
				'compare' => 'LIKE'
			) ) );
		}
    }
}
add_action('pre_get_posts','filter_edu_associations_by_provider_filter');

// Filter Edu associations by Brand
function filter_edu_associations_by_brand_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'edu_association') {
		$meta_val = 'edu_association_brand_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $brands;
		$choices = array();
		foreach($brands->brands as $brand) {
			$choices[$brand->ID] = $brand->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Brands</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_edu_associations_by_brand_dropdown');

function filter_edu_associations_by_brand_filter($query) {
	global $wpdb;

	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'edu_association' && $query->is_main_query()) {
		$meta_val = 'edu_association_brand_relationship';

		if( isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all' ) {
			$provider_id_listing = $wpdb->get_var( "SELECT GROUP_CONCAT(p.ID SEPARATOR '|') as providerIDs FROM $wpdb->posts p WHERE ID in ( SELECT pm.post_id as pId FROM $wpdb->postmeta pm WHERE pm.meta_key = 'provider_brand_relationship' AND pm.meta_value LIKE '%$_GET[$meta_val]%' ) AND p.post_status = 'publish'" );

			$query->set( 'meta_query', array(
				array(
					'key' => 'edu_association_provider_relationship',
					'value' => $provider_id_listing,
					'compare' => 'REGEXP'
				)
			) );
		}
    }
}
add_action('pre_get_posts','filter_edu_associations_by_brand_filter');

//	Filter Edu associations by Region
function filter_edu_associations_by_region_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'edu_association') {
		$meta_val = 'edu_association_region_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $regions;
		$choices = array();
		foreach($regions->regions as $region) {
			$choices[$region->ID] = $region->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Regions</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_edu_associations_by_region_dropdown');

function filter_edu_associations_by_region_filter($query) {
	global $wpdb;

	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'edu_association' && $query->is_main_query()) {
		$meta_val = 'edu_association_region_relationship';
		if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all' && $_GET['edu_association_provider_relationship'] == 'all') {
			$locations_by_region_id = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'region_location_relationship' AND post_id = $_GET[$meta_val]" );
			$locations_by_region_id = unserialize( $locations_by_region_id );
			$provider_id_listing_str = '';
			$providers_for_location = [];

			foreach ($locations_by_region_id as $outer_key => $location_id) {
				$providers_for_location = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'provider_location_relationship' AND meta_value LIKE '%$location_id%'" );

				foreach ($providers_for_location as $inner_key => $provider) {
					$provider_id_listing_str .= $provider->post_id;

					if ( (COUNT($providers_for_location) - 1) > $inner_key) {
						$provider_id_listing_str .= '|';
					}
				}

				if ( (COUNT($locations_by_region_id) - 1) > $outer_key) {
					$provider_id_listing_str .= '|';
				}
			}

			$query->set( 'meta_query', array(
				array(
					'key' => 'edu_association_provider_relationship',
					'value' => $provider_id_listing_str,
					'compare' => 'REGEXP'
				)
			) );
		}
    }
}
add_action('pre_get_posts','filter_edu_associations_by_region_filter');

//	Filter Professional Associations by Provider
function filter_pro_affiliations_by_provider_dropdown() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'pro_affiliation') {
		$meta_val = 'pro_affiliation_provider_relationship';
		$selected = filter_input(INPUT_GET, $meta_val, FILTER_SANITIZE_STRING );

		global $providers;
		$choices = array();
		foreach($providers->providers as $provider) {
			$choices[$provider->ID] = $provider->post_title;
		}

		echo'<select name="'. $meta_val .'">';
			echo '<option value="all" '. (( $selected == 'all' ) ? 'selected="selected"' : "") . '>All Providers</option>';
			foreach( $choices as $key => $value ) {
				echo '<option value="' . $key . '" '. (( $selected == $key ) ? 'selected="selected"' : "") . '>' . $value . '</option>';
			}
		echo'</select>';
	}
}
add_action('restrict_manage_posts', 'filter_pro_affiliations_by_provider_dropdown');

function filter_pro_affiliations_by_provider_filter($query) {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'pro_affiliation' && $query->is_main_query()) {
		$meta_val = 'pro_affiliation_provider_relationship';
		if (isset($_GET[$meta_val]) && $_GET[$meta_val] != 'all') {
			$query->set('meta_query', array( array(
				'key' => $meta_val,
				'value' => '"' . $_GET[$meta_val] . '"',
				'compare' => 'LIKE'
			) ) );
		}
    }
}
add_action('pre_get_posts','filter_pro_affiliations_by_provider_filter');
