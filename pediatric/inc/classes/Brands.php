<?
class Brands
{
	public
		$is_rebuilding = false,
		$brands = null,

		//color-primary, color-secondary, color-tertiary, color-gray-1
		$brand_color_options = [
			'#418ce1, #9FC5F0, #66A2E7, #46464a' => 'Default Blue',
			'#33A3C2, #A0D7E6, #6FBDD3, #6E5F64' => 'Prairie Grove',
			'#5FBC7C, #9CD9AF, #C9EBD3, #5E6367' => 'Dietmeier',
			'#003E79, #A1AB74, #A1AB74, #5E6367' => 'Southmoor (Pediatric Default)',
			'#A1AB74, #F7921E, #9E5F2C, #5E6367' => 'Smiles in Motion',			
		],

		$___ ## DUMMY ##
	;

	public function __construct()  {
		$this->registerPreviewButtonHook();
		$this->registerPostTypes();
		$this->registerACF();
		$this->registerActions();
		$this->registerSaveHook();
	}

	private function registerPreviewButtonHook() {
		add_action('admin_head-post-new.php', [$this, 'hidePreviewButton']);
		add_action('admin_head-post.php', [$this, 'hidePreviewButton']);
	}

	public function hidePreviewButton() {
		global $post_type;

		foreach(['brand'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('brand', array(
				'labels' => array(
					'name' => _x('Brands', 'brand'),
					'singular_name' => _x('Brand', 'brand'),
					'add_new' => _x('Add New Brand', 'brand'),
					'add_new_item' => _x('Add New Brand', 'brand'),
					'edit_item' => _x('Edit Brand', 'brand'),
					'new_item' => _x('Add a Brand', 'brand'),
					'view_item' => _x('View Brand', 'brand'),
					'search_items' => _x('Search Brands', 'brand'),
					'not_found' => _x('No brands found', 'brand'),
					'not_found_in_trash' => _x('No brands found in trash', 'brand'),
					'parent_item_colon' => _x('Parent Brand:', 'brand'),
					'menu_name' => _x('Brands', 'brand'),
				),
				'hierarchical' => false,
				'supports' => ['title', 'revisions'],
				'taxonomies' => [],
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 2,
				'menu_icon' => 'dashicons-heart',
				'show_in_nav_menus' => false,
				'publicly_queryable' => false,
				'exclude_from_search' => false,
				'has_archive' => false,
				'query_var' => true,
				'can_export' => true,
				'capability_type' => 'post',
			));
		});
	}

	private function getPageVars() {
		return array_filter(array_keys(get_object_vars($this)), function($v) {
			return stripos($v, 'page_') === 0;
		});
	}

