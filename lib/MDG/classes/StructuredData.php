<?
namespace MDG;

class StructuredData {

	private $corporate;

	private function buildCorporate() {
		$brand = is_brand();

		$this->corporate = (object)[
			'@context' => 'https://schema.org/',
			'@type' => 'Dentist',
			'name' => $brand->corporate_name,
			'description' => '',
			'legalName' => $brand->corporate_legal_name,
			'logo' => wp_get_attachment_url($brand->logo_desktop),
			'numberOfEmployees' => $brand->post_title === 'Kristo Orthodontics' ? '100' : '65',
			'sameAs' => array_values((array)[$brand->facebook_link, $brand->instagram_link]),
			'currenciesAccepted' => 'USD',
			'paymentAccepted' => 'Cash, Credit Card, Dental Insurance, Financing',
			'amenityFeature' => 'Leading orthodontic specialist creating healthy, confident smiles in a fun atmosphere. Our braces and Invisalign® treatment plans are personalized to meet each patient’s unique lifestyle and goals.',
			'publicAccess' => 'Yes',
			'memberOf' => 'American Association of Orthodontists',
			'url' => brand_host(),
		];
	}

	private function getDentistBioStructuredData() {
		global $providers;
		$dentist = $providers->providers[get_the_ID()];
		$sd = (object)[
			'@context' => 'https://schema.org/',
			'@type' => 'Person',
			'name' => $dentist->first_name.' '.$dentist->last_name,
			'hasCredential' => $dentist->degree,
			'hasOccupation' => 'Orthodontist',
			'jobTitle' => 'Orthodontist',
			'memberOf' => 'American Association of Orthodontists',
		];

		return $sd;
	}

	private function getBlogPostStructuredData() {
		$post = get_post(get_the_ID());
		$sd = (object)[
			'@context' => 'https://schema.org/',
			'@type' => 'BlogPosting',
			'name' => esc_js(get_the_title()),
			'author' => (object)[
				'@context' => 'https://schema.org',
				'@type' => 'Person',
				'additionalName' => esc_js(get_the_author_meta('display_name', $post->post_author)),
			],
			'wordCount' => str_word_count(strip_tags(get_the_content())),
			'datePublished' => get_the_date('Y-m-d'),
		];

		return $sd;
	}

	private function build() {
		$sd = $this->corporate;

		if(is_singular('post')) {
			$sd = $this->getBlogPostStructuredData();
			$sd->publisher = $this->corporate;
		}

		if(is_singular('provider')) {
			$sd = $this->getDentistBioStructuredData();
			$sd->publisher = $this->corporate;
		}

		return $sd;
	}

	private function registerHooks() {
		add_action('wp_footer', function() {
			$this->buildCorporate();
			$json_ld = $this->build();
			echo '<script type="application/ld+json">'.PHP_EOL.json_encode($json_ld, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES).PHP_EOL.'</script>';
		});
	}

	function __construct() {
		$this->registerHooks();
	}
}
