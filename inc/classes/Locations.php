<?

class Locations
{
	public
		$is_rebuilding = false,
		$locations = null,

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

		foreach(['location'] as $pt) {
			if($post_type == $pt) {
				echo '<style type="text/css">body.wp-admin.post-type-'.$pt.' .preview.button { display:none; }</style>';
			}
		}
	}

	private function registerPostTypes() {
		add_action('init', function() {
			register_post_type('location', array(
				'labels' => array(
					'name' => _x('Locations', 'location'),
					'singular_name' => _x('Location', 'location'),
					'add_new' => _x('Add New Location', 'location'),
					'add_new_item' => _x('Add New Location', 'location'),
					'edit_item' => _x('Edit Location', 'location'),
					'new_item' => _x('Add a Location', 'location'),
					'view_item' => _x('View Location', 'location'),
					'search_items' => _x('Search Locations', 'location'),
					'not_found' => _x('No locations found', 'location'),
					'not_found_in_trash' => _x('No locations found in trash', 'location'),
					'parent_item_colon' => _x('Parent Location:', 'location'),
					'menu_name' => _x('Locations', 'location'),
				),
				'hierarchical' => false,
				'supports' => ['title', 'thumbnail', 'page-attributes', 'revisions'],
				'taxonomies' => [],
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				// 'menu_position' => 3,
				'menu_icon' => 'dashicons-admin-multisite',
				'rewrite' => ['slug' => 'orthodontist-office', 'with_front' => false],
				'show_in_nav_menus' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'has_archive' => true,
				'query_var' => true,
				'can_export' => true,
				'capability_type' => 'post',
			));
		});
	}

	private function registerACF() {
		////////////////////////////// POST TYPE FIELDS //////////////////////////////
		$menu_order = 0;

		if(function_exists('acf_add_local_field_group')) acf_add_local_field_group([
			'key' => 'location_settings',
			'title' => 'Location Settings',
			'fields' => [
				[
					'key' => 'relationship_tab_location',
					'name' => 'relationship_tab_location',
					'label' => 'Relationship’s',
					'type' => 'tab',
				],
				[
					'key' => 'location_brand_relationship',
					'name' => 'location_brand_relationship',
					'label' => 'Brand Relationship',
					'type' => 'relationship',
					'required' => 1,
					'conditional_logic' => 0,
					'post_type' => [
						0 => 'brand',
					],
					'filters' => [],
					'elements' => [],
					'return_format' => 'object',
				],
				[
					'key' => 'address_tab_location',
					'name' => 'address_tab_location',
					'label' => 'Address',
					'type' => 'tab',
				],
				[
					'key' => 'location_address',
					'name' => 'location_address',
					'label' => 'Address',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_address_line_2',
					'name' => 'location_address_line_2',
					'label' => 'Address 2',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_city',
					'name' => 'location_city',
					'label' => 'City',
					'type' => 'text',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'location_state',
					'name' => 'location_state',
					'label' => 'State',
					'type' => 'text',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'location_zip',
					'name' => 'location_zip',
					'label' => 'ZIP',
					'type' => 'text',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'location_full_address',
					'name' => 'location_full_address',
					'label' => 'Full Address',
					'type' => 'text',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'location_directions_link',
					'name' => 'location_directions_link',
					'label' => 'Directions URL',
					'type' => 'url',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'location_latitude',
					'name' => 'location_latitude',
					'label' => 'Latitude',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_longitude',
					'name' => 'location_longitude',
					'label' => 'Longitude',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_phone',
					'name' => 'location_phone',
					'label' => 'Phone',
					'type' => 'text',
					'instructions' => '<strong>IMPORTANT:</strong> Format numbers as [local number without spaces]. Ex: 8883663827',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'location_after_hours_phone',
					'name' => 'location_after_hours_phone',
					'label' => 'After Hours Phone',
					'type' => 'text',
					'instructions' => '<strong>IMPORTANT:</strong> Format numbers as [local number without spaces]. Ex: 8883663827',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'location_toll_free_phone',
					'name' => 'location_toll_free_phone',
					'label' => 'Toll Free Phone',
					'type' => 'text',
					'instructions' => '<strong>IMPORTANT:</strong> Format numbers as [local number without spaces]. Ex: 8883663827',
					'wrapper' => [
						'width' => 33.33,
					],
				],
				[
					'key' => 'location_hours',
					'name' => 'location_hours',
					'label' => 'Hours',
					'type' => 'wysiwyg',
					'instructions' => '',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'location_descriptive_heading',
					'name' => 'location_descriptive_heading',
					'label' => 'Heading for descriptive office location',
					'type' => 'text',
					'instructions' => '',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'location_descriptive_copy',
					'name' => 'location_descriptive_copy',
					'label' => 'Descriptive office location',
					'type' => 'wysiwyg',
					'instructions' => '',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'site_forms_tab_location',
					'name' => 'site_forms_tab_location',
					'label' => 'Site Forms',
					'type' => 'tab',
				],
				[
					'key' => 'location_consultation_to_emails',
					'name' => 'location_consultation_to_emails',
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
					'key' => 'location_consultation_cc_emails',
					'name' => 'location_consultation_cc_emails',
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
					'key' => 'location_consultation_bcc_emails',
					'name' => 'location_consultation_bcc_emails',
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
					'key' => 'location_contact_to_emails',
					'name' => 'location_contact_to_emails',
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
					'key' => 'location_contact_cc_emails',
					'name' => 'location_contact_cc_emails',
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
					'key' => 'location_contact_bcc_emails',
					'name' => 'location_contact_bcc_emails',
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
					'key' => 'location_appointment_to_emails',
					'name' => 'location_appointment_to_emails',
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
					'key' => 'location_appointment_cc_emails',
					'name' => 'location_appointment_cc_emails',
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
					'key' => 'location_appointment_bcc_emails',
					'name' => 'location_appointment_bcc_emails',
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
					'key' => 'location_refer_to_emails',
					'name' => 'location_refer_to_emails',
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
					'key' => 'location_refer_cc_emails',
					'name' => 'location_refer_cc_emails',
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
					'key' => 'location_refer_bcc_emails',
					'name' => 'location_refer_bcc_emails',
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
					'key' => 'location_community_to_emails',
					'name' => 'location_community_to_emails',
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
					'key' => 'location_community_cc_emails',
					'name' => 'location_community_cc_emails',
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
					'key' => 'location_community_bcc_emails',
					'name' => 'location_community_bcc_emails',
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
					'key' => 'location_orthodontic_referral_to_emails',
					'name' => 'location_orthodontic_referral_to_emails',
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
					'key' => 'location_orthodontic_referral_cc_emails',
					'name' => 'location_orthodontic_referral_cc_emails',
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
					'key' => 'location_orthodontic_referral_bcc_emails',
					'name' => 'location_orthodontic_referral_bcc_emails',
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
					'key' => 'location_review_to_emails',
					'name' => 'location_review_to_emails',
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
					'key' => 'location_review_cc_emails',
					'name' => 'location_review_cc_emails',
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
					'key' => 'location_review_bcc_emails',
					'name' => 'location_review_bcc_emails',
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
					'key' => 'location_custom_mouthguard_to_emails',
					'name' => 'location_custom_mouthguard_to_emails',
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
					'key' => 'location_custom_mouthguard_cc_emails',
					'name' => 'location_custom_mouthguard_cc_emails',
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
					'key' => 'location_custom_mouthguard_bcc_emails',
					'name' => 'location_custom_mouthguard_bcc_emails',
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
					'key' => 'location_custom_invisalign_vc_to_emails',
					'name' => 'location_custom_invisalign_vc_to_emails',
					'label' => 'Invisalign Virtual Care TO: Emails',
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
					'key' => 'location_custom_invisalign_vc_cc_emails',
					'name' => 'location_custom_invisalign_vc_cc_emails',
					'label' => 'Invisalign Virtual Care CC: Emails',
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
					'key' => 'location_custom_invisalign_vc_bcc_emails',
					'name' => 'location_custom_invisalign_vc_bcc_emails',
					'label' => 'Invisalign Virtual Care BCC: Emails',
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
					'key' => 'social_media_tab_location',
					'name' => 'social_media_tab_location',
					'label' => 'Social Media',
					'type' => 'tab',
				],
				[
					'key' => 'location_facebook_link',
					'name' => 'location_facebook_link',
					'label' => 'Facebook',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_instagram_link',
					'name' => 'location_instagram_link',
					'label' => 'Instagram',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'integrations_tab_location',
					'name' => 'integrations_tab_location',
					'label' => 'Integrations',
					'type' => 'tab',
				],
				[
					'key' => 'location_google_cid',
					'name' => 'location_google_cid',
					'label' => 'Google CID',
					'type' => 'text',
				],
				[
					'key' => 'location_gmb_review_link',
					'name' => 'location_gmb_review_link',
					'label' => 'GMB Review Link',
					'type' => 'text',
				],
				[
					'key' => 'patient_forms_tab_location',
					'name' => 'patient_forms_tab_location',
					'label' => 'Patient Forms Page',
					'type' => 'tab',
				],
				[
					'key' => 'location_new_patient_copy',
					'name' => 'location_new_patient_copy',
					'label' => 'New Patient Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 100,
					],
					'rows' => 3,
				],
				[
					'key' => 'location_new_patient_consultation_form',
					'name' => 'location_new_patient_consultation_form',
					'label' => 'New Patient Consultation Form',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_consultation_form_link_text',
					'name' => 'location_new_patient_consultation_form_link_text',
					'label' => 'New Patient Consultation Form Link Text',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_consultation_form_heading',
					'name' => 'location_new_patient_consultation_form_heading',
					'label' => 'New Patient Consultation Form Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_consultation_form_copy',
					'name' => 'location_new_patient_consultation_form_copy',
					'label' => 'New Patient Consultation Form Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 50,
					],
					'rows' => 2,
				],
				[
					'key' => 'location_form_a',
					'name' => 'location_form_a',
					'label' => 'New Patient Form A',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_form_a_link_text',
					'name' => 'location_form_a_link_text',
					'label' => 'New Patient Form A Link Text',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_form_b',
					'name' => 'location_form_b',
					'label' => 'New Patient Form B',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_form_b_link_text',
					'name' => 'location_form_b_link_text',
					'label' => 'New Patient Form B Link Text',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_a_b_form_heading',
					'name' => 'location_a_b_form_heading',
					'label' => 'A B Form Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_a_b_form_copy',
					'name' => 'location_a_b_form_copy',
					'label' => 'A B Form Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 50,
					],
					'rows' => 2,
				],
				[
					'key' => 'location_new_patient_form',
					'name' => 'location_new_patient_form',
					'label' => 'New Patient Form',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_form_link_text',
					'name' => 'location_new_patient_form_link_text',
					'label' => 'New Patient Form Link Text',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_form_heading',
					'name' => 'location_new_patient_form_heading',
					'label' => 'New Patient Form Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_form_copy',
					'name' => 'location_new_patient_form_copy',
					'label' => 'New Patient Form Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 50,
					],
					'rows' => 2,
				],
				[
					'key' => 'location_new_patient_aao_questionnaire',
					'name' => 'location_new_patient_aao_questionnaire',
					'label' => 'New Patient AAO Questionnaire (COVID-19)',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_aao_link_text',
					'name' => 'location_new_patient_aao_link_text',
					'label' => 'New Patient AAO Link Text',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_aao_heading',
					'name' => 'location_new_patient_aao_heading',
					'label' => 'New Patient AAO Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_new_patient_aao_copy',
					'name' => 'location_new_patient_aao_copy',
					'label' => 'New Patient AAO Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 50,
					],
					'rows' => 2,
				],
				[
					'key' => 'location_existing_patient_copy',
					'name' => 'location_existing_patient_copy',
					'label' => 'Existing Patient Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 100,
					],
					'rows' => 3,
				],
				[
					'key' => 'location_existing_patient_aao_informed_consent',
					'name' => 'location_existing_patient_aao_informed_consent',
					'label' => 'Existing Patient AAO informed consent (COVID-19)',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_existing_patient_informed_consent_link_text',
					'name' => 'location_existing_patient_informed_consent_link_text',
					'label' => 'Existing Patient Informed Consent Link Text',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_existing_patient_informed_consent_heading',
					'name' => 'location_existing_patient_informed_consent_heading',
					'label' => 'Existing Patient Informed Consent Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_existing_patient_informed_consent_copy',
					'name' => 'location_existing_patient_informed_consent_copy',
					'label' => 'Existing Patient Informed Consent Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 50,
					],
					'rows' => 2,
				],
				[
					'key' => 'location_existing_patient_aao_questionnaire',
					'name' => 'location_existing_patient_aao_questionnaire',
					'label' => 'Existing Patient AAO Questionnaire (COVID-19)',
					'type' => 'url',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_existing_patient_aao_questionnaire_link_text',
					'name' => 'location_existing_patient_aao_questionnaire_link_text',
					'label' => 'Existing Patient Informed Consent Link Text',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_existing_patient_aao_questionnaire_heading',
					'name' => 'location_existing_patient_aao_questionnaire_heading',
					'label' => 'Existing Patient Informed Consent Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 50,
					],
				],
				[
					'key' => 'location_existing_patient_aao_questionnaire_copy',
					'name' => 'location_existing_patient_aao_questionnaire_copy',
					'label' => 'Existing Patient Informed Consent Copy',
					'type' => 'textarea',
					'wrapper' => [
						'width' => 50,
					],
					'rows' => 2,
				],
				[
					'key' => 'location_patient_forms_section_two_heading',
					'name' => 'location_patient_forms_section_two_heading',
					'label' => 'Section Two Heading',
					'type' => 'text',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'location_patient_forms_section_two_copy',
					'name' => 'location_patient_forms_section_two_copy',
					'label' => 'Section Two Copy',
					'type' => 'text',
					'wrapper' => [
						'width' => 100,
					],
				],
				[
					'key' => 'info_packets_tab_location',
					'label' => 'Info Packets',
					'name' => 'info_packets_tab_location',
					'type' => 'tab',
				],
				[
					'key' => 'location_braces_packet',
					'name' => 'location_braces_packet',
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
					'key' => 'location_invisalign_aligners_packet',
					'name' => 'location_invisalign_aligners_packet',
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
					'key' => 'location_expanders_packet',
					'name' => 'location_expanders_packet',
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
					'key' => 'location_herbst_appliance_packet',
					'name' => 'location_herbst_appliance_packet',
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
					'key' => 'location_while_eating_packet',
					'name' => 'location_while_eating_packet',
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
					'key' => 'location_retainer_packet',
					'name' => 'location_retainer_packet',
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
					'key' => 'location_info_packet',
					'name' => 'location_info_packet',
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
					'key' => 'homepage_tab_location',
					'name' => 'homepage_tab_location',
					'label' => 'Location Homepage',
					'type' => 'tab',
				],
				[
					'key' => 'location_section_one',
					'name' => 'location_section_one',
					'label' => 'Section One',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'location_section_one_desktop_hero',
							'name' => 'desktop_hero',
							'label' => 'Desktop Hero',
							'type' => 'image',
							'mime_types' => 'png,jpeg,jpg',
							'library' => 'all',
							'required' => false,
							'return_format' => 'id',
							'preview_size' => 'thumbnail',
							'wrapper' => [
								'width' => 100,
							],
						],
						[
							'key' => 'location_section_one_heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'location_section_one_subheading',
							'name' => 'subheading',
							'label' => 'Sub-heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'location_section_one_cta',
							'name' => 'cta',
							'label' => 'Shortcode',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'location_section_one_content',
							'name' => 'content',
							'label' => 'Content',
							'type' => 'wysiwyg',
							'required' => false,
							'tabs' => 'all',
							'toolbar' => 'simple',
							'media_upload' => 0,
							'delay' => 0,
						],
					],
				],
				[
					'key' => 'location_section_two',
					'name' => 'location_section_two',
					'label' => 'Section Two Icons Carousel',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'location_section_two_icon_1',
							'name' => 'icon_1',
							'label' => 'Icon 1',
							'type' => 'group',
							'layout' => 'block',
							'sub_fields' => [
								[
									'key' => 'location_section_two_icon_1_icon',
									'name' => 'icon',
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
									'key' => 'location_section_two_icon_2_heading',
									'name' => 'text',
									'label' => 'Heading',
									'type' => 'text',
									'wrapper' => [
										'width' => 50,
									],
								],
								[
									'key' => 'location_section_two_icon_2_copy',
									'name' => 'text',
									'label' => 'Copy',
									'type' => 'text',
									'wrapper' => [
										'width' => 50,
									],
								],
							],
						],
						[
							'key' => 'location_section_two_icon_2',
							'name' => 'icon_2',
							'label' => 'Icon 2',
							'type' => 'group',
							'layout' => 'block',
							'sub_fields' => [
								[
									'key' => 'location_section_two_icon_2_icon',
									'name' => 'icon',
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
									'key' => 'location_section_two_icon_2_heading',
									'name' => 'text',
									'label' => 'Heading',
									'type' => 'text',
									'wrapper' => [
										'width' => 50,
									],
								],
								[
									'key' => 'location_section_two_icon_2_copy',
									'name' => 'text',
									'label' => 'Copy',
									'type' => 'text',
									'wrapper' => [
										'width' => 50,
									],
								],
							],
						],
						[
							'key' => 'location_section_two_icon_3',
							'name' => 'icon_3',
							'label' => 'Icon 3',
							'type' => 'group',
							'layout' => 'block',
							'sub_fields' => [
								[
									'key' => 'location_section_two_icon_3_icon',
									'name' => 'icon',
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
									'key' => 'location_section_two_icon_3_heading',
									'name' => 'text',
									'label' => 'Heading',
									'type' => 'text',
									'wrapper' => [
										'width' => 50,
									],
								],
								[
									'key' => 'location_section_two_icon_3_copy',
									'name' => 'text',
									'label' => 'Copy',
									'type' => 'text',
									'wrapper' => [
										'width' => 50,
									],
								],
							],
						],
					],
				],
				[
					'key' => 'location_section_three',
					'name' => 'location_section_three',
					'label' => 'Section Three',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'location_section_three_heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'location_section_three_cta',
							'name' => 'cta',
							'label' => 'Shortcode',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'location_section_three_content',
							'name' => 'content',
							'label' => 'Content',
							'type' => 'wysiwyg',
							'required' => false,
							'tabs' => 'all',
							'toolbar' => 'simple',
							'media_upload' => 0,
							'delay' => 0,
						],
						[
							'key' => 'location_section_three_providers_relationship',
							'name' => 'providers_relationship',
							'label' => 'Providers Relationship',
							'type' => 'relationship',
							'instructions' => 'Choose which Providers you want to show on the page.',
							'post_type' => [
								0 => 'provider',
							],
							'filters' => [],
							'elements' => [],
							'return_format' => 'object',
						],
					],
				],
				[
					'key' => 'location_section_four',
					'name' => 'location_section_four',
					'label' => 'Section Four',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'location_section_four_desktop_hero',
							'name' => 'desktop_hero',
							'label' => 'Desktop Hero',
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
							'key' => 'location_section_four_heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'location_section_four_cta',
							'name' => 'cta',
							'label' => 'Shortcode',
							'type' => 'text',
							'wrapper' => [
								'width' => 50,
							],
						],
						[
							'key' => 'location_section_four_content',
							'name' => 'content',
							'label' => 'Content',
							'type' => 'wysiwyg',
							'required' => false,
							'tabs' => 'all',
							'toolbar' => 'simple',
							'media_upload' => 0,
							'delay' => 0,
						],
					],
				],
				[
					'key' => 'location_age_group_slides_section',
					'name' => 'location_age_group_slides_section',
					'label' => 'Section 5: Age group carousel',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'location_age_group_section_heading',
							'name' => 'location_age_group_section_heading',
							'label' => 'Heading',
							'type' => 'text',
							'wrapper' => [
								'width' => 80,
							],
						],
						[
							'key' => 'location_age_group_slides',
							'name' => 'location_age_group_slides',
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
									'key' => 'location_age_group_slides_image',
									'name' => 'location_age_group_slides_image',
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
									'key' => 'location_age_group_slides_heading',
									'name' => 'location_age_group_slides_heading',
									'label' => 'Heading',
									'type' => 'text',
									'allow_null' => true,
									'required' => false,
									'wrapper' => [
										'width' => 50,
									],
								],
								[
									'key' => 'location_age_group_slides_copy',
									'name' => 'location_age_group_slides_copy',
									'label' => 'Copy',
									'type' => 'wysiwyg',
									'allow_null' => true,
									'required' => false,
									'wrapper' => [
										'width' => 100,
									],
								],
								[
									'key' => 'location_age_group_slides_link_text',
									'name' => 'location_age_group_slides_link_text',
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
					'key' => 'location_appointment_section',
					'name' => 'location_appointment_section',
					'label' => 'Appointment section',
					'type' => 'group',
					'layout' => 'block',
					'sub_fields' => [
						[
							'key' => 'location_appointment_section_image',
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
							'key' => 'location_appointment_section_heading',
							'name' => 'heading',
							'label' => 'Heading',
							'type' => 'text',
							'required' => false,
							'tabs' => 'all',
							'toolbar' => 'simple',
							'media_upload' => 0,
							'delay' => 0,
						],
						[
							'key' => 'location_appointment_section_cta',
							'name' => 'cta',
							'label' => 'Anchor link',
							'type' => 'text',
							'default_value' => '[BRAND_URL path="#" text="Schedule your appointment" class="cta white" title="Schedule your appointment"]',
							'wrapper' => [
								'width' => 100,
							],
						],
					],
				],
				[
					'key' => 'location_homepage_bottom_slider',
					'name' => 'location_homepage_bottom_slider',
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
			],
			'location' => [
				[[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'location',
				]],
			],
			'menu_order' => $menu_order++,
		]);
	}

	private function registerActions() {
		add_action('init', function() {
			$this->loadData();
		});

		add_filter('manage_location_posts_columns', function($columns) {
			$columns = [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'brands' => __('Brands', 'location'),
				'region' => __('Region', 'location'),
				'date' => $columns['date'],
			];

			return $columns;
		}, 10, 1);

		add_action('manage_location_posts_custom_column', function($column, $post_id) {
			switch ($column) {
				case 'brands':
					$brands = get_brands_for_location($post_id, 1);
					echo !empty($brands) ? implode(', ', $brands) : '—';
					break;
				case 'region':
					$regions = get_region_for_location($post_id, 1);
					echo !empty($regions) ? implode(', ', $regions) : '—';
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
		//=======================================[ACF/Save Post]=======================================//
		add_action('acf/save_post', function($post_id) {
			if (get_post_type($post_id) != 'location') return;
			$values = get_fields($post_id);
			$post_name = get_the_title($post_id).' '.$values['location_state'].' '.$values['location_zip'];

			// Updating Slug with ACF content for SEO purposes
			if (!empty($_POST['acf']['page_location_parent'])) {
				$post_data = [
					'ID' => $post_id,
					'post_parent' => $_POST['acf']['page_location_parent'],
					'post_name' => sanitize_title($post_name, '', 'save'),
				];
			} else {
				$post_data = [
					'ID' => $post_id,
					'post_name' => sanitize_title($post_name, '', 'save'),
				];
			}
			wp_update_post($post_data);
		}, 5, 1);

		add_action('save_post_location', function($post_id, $post, $update) {
			$this->flushCache();
			$this->rebuild();
		}, 10, 3);
	}

	public function flushCache() {
		delete_option('__website_cache_metadata_locations');
	}

	private function loadData() {
		$this->locations = get_option('__website_cache_metadata_locations');

		if(
			false
			|| isset($_GET['rebuild'])
			|| isset($_GET['rebuild-locations'])
			|| empty($this->locations)
		) {
			add_action('template_redirect', function() {
				$this->rebuild();
			});
		}
	}

	public function rebuild() {
		if($this->is_rebuilding) return;
		$this->is_rebuilding = true;

		global $providers;

		$q = new WP_Query([
			'post_type' => 'location',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		]);

		# Build storage
		$this->locations = [];
		foreach($q->posts as $p) {
			# Prepare meta attributes
			$temp = [];
			foreach(get_post_meta($p->ID) as $key => $value) {
				if(starts_with($key, 'location_')) $temp[str_replace('location_', '', $key)] = $value[0];
			}
			$temp = array_map('trim', $temp);
			$this->locations[$p->ID] = (object)array_merge((array)$p, $temp);

			$location = $this->locations[$p->ID];
			$location->url = get_permalink($p->ID);
			$location->relative_url = get_relative_permalink($p->ID);
			$location->image = [
				'src' => get_relative_url(get_the_post_thumbnail_url($p->ID, 'medium_large')),
				'alt' => get_post_meta(get_post_thumbnail_id($p->ID), '_wp_attachment_image_alt', true),
				'classes' => ['bg-img']
			];
			$doctors = array_map(function($provider) use ($location) {
				$relationships = property_exists($location, 'section_three_providers_relationship') && !empty($location->section_three_providers_relationship) ? unserialize($location->section_three_providers_relationship) : false;
				return !empty($relationships) && is_array($relationships) && in_array($provider->ID, $relationships) ? 'Dr. '.($provider->first_name).' '.($provider->last_name) : false;
			}, $providers->providers);
			if (!empty($doctors)) {				
				$location->doctors = array_values(array_filter($doctors));
			}

			$location->schedule_consultation_link = '<a href="'.(brand_url('/pediatric-dentist/'.($location->post_name).'/free-orthodontic-consultation/')).'" class="cta text" title="Schedule consultation">Schedule consultation</a>';
			$location->email_address = '<a href="'.(brand_url('/pediatric-dentist/'.($location->post_name).'/contact-us/')).'" class="cta text" title="Email us">Email us</a>';
			
			$phone = !empty($location->phone) ? ['number' => hyphenatePhoneNumber($location->phone), 'is_after_hours' => false] : false;
			$toll_free_phone = !empty($location->toll_free_phone) ? ['number' => hyphenatePhoneNumber($location->toll_free_phone), 'is_after_hours' => false] : false;
			// $after_hours_phone = !empty($location->after_hours_phone) ? ['number' => hyphenatePhoneNumber($location->after_hours_phone), 'is_after_hours' => true] : false;
			$location->phone_numbers = array_values(array_filter([$phone,$toll_free_phone]));
		}

		if (isset($_GET['locations_debug'])) {
			print_stmt($this->locations, 1);
		}

		update_option('__website_cache_metadata_locations', $this->locations, false);
		wp_reset_postdata();
	}

	public function searchLocations($search_ids) {		
		return (empty($search_ids) ? [] : array_filter($this->locations, function($v) use ($search_ids) { return in_array($v->ID, $search_ids); }) );
	}
}