	private function registerACF() {
		////////////////////////////// POST TYPE FIELDS //////////////////////////////
		$menu_order = 0;

		if(function_exists('acf_add_local_field_group')) acf_add_local_field_group([
			'key' => 'brand_settings',
			'title' => 'Brand Settings',
			'fields' => [
				[
					'key' => 'urls_tab_brand',
					'name' => 'urls_tab_brand',
					'label' => 'Brand URL’s',
					'type' => 'tab',
				],
				[
					'key'   => 'brand_local_url',
					'name'  => 'brand_local_url',
					'label' => 'Local URL',
					'type'  => 'url',
					'required' => false,
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key'   => 'brand_dev_url',
					'name'  => 'brand_dev_url',
					'label' => 'Development URL',
					'type'  => 'url',
					'required' => false,
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key'   => 'brand_admin_url',
					'name'  => 'brand_admin_url',
					'label' => 'Admin URL',
					'type'  => 'url',
					'required' => false,
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key'   => 'brand_production_url',
					'name'  => 'brand_production_url',
					'label' => 'Production URL',
					'type'  => 'url',
					'required' => false,
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key' => 'seo_tab_brand',
					'name' => 'seo_tab_brand',
					'label' => 'SEO',
					'type' => 'tab',
				],
				[
					'key' => 'brand_seo_title',
					'name' => 'brand_seo_title',
					'label' => 'SEO Title',
					'required' => false,
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_seo_description',
					'name' => 'brand_seo_description',
					'label' => 'SEO Description',
					'required' => false,
					'type' => 'textarea',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'corporate_information_tab_brand',
					'name' => 'corporate_information_tab_brand',
					'label' => 'Corporate Information',
					'type' => 'tab',
				],
				[
					'key' => 'brand_corporate_name',
					'name' => 'brand_corporate_name',
					'label' => 'Name',
					'required' => false,
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_corporate_legal_name',
					'name' => 'brand_corporate_legal_name',
					'label' => 'Legal Name',
					'required' => false,
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_corporate_address',
					'name' => 'brand_corporate_address',
					'label' => 'Address',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_corporate_address_line_2',
					'name' => 'brand_corporate_address_line_2',
					'label' => 'Address 2',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_corporate_city',
					'name' => 'brand_corporate_city',
					'label' => 'City',
					'type' => 'text',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'brand_corporate_state',
					'name' => 'brand_corporate_state',
					'label' => 'State',
					'type' => 'text',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'brand_corporate_zip',
					'name' => 'brand_corporate_zip',
					'label' => 'ZIP',
					'type' => 'text',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'brand_hr_email',
					'name' => 'brand_hr_email',
					'label' => 'HR Email',
					'type' => 'text',
					'required' => false,
				],
				[
					'key' => 'brand_corporate_phone',
					'name' => 'brand_corporate_phone',
					'label' => 'Phone',
					'type' => 'text',
					'instructions' => '<strong>IMPORTANT:</strong> Format as 10 numbers, no spaces. E.G. 8005551212',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'brand_corporate_after_hours_phone',
					'name' => 'brand_corporate_after_hours_phone',
					'label' => 'After Hours Phone',
					'type' => 'text',
					'instructions' => '<strong>IMPORTANT:</strong> Format as 10 numbers, no spaces. E.G. 8005551212',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'brand_corporate_toll_free_phone',
					'name' => 'brand_corporate_toll_free_phone',
					'label' => 'Toll Free Phone',
					'type' => 'text',
					'instructions' => '<strong>IMPORTANT:</strong> Format as 10 numbers, no spaces. E.G. 8005551212',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'site_forms_tab_brand',
					'name' => 'site_forms_tab_brand',
					'label' => 'Site Forms',
					'type' => 'tab',
				],
				[
					'key' => 'brand_consultation_to_emails',
					'name' => 'brand_consultation_to_emails',
					'label' => 'Consultation TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_consultation_cc_emails',
					'name' => 'brand_consultation_cc_emails',
					'label' => 'Consultation CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_consultation_bcc_emails',
					'name' => 'brand_consultation_bcc_emails',
					'label' => 'Consultation BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_contact_to_emails',
					'name' => 'brand_contact_to_emails',
					'label' => 'Contact TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_contact_cc_emails',
					'name' => 'brand_contact_cc_emails',
					'label' => 'Contact CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_contact_bcc_emails',
					'name' => 'brand_contact_bcc_emails',
					'label' => 'Contact BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_appointment_to_emails',
					'name' => 'brand_appointment_to_emails',
					'label' => 'Appointment TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_appointment_cc_emails',
					'name' => 'brand_appointment_cc_emails',
					'label' => 'Appointment CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_appointment_bcc_emails',
					'name' => 'brand_appointment_bcc_emails',
					'label' => 'Appointment BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_refer_to_emails',
					'name' => 'brand_refer_to_emails',
					'label' => 'Refer TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_refer_cc_emails',
					'name' => 'brand_refer_cc_emails',
					'label' => 'Refer CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_refer_bcc_emails',
					'name' => 'brand_refer_bcc_emails',
					'label' => 'Refer BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_community_to_emails',
					'name' => 'brand_community_to_emails',
					'label' => 'Community TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_community_cc_emails',
					'name' => 'brand_community_cc_emails',
					'label' => 'Community CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_community_bcc_emails',
					'name' => 'brand_community_bcc_emails',
					'label' => 'Community BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_orthodontic_referral_to_emails',
					'name' => 'brand_orthodontic_referral_to_emails',
					'label' => 'Orthodontic Referral TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_orthodontic_referral_cc_emails',
					'name' => 'brand_orthodontic_referral_cc_emails',
					'label' => 'Orthodontic Referral CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_orthodontic_referral_bcc_emails',
					'name' => 'brand_orthodontic_referral_bcc_emails',
					'label' => 'Orthodontic Referral BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_review_to_emails',
					'name' => 'brand_review_to_emails',
					'label' => 'Review TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_review_cc_emails',
					'name' => 'brand_review_cc_emails',
					'label' => 'Review CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_review_bcc_emails',
					'name' => 'brand_review_bcc_emails',
					'label' => 'Review BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_custom_mouthguard_to_emails',
					'name' => 'brand_custom_mouthguard_to_emails',
					'label' => 'Free Custom Mouthguard TO: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_custom_mouthguard_cc_emails',
					'name' => 'brand_custom_mouthguard_cc_emails',
					'label' => 'Free Custom Mouthguard CC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'brand_custom_mouthguard_bcc_emails',
					'name' => 'brand_custom_mouthguard_bcc_emails',
					'label' => 'Free Custom Mouthguard BCC: Emails',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add email',
					'sub_fields' => [
						[
							'key' => 'email',
							'name' => 'email',
							'label' => 'Email',
							'type' => 'text',
						],
					],
				],
				[
					'key' => 'header_tab_brand',
					'name' => 'header_tab_brand',
					'label' => 'Header',
					'type' => 'tab',
				],
				[
					'key' => 'brand_logo_desktop',
					'name' => 'brand_logo_desktop',
					'label' => 'Desktop Logo',
					'type' => 'image',
					'mime_types' => 'svg,png',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_logo_desktop_inverse',
					'name' => 'brand_logo_desktop_inverse',
					'label' => 'Desktop Logo (Inverse)',
					'type' => 'image',
					'mime_types' => 'svg,png',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_favicon',
					'name' => 'brand_favicon',
					'label' => 'Favicon',
					'type' => 'image',
					'mime_types' => 'svg,png',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'footer_tab_brand',
					'name' => 'footer_tab_brand',
					'label' => 'Footer',
					'type' => 'tab',
				],
				[
					'key' => 'brand_footer_trademark_text',
					'name' => 'brand_footer_trademark_text',
					'label' => 'Trademark Text',
					'type' => 'text',
					'required' => false,
				],
				[
					'key' => 'brand_footer_images',
					'name' => 'brand_footer_images',
					'label' => 'Footer Images',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => 1,
					'max' => 2,
					'layout' => 'row',
					'button_label' => '',
					'sub_fields' => [
						[
							'key' => 'fimage',
							'name' => 'fimage',
							'label' => 'Footer Image',
							'type' => 'image',
							'mime_types' => 'svg,png',
							'library' => 'all',
							'required' => false,
							'return_format' => 'id',
							'preview_size' => 'thumbnail',
						],
					],
				],
				[
					'key' => 'social_media_tab_brand',
					'name' => 'social_media_tab_brand',
					'label' => 'Social Media',
					'type' => 'tab',
				],
				[
					'key' => 'brand_facebook_link',
					'name' => 'brand_facebook_link',
					'label' => 'Facebook',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_instagram_link',
					'name' => 'brand_instagram_link',
					'label' => 'Instagram',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'integrations_tab_brand',
					'name' => 'integrations_tab_brand',
					'label' => 'Integrations',
					'type' => 'tab',
				],
				[
					'key' => 'brand_gtm_container_id',
					'name' => 'brand_gtm_container_id',
					'label' => 'Google Tag Manager (GTM) Container ID',
					'instructions' => 'MDG Advertising tracking',
					'type' => 'text',
					'required' => false,
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_ga4_measurement_id',
					'name' => 'brand_ga4_measurement_id',
					'label' => 'GA4 Measurement ID',
					'instructions' => 'used for Whitelabeled Media tracking',
					'type' => 'text',
					'required' => false,
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_recaptcha_site_key',
					'name' => 'brand_recaptcha_site_key',
					'label' => 'reCAPTCHA Site Key',
					'type' => 'text',
					'required' => false,
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_recaptcha_secret_key',
					'name' => 'brand_recaptcha_secret_key',
					'label' => 'reCAPTCHA Secret Key',
					'type' => 'text',
					'required' => false,
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_google_maps_api_key',
					'name' => 'brand_google_maps_api_key',
					'label' => 'Google Maps API Key',
					'type' => 'text',
					'required' => false,
				],
				[
					'key' => 'patient_forms_tab_brand',
					'name' => 'patient_forms_tab_brand',
					'label' => 'Patient Forms Page',
					'type' => 'tab',
				],
				[
					'key' => 'brand_patient_forms_hero_desktop_image',
					'name' => 'brand_patient_forms_hero_desktop_image',
					'label' => 'Desktop Hero Image',
					'type' => 'image',
					'mime_types' => 'png,jpeg,jpg',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key' => 'brand_patient_forms_hero_mobile_image',
					'name' => 'brand_patient_forms_hero_mobile_image',
					'label' => 'Mobile Hero Image',
					'type' => 'image',
					'mime_types' => 'png,jpeg,jpg',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key' => 'brand_patient_forms_hero_heading',
					'name' => 'brand_patient_forms_hero_heading',
					'label' => 'Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key' => 'brand_patient_forms_hero_position',
					'name' => 'brand_patient_forms_hero_position',
					'label' => 'Hero Text Position',
					'type' => 'true_false',
					'default_value' => 0,
					'ui' => 1,
					'ui_on_text' => 'Right',
					'ui_off_text' => 'Left',
					'wrapper' => [
						'width' => 25,
					],
				],
				[
					'key' => 'brand_patient_forms_hero_content',
					'name' => 'brand_patient_forms_hero_content',
					'label' => 'Content',
					'type' => 'wysiwyg',
					'required' => false,
					'tabs' => 'all',
					'toolbar' => 'simple',
					'media_upload' => 0,
					'delay' => 0,
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'brand_patient_forms_section_two_heading',
					'name' => 'brand_patient_forms_section_two_heading',
					'label' => 'Section Two Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_patient_forms_section_two_copy',
					'name' => 'brand_patient_forms_section_two_copy',
					'label' => 'Section Two Copy',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'brand_patient_forms_side_image',
					'name' => 'brand_patient_forms_side_image',
					'label' => 'Side Image',
					'type' => 'image',
					'mime_types' => 'png,jpeg,jpg',
					'library' => 'all',
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
				],
				[
					'key' => 'info_packets_tab_brand',
					'name' => 'info_packets_tab_brand',
					'label' => 'Info Packets',
					'type' => 'tab',
				],
				[
					'key' => 'brand_braces_packet',
					'name' => 'brand_braces_packet',
					'label' => 'Braces packet',
					'type' => 'file',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				],
				[
					'key' => 'brand_invisalign_aligners_packet',
					'name' => 'brand_invisalign_aligners_packet',
					'label' => 'Invisalign® aligners packet',
					'type' => 'file',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				],
				[
					'key' => 'brand_expanders_packet',
					'name' => 'brand_expanders_packet',
					'label' => 'Expanders, separators, and headgear packet',
					'type' => 'file',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				],
				[
					'key' => 'brand_herbst_appliance_packet',
					'name' => 'brand_herbst_appliance_packet',
					'label' => 'Herbst® appliance packet',
					'type' => 'file',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				],
				[
					'key' => 'brand_while_eating_packet',
					'name' => 'brand_while_eating_packet',
					'label' => 'While eating packet',
					'type' => 'file',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				],
				[
					'key' => 'brand_retainer_packet',
					'name' => 'brand_retainer_packet',
					'label' => 'Retainer packet',
					'type' => 'file',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				],
				[
					'key' => 'brand_info_packet',
					'name' => 'brand_info_packet',
					'label' => 'Info packet',
					'type' => 'file',
					'wrapper' => [
						'width' => 50,
					],
					'return_format' => 'array',
					'library' => 'all',
					'min_size' => '',
					'max_size' => '',
					'mime_types' => 'pdf',
				],
				[
					'key' => 'blog_page_tab_brand',
					'name' => 'blog_page_tab_brand',
					'label' => 'Blog Page',
					'type' => 'tab',
				],
				[
					'key' => 'brand_blog_sidebar_heading',
					'name' => 'brand_blog_sidebar_heading',
					'label' => 'Blog Sidebar Heading',
					'type' => 'text',
					'required' => false,
				],
				[
					'key' => 'brand_blog_sidebar_content',
					'name' => 'brand_blog_sidebar_content',
					'label' => 'Blog Sidebar Content',
					'type' => 'wysiwyg',
					'required' => false,
					'tabs' => 'all',
					'toolbar' => 'simple',
					'media_upload' => 0,
					'delay' => 0,
				],
				[
					'key' => 'locations_page_tab_brand',
					'name' => 'locations_page_tab_brand',
					'label' => 'Locations Page',
					'type' => 'tab',
				],
				[
					'key' => 'brand_locations_heading',
					'name' => 'brand_locations_heading',
					'label' => 'Locations Heading',
					'type' => 'text',
					'required' => false,
				],
				[
					'key' => 'homepage_tab_brand',
					'name' => 'homepage_tab_brand',
					'label' => 'Brand Homepage',
					'type' => 'tab',
				],
				[
					'key' => 'homepage_hero_image',
					'name' => 'homepage_hero_image',
					'label' => 'Hero Image',
					'instructions' => 'Square image only',
					'type' => 'image',
					'mime_types' => 'png,jpeg,jpg',
					'library' => 'all',
					'required' => false,
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'wrapper' => [
						'width' => 20,
					],
				],
				[
					'key' => 'homepage_hero_heading',
					'name' => 'homepage_hero_heading',
					'label' => 'Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 80,
					],
				],
				[
					'key' => 'homepage_hero_copy',
					'name' => 'homepage_hero_copy',
					'label' => 'Copy',
					'type' => 'wysiwyg'
				],
				[
					'key' => 'homepage_hero_link_text',
					'name' => 'homepage_hero_link_text',
					'label' => 'Anchor link text',
					'type' => 'text',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'three_icons_slides_0',
					'name' => 'three_icons_slides_0',
					'label' => 'Three icons: Icon 1 data',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'three_icons_slides_0_icon',
							'name' => 'three_icons_slides_0_icon',
							'label' => 'Icon',
							'type' => 'select',
							'wrapper' => [
								'width' => 50,
							],
							'choices' => [],
							'default_value' => [],
							'allow_null' => false,
							'multiple' => false,
							'ui' => true,
							'return_format' => 'value',
							'ajax' => false,
						],
						[
							'key' => 'three_icons_slides_0_heading',
							'name' => 'three_icons_slides_0_heading',
							'label' => 'Heading',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'three_icons_slides_0_copy',
							'name' => 'three_icons_slides_0_copy',
							'label' => 'Copy',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 100,
							],
						],
					],
				],
				[
					'key' => 'three_icons_slides_1',
					'name' => 'three_icons_slides_1',
					'label' => 'Three icons: Icon 2 data',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'three_icons_slides_1_icon',
							'name' => 'three_icons_slides_1_icon',
							'label' => 'Icon',
							'type' => 'select',
							'wrapper' => [
								'width' => 50,
							],
							'choices' => [],
							'default_value' => [],
							'allow_null' => false,
							'multiple' => false,
							'ui' => true,
							'return_format' => 'value',
							'ajax' => false,
						],
						[
							'key' => 'three_icons_slides_1_heading',
							'name' => 'three_icons_slides_1_heading',
							'label' => 'Heading',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'three_icons_slides_1_copy',
							'name' => 'three_icons_slides_1_copy',
							'label' => 'Copy',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 100,
							],
						],
					],
				],
				[
					'key' => 'three_icons_slides_2',
					'name' => 'three_icons_slides_2',
					'label' => 'Three icons: Icon 3 data',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'three_icons_slides_2_icon',
							'name' => 'three_icons_slides_2_icon',
							'label' => 'Icon',
							'type' => 'select',
							'wrapper' => [
								'width' => 50,
							],
							'choices' => [],
							'default_value' => [],
							'allow_null' => false,
							'multiple' => false,
							'ui' => true,
							'return_format' => 'value',
							'ajax' => false,
						],
						[
							'key' => 'three_icons_slides_2_heading',
							'name' => 'three_icons_slides_2_heading',
							'label' => 'Heading',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'three_icons_slides_2_copy',
							'name' => 'three_icons_slides_2_copy',
							'label' => 'Copy',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 100,
							],
						],
//						[
//							'key' => 'homepage_tab_section5',
//							'name' => 'homepage_tab_section5',
//							'label' => 'Section 5 tab',
//							'type' => 'tab',
//						],

					],
				],

				[
                    'key' => 'section5_group',
                    'name' => 'section5_group',
                    'label' => 'Section 5: Image + Content',
                    'type' => 'group',
                    'layout' => 'block',
                    'sub_fields' => [
	                    [
		                    'key' => 'homepage_section5_image',
		                    'name' => 'homepage_section5_image',
		                    'label' => 'Image',
		                    'instructions' => 'Square image only',
		                    'type' => 'image',
		                    'mime_types' => 'png,jpeg,jpg',
		                    'library' => 'all',
		                    'required' => false,
		                    'return_format' => 'id',
		                    'preview_size' => 'thumbnail',
		                    'wrapper' => [
			                    'width' => 20,
		                    ],
	                    ],
						[
							'key' => 'homepage_section5_video',
							'name' => 'homepage_section5_video',
							'label' => 'Video Text',
							'instructions' => 'Enter url related to the video (if no video then image will render)',
							'type' => 'text',
							'required' => false,
							'wrapper' => [
								'width' => 80,
							],
						],
                        [
                            'key' => 'homepage_section5_heading',
                            'name' => 'homepage_section5_heading',
                            'label' => 'Heading',
                            'type' => 'text',
                            'wrapper' => [
                                'width' => 80,
                            ],
                        ],
                        [
                            'key' => 'homepage_section5_copy',
                            'name' => 'homepage_section5_copy',
                            'label' => 'Copy',
                            'type' => 'wysiwyg'
                        ],
                        [
                            'key' => 'homepage_section5_link_text',
                            'name' => 'homepage_section5_link_text',
                            'label' => 'Anchor link text',
                            'type' => 'text',
                            'wrapper' => [
                                'width' => 100,
                            ],
                        ],
				    ],
				],
				[
					'key' => 'age_group_slides_section',
					'name' => 'age_group_slides_section',
					'label' => 'Section: Age group carousel',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'age_group_section_heading',
							'name' => 'age_group_section_heading',
							'label' => 'Heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 80,
							],
						],
						[
							'key' => 'age_group_slides',
							'name' => 'age_group_slides',
							'label' => 'Age group carousel',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'collapsed' => '',
							'min' => '',
							'max' => '',
							'layout' => 'row',
							'button_label' => 'Add Slide',
							'sub_fields' => [
								[
									'key' => 'age_group_slides_image',
									'name' => 'age_group_slides_image',
									'label' => 'Image',
									'wrapper' => [
										'width' => 50,
									],
									'type' => 'image',
									'mime_types' => 'png,jpeg,jpg',
									'library' => 'all',
									'required' => false,
									'return_format' => 'id',
									'preview_size' => 'thumbnail',
								],
								[
									'key' => 'age_group_slides_heading',
									'name' => 'age_group_slides_heading',
									'label' => 'Heading',
									'type' => 'text',
									'allow_null' => true,
									'required' => false,
									'wrapper' => [
										'width' => 50,
									],
								],
								[
									'key' => 'age_group_slides_copy',
									'name' => 'age_group_slides_copy',
									'label' => 'Copy',
									'type' => 'wysiwyg',
									'allow_null' => true,
									'required' => false,
									'wrapper' => [
										'width' => 100,
									],
								],
								[
									'key' => 'age_group_slides_link_text',
									'name' => 'age_group_slides_link_text',
									'label' => 'Anchor link',
									'type' => 'text',
									'default_value' => '[BRAND_URL path="#" text="Learn more" class="cta red" title="Learn more"]',
									'wrapper' => [
										'width' => 100,
									],
								],
							]
						],
					],

				],
				[
					'key' => 'homepage_content_image_slides',
					'name' => 'homepage_content_image_slides',
					'label' => 'Question slides',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'button_label' => 'Add Slide',
					'sub_fields' => [
						[
							'key' => 'image',
							'name' => 'image',
							'label' => 'Image',
							'type' => 'image',
							'mime_types' => 'png,jpeg,jpg',
							'library' => 'all',
							'required' => false,
							'return_format' => 'id',
							'preview_size' => 'thumbnail',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'copy',
							'name' => 'copy',
							'label' => 'Copy',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 100,
							],
						],
						[
							'key' => 'cta',
							'name' => 'cta',
							'label' => 'CTA Short Code Field',
							'type' => 'text',
							'instructions' => 'use class="cta primary"',
							'wrapper' => [
								'width' => 100,
							],
							'return_format' => 'array',
						],
					]
				],
				[
					'key' => 'homepage_bottom_section',
					'name' => 'homepage_bottom_section',
					'label' => 'Bottom slider',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'row',
					'sub_fields' => [
						[
							'key' => 'heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
							'allow_null' => true,
							'required' => false,
							'wrapper' => [
								'width' => 100,
							],
						],
						[
							'key' => 'copy',
							'name' => 'copy',
							'label' => 'Copy',
							'type' => 'wysiwyg',
							'required' => false,
							'tabs' => 'all',
							'toolbar' => 'simple',
							'media_upload' => 0,
							'delay' => 0,
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'slides',
							'name' => 'slides',
							'label' => 'Slides',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'collapsed' => '',
							'min' => '',
							'max' => '',
							'layout' => 'row',
							'button_label' => 'Add Slide',
							'sub_fields' => [
								[
									'key' => 'image',
									'name' => 'image',
									'label' => 'Image',
									'type' => 'image',
									'mime_types' => 'png,jpeg,jpg',
									'library' => 'all',
									'required' => false,
									'return_format' => 'id',
									'preview_size' => 'thumbnail',
									'wrapper' => [
										'width' => 50,
									],
								],
							]
						],
				    ],
				],
				[
					'key' => 'options_tab_brand',
					'name' => 'options_tab_brand',
					'label' => 'Brand Options',
					'type' => 'tab',
				],
				[
					'key' => 'brand_colors',
					'name' => 'brand_colors',
					'label' => 'Brand Colors',
					'type' => 'radio',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => [
						'width' => '66.6666',
						'class' => '',
						'id' => '',
					],
					'choices' => $this->brand_color_options,
					'allow_null' => 0,
					'other_choice' => 0,
					'default_value' => '#418ce1, #9FC5F0, #66A2E7, #46464a',
					'layout' => 'horizontal',
					'return_format' => 'value',
					'save_other_choice' => 0,
				],
				[
					'key' => 'brand_fonts',
					'name' => 'brand_fonts',
					'label' => 'Brand Fonts',
					'type' => 'radio',
					'instructions' => 'Primary/Secondary',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => [
						'width' => '33.3333',
						'class' => '',
						'id' => '',
					],
					'choices' => [
						'usual, sans-serif+urbane, sans-serif' => 'Southmoor Usual/Urbane',
					],
					'allow_null' => 0,
					'other_choice' => 0,
					'default_value' => 'usual, sans-serif+urbane, sans-serif',
					'layout' => 'horizontal',
					'return_format' => 'value',
					'save_other_choice' => 0,
				],
			],
			'location' => [
				[[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'brand',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		add_filter('manage_brand_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'local_url' => __('Local URL', 'brand'),
				'dev_url' => __('Development URL', 'brand'),
				'admin_url' => __('Admin URL', 'brand'),
				'prod_url' => __('Production URL', 'brand'),
				'locations' => __('Locations', 'brand'),
				'date' => $columns['date'],
			];

			return $columns;
		}, 10, 1);

		add_action('manage_brand_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'local_url':
					echo get_post_meta($post_id, 'brand_local_url', true);
					break;
				case 'dev_url':
					echo get_post_meta($post_id, 'brand_dev_url', true);
					break;
				case 'admin_url':
					echo get_post_meta($post_id, 'brand_admin_url', true);
					break;
				case 'prod_url':
					echo get_post_meta($post_id, 'brand_production_url', true);
					break;
				case 'locations':
					$locations = get_locations_for_brand($post_id, 1);
					echo !empty($locations) ? implode(', ', $locations) : '—';
					break;
			}
		}, 10, 2);

		add_action('admin_head', function() {
			?>
			<style>
			.acf-field.auto-height { min-height:0!important;height:auto!important; }
			</style>
			<?
		});
	}

	private function registerSaveHook() {
		add_action('save_post', function($post_id, $post, $update) {
			if(get_post_type($post_id) == 'brand') $this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_brands');
	}

	private function loadData() {
		$this->brands = get_option('__website_cache_metadata_brands');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-brands'])
			|| empty($this->brands)
		) {
			add_action('template_redirect', function() {
				$this->rebuild();
			});
		}
	}

	public function rebuild() {
		if($this->is_rebuilding) return;
		$this->is_rebuilding = true;

		$q = new WP_Query([
			'post_type' => 'brand',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->brands = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'brand_')) $temp[str_replace('brand_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->brands[$p->ID] = (object)array_merge((array)$p, $temp);

			// Images
			$this->brands[$p->ID]->top_hero_bg_img = get_relative_url(wp_get_attachment_image_src($this->brands[$p->ID]->section_one_desktop_hero, 'full')[0]);
			$this->brands[$p->ID]->top_hero_mobile_img = [
				'src' => get_relative_url(wp_get_attachment_image_src($this->brands[$p->ID]->section_one_mobile_hero, 'medium_large')[0]),
				'alt' => get_post_meta($this->brands[$p->ID]->section_one_mobile_hero, '_wp_attachment_image_alt', true),
				'classes' => ['mobile']
			];
		}
		update_option('__website_cache_metadata_brands', $this->brands, false);
	}

	public function searchBrands($search_ids) {
		return array_filter($this->brands, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); });
	}
}
