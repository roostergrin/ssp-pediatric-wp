<?
namespace MDG;

use DateTime;
use DateTimeZone;

class Forms {
	protected $brand, $location;
	protected $form_fields, $form_configurations, $current_form, $current_form_name, $current_object, $current_object_type;
	protected $form_errors, $api_errors;
	protected $inserted_id;

	function __construct() {
		$this->form_errors = [];
		$this->api_errors = [];
		$this->registerHooks();
		$this->registerFormSubmissionManager();
	}

	private function registerHooks() {
		add_action('phpmailer_init', function($phpmailer) {
			$phpmailer->isSMTP();
			$phpmailer->Host = 'smtp.office365.com';
			$phpmailer->SMTPAuth = true;
			$phpmailer->Port = 587;
			$phpmailer->SMTPSecure = 'tls';
			$phpmailer->Username = 'website-notifications@simkosupport.com';
			$phpmailer->Password = 'Temp1357$';
			$phpmailer->SetFrom('website-notifications@simkosupport.com', $this->brand->post_title);
			$phpmailer->addReplyTo('website-notifications@simkosupport.com', $this->brand->post_title);
		});

		if(!is_live()) {
			add_action('wp_mail_failed', function($wp_error) {
				echo "<pre>";
				print_r($wp_error);
				echo "</pre>";
			}, 10, 1);
		}

		add_filter('validation_method_name', function($method) {
			if($method == 'captcha') $method = 'required';
			return $method;
		}, 10, 1);

		add_action('wp_enqueue_scripts', function() {
			$this->enqueueScripts();
			$this->generate_form_js();
		}, 1e3, 0);

		add_action('wp', function() {
			$this->brand = is_brand();
			$this->location = is_location(true);
			$this->generateFormFields();
			$this->generateFormConfigurations();
			$this->verifyFormSubmission();
		});
	}
	private function generate_form_js() {
		$js_config = [];
		if( !empty( $this->form_configurations ) ) {
			foreach($this->form_configurations as $form_name => $config) {
				$js_config[$form_name] = $rules = $messages = [];
				if(!empty($config['rows'])) {

					// print_stmt($this->form_configurations['orthodontic-referral'], 1);
					foreach($config['rows'] as $row) {
						foreach($row as $ff) {
							$field = $this->getField($ff);
							if(isset($field)) {
								// TODO: move enqueue logic to generateForm() again
								if($field->type == 'date') {
									wp_enqueue_style('pickadate-css');
									wp_enqueue_script('pickadate-js');
								}
								elseif($field->type == 'tel') {
									wp_enqueue_script('masked-input');
								}
								if(!in_array($ff, $config['required'])) continue;
								foreach($field->errors as $method => $message) {
									$method = apply_filters('validation_method_name', $method);
									$rules[$ff][$method] = true;
									$messages[$ff][$method] = $message;
								}
							}
						}
					}
				}
				if(isset($rules) || isset($messages)) {
					$js_config[$form_name] = [
						'rules' => $rules,
						'messages' => $messages,
					];
				}
			}
		}

		wp_localize_script('internal-form-validator', '__forms', $js_config);
	}

	protected function enqueueScripts() {
		wp_enqueue_script('internal-forms');
		wp_enqueue_script('google-recaptcha');
	}

	protected function generateFormFields() {
		$this->form_fields = [
			'full_name' => [
				'type' => 'text',
				'label' => 'Your full name',
				'placeholder' => 'Your full name',
				'errors' => [
					'required' => 'Please enter your full name.',
				],
				'include_in_email_template' => true,
			],
			'office_preference' => [
				'type' => 'fancy-select',
				'label' => 'Your office preference',
				'placeholder' => 'Your office preference',
				'errors' => [
					'required' => 'Please select a office.',
				],
				'options' => $this->getOffices(),
				'default' => '-- Choose a office --',
				'include_in_email_template' => true,
			],
			'address' => [
				'type' => 'text',
				'label' => 'Address',
				'placeholder' => '123 Main Street',
				'errors' => [
					'required' => 'Please enter the address.',
				],
				'include_in_email_template' => true,
			],
			'city' => [
				'type' => 'text',
				'label' => 'City',
				'placeholder' => 'West Palm Beach',
				'errors' => [
					'required' => 'Please enter the city.',
				],
				'include_in_email_template' => true,
			],
			'state' => [
				'type' => 'select',
				'label' => 'State',
				'placeholder' => 'Choose a state',
				'errors' => [
					'required' => 'Please select a state.',
				],
				'options' => $this->getStateList(),
				'default' => '-- Choose a state --',
				'include_in_email_template' => true,
			],
			'country' => [
				'type' => 'text',
				'label' => 'Country',
				'errors' => [
					'required' => 'Please select a country.',
				],
				'include_in_email_template' => true,
			],
			'zip_code' => [
				'type' => 'text',
				'label' => 'ZIP Code',
				'placeholder' => '12345',
				'errors' => [
					'required' => 'Please enter your ZIP Code.',
				],
				'include_in_email_template' => true,
			],
			'company' => [
				'type' => 'text',
				'label' => 'Company',
				'errors' => [
					'required' => 'Please enter your company.',
				],
				'include_in_email_template' => true,
			],
			'title' => [
				'type' => 'text',
				'label' => 'Title:',
				'errors' => [
					'required' => 'Please enter your title.',
				],
				'include_in_email_template' => true,
			],
			'email_address' => [
				'type' => 'email',
				'label' => 'Your email address',
				'errors' => [
					'required' => 'Please enter your email address.',
					'emailTLD' => 'Please enter a valid email address.',
				],
				'include_in_email_template' => true,
			],
			'phone_number' => [
				'type' => 'tel',
				'label' => 'Your phone number',
				'errors' => [
					'required' => 'Please enter your phone number.',
					// 'phoneUS' => 'Please enter a valid phone number.',
				],
				'include_in_email_template' => true,
			],
			'comment' => [
				'type' => 'textarea',
				'label' => 'Any additional details',
				'errors' => [
					'required' => 'Please enter a any additional details.',
				],
				'include_in_email_template' => true,
			],
			'mouthguard_comment' => [
				'type' => 'textarea',
				'label' => 'Teams and organizations: Please provide as much detail as possible, including number of children or teens in your group, preferred mouthguard colors, etc.',
				'errors' => [
					'required' => 'Please enter as much detail as possible.',
				],
				'include_in_email_template' => true,
			],
			'mouthguard_colors' => [
				'type' => 'fancy-select-colors',
				'label' => 'Preferred mouthguard color(s)',
				'placeholder' => 'Preferred mouthguard color(s)',
				'errors' => [
					'required' => 'Please choose a preferred mouthguard color.',
				],
				'options' => $this->getMouthguardColors(),
				'default' => '-- Choose a mouthguard color --',
				'include_in_email_template' => true,
			],
			'current_patient' => [
				'type' => 'radio',
				'class' => 'row-group',
				'label' => 'Are you a current patient?',
				'options' => ['Yes', 'No'],
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'mouthguard_appointment' => [
				'type' => 'radio',
				'class' => 'row-group',
				'label' => 'Is this appointment for a custom mouthguard?',
				'options' => ['Yes', 'No'],
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'contact_method' => [
				'type' => 'checkbox-group',
				'class' => 'checkbox-group-horizontal',
				'label' => 'Contact me by',
				'options' => ['Phone', 'Text', 'Email'],
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'review_confirmation' => [
				'type' => 'checkbox',
				'class' => 'checkbox-confirmation',
				'label' => 'By checking this box, I confirm that the image I upload is either of myself or a legal dependent AND may be used on the website or marketing materials of '.(do_shortcode('[BRAND_TITLE]')).' or affiliated clinics.',
				'options' => [''],
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'appt_day' => [
				'type' => 'radio',
				'class' => 'row-group-boxes',
				'label' => 'Preferred appointment day',
				'options' => ['Any', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'appt_time' => [
				'type' => 'radio',
				'class' => 'row-group-boxes',
				'label' => 'Preferred appointment time',
				'options' => ['Any', 'Morning', 'Midday', 'Afternoon'],
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			// 'virtual_consultation_link' => [
			// 	'type' => 'html',
			// 	'label' => '<a href="#consult-from-home" class="cta text">Virtual consultations available</a>',
			// ],
			'info_title' => [
				'type' => 'html',
				'label' => '<div class="title">Your information</div>',
			],
			'friend_info_title' => [
				'type' => 'html',
				'label' => '<div class="title">Your friend’s information</div>',
			],
			'friend_full_name' => [
				'type' => 'text',
				'label' => 'Your friend\'s full name',
				'placeholder' => 'Your friend\'s full name',
				'errors' => [
					'required' => 'Please enter your full name.',
				],
				'include_in_email_template' => true,
			],
			'friend_relationship' => [
				'type' => 'text',
				'label' => 'Your relationship to your friend',
				'placeholder' => 'Your relationship to your friend',
				'errors' => [
					'required' => 'Please enter your full name.',
				],
				'include_in_email_template' => true,
			],
			'promo_code' => [
				'type' => 'text',
				'label' => 'Promo code',
				'placeholder' => 'promo_code',
				'errors' => [
					'required' => 'Please enter your full name.',
				],
				'include_in_email_template' => true,
			],
			'friend_office_preference' => [
				'type' => 'select',
				'label' => 'Your friend\'s office preference',
				'placeholder' => 'Your friend\'s office preference',
				'errors' => [
					'required' => 'Please select a office.',
				],
				'options' => $this->getOffices(),
				'default' => '-- Choose a office --',
				'include_in_email_template' => true,
			],
			'friend_email_address' => [
				'type' => 'email',
				'label' => 'Your friend\'s email address',
				'errors' => [
					'required' => 'Please enter your friend\'s email address.',
					'emailTLD' => 'Please enter a valid email address.',
				],
				'include_in_email_template' => true,
			],
			'friend_phone_number' => [
				'type' => 'tel',
				'label' => 'Your friend\'s phone number',
				'errors' => [
					'required' => 'Please enter your friend\'s phone number.',
					// 'phoneUS' => 'Please enter a valid phone number.',
				],
				'include_in_email_template' => true,
			],
			'contact_name' => [
				'type' => 'text',
				'label' => 'Contact name',
				'placeholder' => 'Contact name',
				'errors' => [
					'required' => 'Please enter contact name.',
				],
				'include_in_email_template' => true,
			],
			'contact_title' => [
				'type' => 'text',
				'label' => 'Contact title',
				'placeholder' => 'Contact title',
				'errors' => [
					'required' => 'Please enter contact title.',
				],
				'include_in_email_template' => true,
			],
			'contact_email_address' => [
				'type' => 'email',
				'label' => 'Contact email',
				'errors' => [
					'required' => 'Please enter contact email.',
					'emailTLD' => 'Please enter a valid email address.',
				],
				'include_in_email_template' => true,
			],
			'contact_phone_number' => [
				'type' => 'tel',
				'label' => 'Contact phone number',
				'errors' => [
					'required' => 'Please enter contact phone number.',
					// 'phoneUS' => 'Please enter a valid phone number.',
				],
				'include_in_email_template' => true,
			],
			'review' => [
				'type' => 'textarea',
				'label' => 'Please write your review',
				'errors' => [
					'required' => 'Please enter your review.',
				],
				'include_in_email_template' => true,
			],
			'title_of_event' => [
				'type' => 'text',
				'label' => 'Title of event or sponsorship',
				'placeholder' => 'Title of event or sponsorship',
				'errors' => [
					'required' => 'Please enter title of event or sponsorship.',
				],
				'include_in_email_template' => true,
			],
			'title_of_organization' => [
				'type' => 'text',
				'label' => 'Title of organization',
				'placeholder' => 'Title of organization',
				'errors' => [
					'required' => 'Please enter title of organization.',
				],
				'include_in_email_template' => true,
			],
			'sponsorship_type' => [
				'type' => 'text',
				'label' => 'Type of sponsorship or support',
				'placeholder' => 'Type of sponsorship or support',
				'errors' => [
					'required' => 'Please specify the type of sponsorship or support.',
				],
				'include_in_email_template' => true,
			],
			'event_location' => [
				'type' => 'text',
				'label' => 'Event location',
				'placeholder' => 'Event location',
				'errors' => [
					'required' => 'Please enter event location.',
				],
				'include_in_email_template' => true,
			],
			'website' => [
				'type' => 'text',
				'label' => 'Website',
				'placeholder' => 'Website',
				'errors' => [
					'required' => 'Please enter website.',
				],
				'include_in_email_template' => true,
			],
			'event_date' => [
				'type' => 'text',
				'class' => 'date',
				'label' => 'Event date',
				'placeholder' => 'Event date',
				'errors' => [
					'required' => 'Please enter event date.',
				],
				'include_in_email_template' => true,
			],
			'event_deadline' => [
				'type' => 'text',
				'class' => 'date',
				'label' => 'Deadline for participation',
				'placeholder' => 'Deadline for participation',
				'errors' => [
					'required' => 'Please enter deadline for participation.',
				],
				'include_in_email_template' => true,
			],
			'event_age_group' => [
				'type' => 'text',
				'label' => 'Expected age group(s) at event',
				'placeholder' => 'Expected age group(s) at event',
				'errors' => [
					'required' => 'Please enter expected age group(s) at event.',
				],
				'include_in_email_template' => true,
			],
			'event_attendance' => [
				'type' => 'text',
				'label' => 'Expected event attendance',
				'placeholder' => 'Expected event attendance',
				'errors' => [
					'required' => 'Please enter expected event attendance.',
				],
				'include_in_email_template' => true,
			],
			'referring_practice_name' => [
				'type' => 'text',
				'label' => 'Referring practice name',
				'placeholder' => 'Referring practice name',
				'errors' => [
					'required' => 'Please enter referring practice name.',
				],
				'include_in_email_template' => true,
			],
			'referring_doctor_name' => [
				'type' => 'text',
				'label' => 'Referring doctor\'s name',
				'placeholder' => 'Referring doctor\'s name',
				'errors' => [
					'required' => 'Please enter referring doctor\'s name.',
				],
				'include_in_email_template' => true,
			],
			'referring_doctor_email' => [
				'type' => 'email',
				'label' => 'Doctor\'s email address',
				'errors' => [
					'required' => 'Please enter the doctor\'s email.',
					'emailTLD' => 'Please enter a valid email address.',
				],
				'include_in_email_template' => true,
			],
			'referring_doctor_phone_number' => [
				'type' => 'tel',
				'label' => 'Doctor\'s phone number',
				'errors' => [
					'required' => 'Please enter the doctor\'s phone number.',
					// 'phoneUS' => 'Please enter a valid phone number.',
				],
				'include_in_email_template' => true,
			],
			'patient_name' => [
				'type' => 'text',
				'label' => 'Patient\'s full name',
				'placeholder' => 'Patient\'s full name',
				'errors' => [
					'required' => 'Please enter patient\'s name.',
				],
				'include_in_email_template' => true,
			],
			'parent_name' => [
				'type' => 'text',
				'label' => 'Parent/guardian’s full name',
				'placeholder' => 'Parent/guardian’s full name',
				'errors' => [
					'required' => 'Please enter parent/guardian’s name.',
				],
				'include_in_email_template' => true,
			],
			 'patient_phone_number' => [
				'type' => 'tel',
				'label' => 'Patient phone number',
				'errors' => [
					'required' => 'Please enter your patient phone number.',
					// 'phoneUS' => 'Please enter a valid phone number.',
				],
				'include_in_email_template' => true,
			],
			'patient_dob' => [
				'type' => 'text',
				'label' => 'Patient\'s birthday (mm/dd/yyyy)',
				'placeholder' => 'Patient\'s birthday (mm/dd/yyyy)',
				'errors' => [
					'required' => 'Please enter patient\'s birthday.',
					'date' => 'Please enter valid date.',
					'dob' => 'Please enter valid DOB.',
				],
				'include_in_email_template' => true,
			],
			'call_permission' => [
				'type' => 'radio',
				// 'class' => 'permission-group',
				'options' => ['Yes', 'No'],
				'label' => 'May we call to schedule an appointment?',
				'errors' => [
					'required' => 'Please select yes or no',
				],
				'include_in_email_template' => true,
			],
			'referred_reason_title' => [
				'type' => 'html',
				'label' => '<p class="primary"><strong>This patient is being referred for:*</strong></p>',
			],
			'referred_reason' => [
				'type' => 'checkbox-group',
				'class' => 'row-group-2',
				'label' => 'Reason(s) for referral (check all that apply):',
				'options' => ['Age', 'Special needs', 'Comprehensive care', 'Emergency needs', 'Limited care', 'Restorative needs', 'Space concerns/interceptive orthodontics'],
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'check_all_that_apply' => [
				'type' => 'checkbox-group',
				'class' => 'checkbox-group',
				'options' => ['Please call me before proceeding with treatment', 'I will send radiographs'],
				'label' => 'Check all that apply',
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'xrays_available' => [
				'type' => 'radio',
				// 'class' => 'checkbox-group',
				'options' => ['Yes', 'No'],
				'label' => 'Are x-rays available?',
				'errors' => [
					'required' => '',
				],
				'include_in_email_template' => true,
			],
			'panoramic_xray_title' => [
				'type' => 'html',
				'label' => (empty($this->location) ? '<p>If Panoramic X-ray is available, upload here</p>' : '<p>If Panoramic X-ray is available, upload here or send to <a href="mailto:'.antispambot($this->location->orthodontic_referral_to_emails_0_email).'?subject='.(strip_tags($this->location->post_title)).' - Dentist Referral Panoramic X-rays">'.antispambot($this->location->orthodontic_referral_to_emails_0_email).'</a></p>'),
			],
			'panoramic_xray_file' => [
				'type' => 'file',
				'class' => 'file-upload',
				'label' => 'Upload x-rays',
				'multiple' => true,
				'accept' => ['image/jpeg', 'image/png', 'image/gif'],
				'errors' => [
					'requiredFile' => 'Please upload your xray',
				],
				'include_in_email_template' => true,
			],
			'event_flyer_title' => [
				'type' => 'html',
				'label' => (empty($this->location) ? '<p>Have a flyer or other attachment to include? Upload below</p>' : '<p>Have a flyer or other attachment to include? Upload below or send to <a href="mailto:'.antispambot($this->location->orthodontic_referral_to_emails_0_email).'?subject='.(strip_tags($this->location->post_title)).' - Community Involvement Details">'.antispambot($this->location->orthodontic_referral_to_emails_0_email).'</a></p>'),
			],
			'event_flyer_file' => [
				'type' => 'file',
				'class' => 'file-upload',
				'label' => 'Upload files:',
				'multiple' => true,
				'accept' => ['image/jpeg', 'image/png', 'image/gif'],
				'errors' => [
					'requiredFile' => 'Please upload you flyer, if you have one',
				],
				'include_in_email_template' => true,
			],
			'panoramic_x-ray_file:hidden' => [
				'type' => 'file',
				'label' => 'Upload files:',
				'errors' => [
					'requiredFile' => 'Please upload your xray',
				],
				'include_in_email_template' => true,
			],
			'share_review_file' => [
				'type' => 'review_file',
				'class' => 'feedback-upload',
				'multiple' => true,
				'errors' => [
					'requiredFile' => 'Please upload photos, video, or both',
				],
				'include_in_email_template' => false,
			],
			'g-recaptcha-response' => [
				'type' => 'captcha',
				'label' => '',
				'errors' => [
					'captcha' => 'Please verify that you are not a robot.',
				],
				'key' => $this->brand->recaptcha_site_key,
				'include_in_email_template' => false,
			],
		];
	}

	public function generateForm($form_name) {
		$config = json_decode(json_encode(isset($this->form_configurations[$form_name]) ? $this->form_configurations[$form_name] : null)); # array to object
		if(empty($config)) return;

		partial('form', [
			'action' => null,
			'instance' => $this,
			'form_name' => $form_name,
			'form_class' => isset($config->form_class)?$config->form_class:null,
			'cta_class' => isset($config->cta_class)?$config->cta_class:null,
			'rows' => $config->rows,
			'tabs' => isset($config->tabs)?$config->tabs:null,
			'cta' => $config->cta,
			'required' => !empty($config->required) ? $config->required : [],
			'form_hash' => $this->getHashFromFormName($form_name),
			'form_errors' => $this->form_errors,
			'api_errors' => $this->api_errors,
			'hidden' => !empty($config->hidden) ? $config->hidden : [],
		]);

		$rules = [];
		$messages = [];

		foreach($config->rows as $row) {
			foreach($row as $ff) {
				$field = $this->getField($ff);
				if(isset($field)) {
					if($field->type == 'date') {
						wp_enqueue_style('pickadate-css');
						wp_enqueue_script('pickadate-js');
					}
					elseif($field->type == 'tel') {
						wp_enqueue_script('masked-input');
					}
					if(!in_array($ff, $config->required)) continue;
					foreach($field->errors as $method => $message) {
						$method = apply_filters('validation_method_name', $method);
						$rules[$ff][$method] = true;
						$messages[$ff][$method] = $message;
					}
				}
			}
		}

		wp_enqueue_script('jquery-validate');
		wp_localize_script('jquery-form-validator', '__va', [
			'name' => $form_name,
			'rules' => json_decode(json_encode($rules)),
			'messages' => json_decode(json_encode($messages)),
		]);
		wp_enqueue_script('jquery-form-validator');

	}

	final public function getField($field_name) {
		return isset($this->form_fields[$field_name]) ? (object)$this->form_fields[$field_name] : null;
	}

	protected function generateFormConfigurations() {
		$this->form_configurations['contact'] = [
			'cta' => 'Send request',
			'cta_class' => 'cta orange',
			'rows' => [
				['full_name','email_address'],
				['phone_number', 'office_preference'],
				['current_patient'],
				['comment'],
				['cta'],
			],
			'required' => [
				'full_name',
				'email_address',
				'phone_number',
				'office_preference',
				'current_patient',
				'g-recaptcha-response',
			],
			'subject' => 'Contact us - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		$this->form_configurations['consultation'] = [
			'cta' => 'Request free consultation',
			'cta_class' => 'primary blue',
			'rows' => [
				['full_name','email_address'],
				['phone_number', 'office_preference'],
				['current_patient'],
				['appt_day'],
				['appt_time'],
				['comment'],
			['cta'/*, 'virtual_consultation_link'*/],
			],
			'required' => [
				'full_name',
				'email_address',
				'phone_number',
				'office_preference',
				'current_patient',
				'g-recaptcha-response',
			],
			'subject' => 'Free Consultation - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

        $this->form_configurations['custom-mouthguard'] = [
			'cta' => 'Send request',
			'cta_class' => 'primary blue',
			'rows' => [
				['full_name','email_address'],
				['phone_number', 'office_preference'],
				['mouthguard_colors'],
				['current_patient'],
				['appt_day'],
				['appt_time'],
				['mouthguard_comment'],
				['cta'],
			],
			'required' => [
                'full_name',
				'email_address',
				'phone_number',
				'office_preference',
				'mouthguard_colors',
                'current_patient',
				'g-recaptcha-response',
			],
			'subject' => 'Free Custom Mouthguard - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		$this->form_configurations['consultation-no-virtual-consultation'] = [
			'cta' => 'Request free consultation',
			'cta_class' => 'primary blue',
			'rows' => [
				['full_name','email_address'],
				['phone_number', 'office_preference'],
				['current_patient'],
				['appt_day'],
				['appt_time'],
				['comment'],
				['cta'],
			],
			'required' => [
				'full_name',
				'email_address',
				'phone_number',
				'office_preference',
				'current_patient',
				'g-recaptcha-response',
			],
			'subject' => 'Free Consultation - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		$this->form_configurations['appointment'] = [
			'cta' => 'Send request',
			'cta_class' => 'primary blue',
			'rows' => [
				['full_name','email_address'],
				['phone_number', 'office_preference'],
				['current_patient'],
				['mouthguard_appointment'],
				['appt_day'],
				['appt_time'],
				['comment'],
				['cta'],
			],
			'required' => [
				'full_name',
				'email_address',
				'phone_number',
				'office_preference',
				'current_patient',
				'g-recaptcha-response',
			],
			'subject' => 'Appointment - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		$this->form_configurations['refer'] = [
			'cta' => 'Refer your friend',
			'cta_class' => 'primary blue',
			'rows' => [
				['info_title'],
				['full_name','email_address'],
				['friend_info_title'],
				['friend_full_name','friend_email_address'],
				['friend_phone_number', 'office_preference'],
				['friend_relationship','promo_code'],
				['comment'],
				['cta'],
			],
			'required' => [
				'full_name',
				'email_address',
				'friend_full_name',
				'friend_email_address',
				'friend_phone_number',
				'office_preference',
				'friend_relationship',
				'g-recaptcha-response',
			],
			'subject' => 'Refer a friend - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		$this->form_configurations['community'] = [
			'cta' => 'Send request',
			'cta_class' => 'primary blue',
			'rows' => [
				['contact_name','contact_title'],
				['contact_email_address','contact_phone_number'],
				['title_of_event', 'title_of_organization'],
				['sponsorship_type'],
				['website', 'event_location'],
				['event_date', 'event_deadline'],
				['event_age_group', 'event_attendance'],
				['event_flyer_title'],
				['event_flyer_file'],
				['comment'],
				['cta'],
			],
			'required' => [
				'contact_name',
				'contact_title',
				'contact_email_address',
				'contact_phone_number',
				'title_of_event',
				'title_of_organization',
				'g-recaptcha-response',
			],
			'subject' => 'Community Involvement - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		$this->form_configurations['community']['hidden']['office_preference'] = empty($this->location) ? $this->brand->ID : $this->location->ID;

		$this->form_configurations['orthodontic-referral'] = [
			'cta' => 'Send request',
			'cta_class' => 'primary blue',
			'rows' => [
				['referring_doctor_name', 'referring_doctor_email'],
				['referring_doctor_phone_number', 'office_preference'],
				['patient_name','parent_name'],
				['patient_phone_number', 'patient_dob'],
				['call_permission'],
				['comment'],
				['xrays_available'],
				['panoramic_xray_file'],
				['referred_reason'],
				['cta'],
			],
			'required' => [
				'referring_doctor_name',
				'referring_doctor_email',
				'referring_doctor_phone_number',
				'office_preference',
				'patient_name',
				'parent_name',
				'patient_phone_number',
				'call_permission',
				'xrays_available',
				'g-recaptcha-response',
			],
			'subject' => 'Dentist Referral - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		$this->form_configurations['review'] = [
			'cta' => 'Submit your review',
			'cta_class' => 'primary blue',
			'rows' => [
				['full_name','email_address'],
				['phone_number','office_preference'],
				['review'],
				['share_review_file'],
				['review_confirmation'],
				['cta'],
			],
			'required' => [
				'full_name',
				'email_address',
				'office_preference',
				'review_confirmation',
				'g-recaptcha-response',
			],
			'subject' => 'Review - Form Submission',
			'recipients' => 'bbuisker@simspecialty.com',
			'cc' => 'kathleenf@mdgadvertising.com',
			'bcc' => 'webdept@mdgadvertising.com, bbuisker@simspecialty.com',
			'supports_email_notification' => true,
			'custom_template' => false,
			//'thank_you_page' => get_permalink(4997),
		];

		// Add UTMs to each form configuration
		if (!empty($this->form_configurations)) {
			foreach ($this->form_configurations as $form_name => $config) {
				$form_url = 'http'.($_SERVER['HTTPS'] == 'on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$this->form_configurations[$form_name]['hidden']['form_name']     = $form_name;
				$this->form_configurations[$form_name]['hidden']['form_url']      = $form_url;
				if (!empty($_SESSION['UTMs'])) {
					$this->form_configurations[$form_name]['hidden']['utm_source']    = $_SESSION['UTMs']->utm_source;
					$this->form_configurations[$form_name]['hidden']['utm_medium']    = $_SESSION['UTMs']->utm_medium;
					$this->form_configurations[$form_name]['hidden']['utm_campaign']  = $_SESSION['UTMs']->utm_campaign;
					$this->form_configurations[$form_name]['hidden']['utm_term']      = $_SESSION['UTMs']->utm_term;
					$this->form_configurations[$form_name]['hidden']['utm_content']   = $_SESSION['UTMs']->utm_content;
				}
				$this->form_configurations[$form_name]['hidden']['gclid']         = $_SESSION['gclid'] ?? '';
				$this->form_configurations[$form_name]['hidden']['fbclid']        = $_SESSION['fbclid'] ?? '';
				$this->form_configurations[$form_name]['hidden']['msclkid']       = $_SESSION['msclkid'] ?? '';
			}
		}
	}

	protected function validateFormField($field_name, $errors, $value) {
		foreach($errors as $method => $message) {
			$pass = true;
			switch($method) {
				case 'phoneUS':
					if(preg_match('/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/', $value) !== 1) $pass = false;
					break;
				case 'emailTLD':
					if(filter_var($value, FILTER_VALIDATE_EMAIL) === false) $pass = false;
					break;
				case 'captcha':
					if(!strlen($value)) $pass = false;
					else {
						$response = json_decode(wp_remote_retrieve_body(wp_remote_get('https://www.google.com/recaptcha/api/siteverify?'.http_build_query([
							'secret' => $this->brand->recaptcha_secret_key,
							'response' => $value,
							'remoteip' => $_SERVER['REMOTE_ADDR'],
						]))));

						if(empty($response->success)) $pass = false;
					}
					break;
				case 'requiredFile':
					$found_file_key = $this->getXrayFileKey();
					if(isset($_FILES[$found_file_key]) && !strlen($_FILES[$found_file_key]['name'])) $pass = false;
					break;
				default:
					if(is_string($value) && !strlen($value) && (!in_array($field_name, ['panoramic_xray_file', 'panoramic_xray_file:hidden', 'event_flyer_file']))) $pass = false;
					break;
			}
			if(!$pass) {
				return $message;
			}
		}

		return null;
	}

	private function getHashFromFormName($form_name) {
		return rtrim(base64_encode(substr(hash('ripemd160', $form_name), 1, 11)), '=');
	}

	private function getFormFromHash($hash) {
		foreach($this->form_configurations as $name => $_) {
			if($this->getHashFromFormName($name) == $hash) return $name;
		}
		return null;
	}

	protected function getFormErrors() {
		$data = $_POST;
		$errors = [];
		foreach($this->current_form->required as $ff) {
			$field = $this->getField($ff);
			$error = $this->validateFormField($ff, $field->errors, !isset($data[$ff]) ? null : $data[$ff]);
			if(!empty($error)) $errors[$ff] = $error;
		}
		return $errors;
	}

	final protected function verifyFormSubmission() {
		if(!empty($_POST) && !empty($_POST['__f'])) {
			# Find form from hash
			$form_name = $this->getFormFromHash($_POST['__f']);
			if(empty($form_name)) return;
			$this->current_form_name = $form_name;
			$this->current_form = (object)$this->form_configurations[$form_name];

			$this->form_errors = $this->getFormErrors();
			if(empty($this->form_errors)) {
				$this->processFormSubmission();
			}
		}
	}

	private function get_review_files_links($review_files, $seperator) {
		$files = json_decode(stripslashes($review_files));
		$urls = [];
		foreach ($files as $file) {
			$urls[] = brand_host().'/wp-content/uploads/review_files/'.rawurlencode($file->file);
		}
		return implode($seperator, $urls);
	}

	private function getTemplateBody() {
		$template_file = empty($this->current_form->custom_template) ? 'default' : $this->current_form_name;
		$email_template = get_stylesheet_directory().'/inc/email-templates/'.$template_file.'.tpl';
		if(!file_exists($email_template)) return '';

		$data = sanitize_array_for_output($_POST);
		$fields = ['ID' => $this->inserted_id];

		foreach($this->current_form->rows as $row) {
			foreach($row as $ff) {
				$field = $this->getField($ff);
				if(isset($field)) {
					//&& !empty($field->label)
					if($field->include_in_email_template) $fields[$field->label] = !isset($data[$ff]) ? '' : $data[$ff];
				}
			}
		}

		if (isset($_POST['fileuploader-list-review_files'])) {
			$fields['Files uploaded'] = $this->get_review_files_links($_POST['fileuploader-list-review_files'], '<br/>');
		}

		/* Temp for testing */
		$recipient = 'info@kristoorthodontics.com';
		$recipient_by_form = get_email_addresses_for_form($this->current_form_name, $_POST['office_preference']);

		if(!empty($recipient_by_form)) $recipient = $recipient_by_form;

		$fields['Lead Source'] = empty($_POST['utm_source']) ? 'direct' : $data['utm_source'];
		$fields['Lead Details'] = empty($_POST['utm_medium']) ? '' : $data['utm_medium'];

		$offices = $this->getOffices();
		$fields['Preferred office name'] = $offices[$_POST['office_preference']];
		$fields['Form URL'] = $_POST['form_url'];
		$fields['Timestamp'] = $data['timestamp_formatted'].' (Central)';
		$fields['IP address'] = $data['ip_address'];
		$fields['Client email'] = $recipient;

		unset($fields['Your office preference']);
		unset($fields['Upload files:']);

		$field_rows = [];
		foreach($fields as $k => $v) {
			if(is_string($v)) {
				if(!strlen($v)) $v = 'N/A';
				$field_rows[] = '<b>'.$k.'</b> '.$v;
			}
		}

		$data['fields'] = implode('<br><br>', $field_rows);

		$content = file_get_contents($email_template);
		foreach($data as $k => $v) {
			if(is_string($v)) {
					if(!empty($v)) {
							$content = str_replace('[%'.$k.'%]', $v, $content);
					}
				}
			}
		return $content;
	}

	protected function invokeAPI() {
		$data = sanitize_array_for_output($_POST);
	}

	private function getXrayFileKey() {
		foreach (array_keys($_FILES) as $k) {
			if (starts_with($k, 'panoramic_xray_')) {
				return $k;
			}
		}
		return $ret ?? '';
	}

	protected function processFormSubmission() {
		$this->invokeAPI();

		$row_values = []; foreach($this->current_form->rows as $row) $row_values = array_merge($row_values, $row);

		if (in_array('panoramic_xray_file', $row_values) || in_array('panoramic_xray_file:hidden', $row_values)) {
			define('MAX_FILE_COUNT', 5);
			define('MAX_FILE_SIZE_MB', 5);
			define('MAX_FILE_SIZE', MAX_FILE_SIZE_MB*1024*1024);

			define('XRAY_DIRECTORY', WP_CONTENT_DIR.'/uploads/xray/');
			define('XRAY_URL', WP_CONTENT_URL.'/uploads/xray/');

			$attachments = [];
			$found_file_key = current(array_filter(array_keys($_FILES), function($a) { return starts_with($a, 'panoramic_xray_'); }));
			$files = is_array($_FILES) && (!empty($found_file_key) && array_key_exists($found_file_key, $_FILES)) ? $_FILES[$found_file_key] : null;
			$key = in_array('panoramic_xray_file', $row_values) ? 'panoramic_xray_file' : 'panoramic_xray_file:hidden';

			$f = [];
			foreach ($files['name'] as $k => $v) {
				$f[] = (object)[
					'name' => $files['name'][$k],
					'type' => $files['type'][$k],
					'tmp_name' => $files['tmp_name'][$k],
					'error' => $files['error'][$k],
					'size' => $files['size'][$k],
				];
			}
			$files = $f;

			if(in_array('panoramic_xray_file', $row_values)) {
				if(!empty($files)) {
					foreach ($files as $file_key => $file) {
						if(empty($file->tmp_name)) {
							if(in_array('panoramic_xray_file', $this->current_form->required)) {
								$this->api_errors[] = 'We were unable to process your file. Please try again.';
							}
						}
						else {
							// Check if there was a server-related reason why the file could not be uploaded
							if($file->error != UPLOAD_ERR_OK) {
								$this->api_errors[] = 'The server responded with error code ' . $file->error . '.';
							}

							// Check the file is empty
							elseif($file->size == 0) {
								$this->api_errors[] = 'The file "'.$file->name.'" is empty.';
							}

							// Check if the file exceeds the maximum size restriction
							elseif($file->size > MAX_FILE_SIZE) {
								$this->api_errors[] = 'This file "'.$file->name.'" is too large (max: '.MAX_FILE_SIZE_MB.' MiB).';
							}

							// Check if the file has a valid extension
							elseif(!$this->hasValidExtension($file->name)) {
								$this->api_errors[] = 'This file "'.$file->name.'" cannot be uploaded. Only photos, videos and documents are allowed.';
							}

							// If server-side validation succeeded, return the path to the file.
							else {
								$parts = pathinfo($file->name);
								$filename = str_replace(' ', '_', $parts['filename']).'_'.time().'.'.$parts['extension'];
								if(!file_exists(XRAY_DIRECTORY)) mkdir(XRAY_DIRECTORY, 0755);
								$test = move_uploaded_file($file->tmp_name, XRAY_DIRECTORY.$filename);
								$attachments[] = $_POST[$key][$file_key] = XRAY_DIRECTORY.$filename;
							}
						}
					}
				}
			}
		}

		if (in_array('event_flyer_file', $row_values) || in_array('event_flyer_file:hidden', $row_values)) {
			define('MAX_FILE_COUNT', 5);
			define('MAX_FILE_SIZE_MB', 5);
			define('MAX_FILE_SIZE', MAX_FILE_SIZE_MB*1024*1024);

			define('FLYER_DIRECTORY', WP_CONTENT_DIR.'/uploads/flyer/');
			define('FLYER_URL', WP_CONTENT_URL.'/uploads/flyer/');

			$attachments = [];
			$found_file_key = current(array_filter(array_keys($_FILES), function($a) { return starts_with($a, 'event_flyer_'); }));
			$files = is_array($_FILES) && (!empty($found_file_key) && array_key_exists($found_file_key, $_FILES)) ? $_FILES[$found_file_key] : null;
			$key = in_array('event_flyer_file', $row_values) ? 'event_flyer_file' : 'event_flyer_file:hidden';

			$f = [];
			foreach ($files['name'] as $k => $v) {
				$f[] = (object)[
					'name' => $files['name'][$k],
					'type' => $files['type'][$k],
					'tmp_name' => $files['tmp_name'][$k],
					'error' => $files['error'][$k],
					'size' => $files['size'][$k],
				];
			}
			$files = $f;

			if(in_array('event_flyer_file', $row_values)) {
				if(!empty($files)) {
					foreach ($files as $file_key => $file) {
						if(empty($file->tmp_name)) {
							if(in_array('event_flyer_file', $this->current_form->required)) {
								$this->api_errors[] = 'We were unable to process your file. Please try again.';
							}
						}
						else {
							// Check if there was a server-related reason why the file could not be uploaded
							if($file->error != UPLOAD_ERR_OK) {
								$this->api_errors[] = 'The server responded with error code ' . $file->error . '.';
							}

							// Check the file is empty
							elseif($file->size == 0) {
								$this->api_errors[] = 'The file "'.$file->name.'" is empty.';
							}

							// Check if the file exceeds the maximum size restriction
							elseif($file->size > MAX_FILE_SIZE) {
								$this->api_errors[] = 'This file "'.$file->name.'" is too large (max: '.MAX_FILE_SIZE_MB.' MiB).';
							}

							// Check if the file has a valid extension
							elseif(!$this->hasValidExtension($file->name)) {
								$this->api_errors[] = 'This file "'.$file->name.'" cannot be uploaded. Only photos, videos and documents are allowed.';
							}

							// If server-side validation succeeded, return the path to the file.
							else {
								$parts = pathinfo($file->name);
								$filename = str_replace(' ', '_', $parts['filename']).'_'.time().'.'.$parts['extension'];
								if(!file_exists(FLYER_DIRECTORY)) mkdir(FLYER_DIRECTORY, 0755);
								$test = move_uploaded_file($file->tmp_name, FLYER_DIRECTORY.$filename);
								$attachments[] = $_POST[$key][$file_key] = FLYER_DIRECTORY.$filename;
							}
						}
					}
				}
			}
		}

		if (empty($this->api_errors)) {
			$this->appendPostData();
			$this->captureLead();
			if($this->current_form->supports_email_notification) $this->sendEmailNotification($attachments ?? false);
			// if(isset($_POST['ajaxing'])) {
				die('valid');
			// }
			//$this->processRedirect();
		} else {
			die(json_encode($this->api_errors));
		}
		die;
	}

	private function appendPostData() {
		if(empty($_POST['form_url'])) $_POST['form_url'] = 'http'.($_SERVER['HTTPS'] == 'on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if (!isset($_POST['review_files_links']) && isset($_POST['fileuploader-list-review_files'])) {
			$_POST['review_files_links'] = $this->get_review_files_links($_POST['fileuploader-list-review_files'], ' | ');
		}
		if(!isset($_POST['timestamp_formatted'])) {
			$date = new DateTime(current_time('Y-m-d H:i:s'), new DateTimeZone('America/Chicago'));
			$date->setTimezone(new DateTimeZone('America/Chicago'));
			$_POST['timestamp_formatted'] = $date->format('F j, Y, g:i a');
		}
		if(isset($_POST['contact_method'])) $_POST['contact_method'] = implode(",", $_POST['contact_method']);
		if(isset($_POST['referred_reason'])) $_POST['referred_reason'] = implode(", ", $_POST['referred_reason']);
		if(isset($_POST['check_all_that_apply'])) $_POST['check_all_that_apply'] = implode(",", $_POST['check_all_that_apply']);
		if(!isset($_POST['timestamp_sql'])) $_POST['timestamp_sql'] = current_time('mysql');
		if(!isset($_POST['ip_address'])) $_POST['ip_address'] = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? current(explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR'])) : $_SERVER['REMOTE_ADDR'];;
		if(!isset($_POST['subject'])) $_POST['subject'] = $this->current_form->subject;
		if(!is_live()) $_POST['subject'] = '(DEV) '.$_POST['subject'];
	}

	private function captureLead() {
		global $wpdb;
		$inserted = $wpdb->query($wpdb->prepare('insert into form_submissions(ID, form, ip_address, timestamp, metadata) values(NULL, %s, %s, %s, %s);',
			$this->current_form_name === 'consultation-no-virtual-consultation' ? 'pediatric-consultation' : 'pediatric-'.$this->current_form_name,
			$_POST['ip_address'],
			$_POST['timestamp_sql'],
			json_encode(sanitize_array_for_output($_POST))
		));
		$this->inserted_id = $inserted ? $wpdb->query("SELECT LAST_INSERT_ID() FROM form_submissions") : false;
	}

	private function sendEmailNotification($attachments = false) {
		# Construct headers
		$headers = [
			'From: Kristo Orthodontics Autoresponder <mailer@mdgadvertising.com>',
			'Content-Type: text/html; charset=utf-8',
		];

		$recipient = 'info@kristoorthodontics.com';
		$recipient_by_form = get_email_addresses_for_form($this->current_form_name, $_POST['office_preference']);
		$recipient_cc_by_form = get_cc_email_addresses_for_form($this->current_form_name, $_POST['office_preference']);
		$recipient_bcc_by_form = get_bcc_email_addresses_for_form($this->current_form_name, $_POST['office_preference']);


		if(!empty($recipient_by_form)) $recipient = $recipient_by_form;
		if(!empty($recipient_cc_by_form)) $headers[] = 'Cc: '.$recipient_cc_by_form;
		if(!empty($recipient_bcc_by_form)) $headers[] = 'Bcc: '.$recipient_bcc_by_form;

		$referring_doctor_email = $_POST['referring_doctor_email'] ? $_POST['referring_doctor_email'] : '';
		if(!empty($referring_doctor_email)) $headers[] = 'Cc: ' . $referring_doctor_email;

		# Deploy email
		if(!is_live()) $this->current_form->subject = '(DEV) '.$this->current_form->subject;
		if(!is_local()) wp_mail($recipient, $this->current_form->subject, $this->getTemplateBody(), $headers, $attachments);
		if(is_local()) wp_mail('webdept@mdgadvertising.com', $this->current_form->subject, $this->getTemplateBody(), $headers, $attachments);

		// Delete attachments after attaching them to the email
		if (!empty($attachments)) {
			foreach ($attachments as $a) if (file_exists($a)) unlink($a);
		}
	}

	protected function processRedirect() {
		wp_redirect($this->current_form->thank_you_page, 302);
		exit;
	}

	protected function hasValidExtension($file) {
		$extensions = array('eps','3g2','3gp','3gpp','aif','aiff','asf','au','avi','dat','divx','doc','docx','dv','dwf','dwg','f4v','fdr','flv','gif','giff','htm','html','jfif','jpeg','jpg','m2ts','m4a','m4v','mdb','mid','midi','mkv','mod','mov','mp3','mp4','mpe','mpeg','mpeg4','mpegps','mpg','mts','nsv','odt','ogg','ogm','ogv','pdf','pic','pict','png','pps','ppsx','ppt','pptx','psd','pub','qt','ra','ram','rm','rmi','rtf','rv','swf','tga','tif','tiff','tod','ts','txt','vob','wav','wma','wmf','wmv','wpd','wps','xls','xlsx','zip');
		return in_array(pathinfo($file, PATHINFO_EXTENSION), $extensions);
	}

	private function locationNameFromID($id) {
		switch($id) {
			case 3343: return 'Holmen';
			case 3330: return 'La Crosse';
			case 3393: return 'Maplewood';
			case 3385: return 'Onalaska';
			case 3396: return 'Roseville';
			case 3388: return 'West Salem';
			case 1243: return 'Amery';
			case 1245: return 'Baldwin';
			case 1247: return 'Black River Falls';
			case 1249: return 'Bloomer';
			case 1251: return 'Chippewa Falls';
			case 1253: return 'Eau Claire';
			case 1255: return 'Marinette';
			case 1257: return 'Menomonie';
			case 1259: return 'Merrill';
			case 1263: return 'New Richmond';
			case 1265: return 'Rice Lake';
			case 1267: return 'River Falls';
			case 1269: return 'Stanley';
			case 1271: return 'Wausau';
			case 14242: return 'Chapman';
			case 14097: return 'Centennial';
			case 12376: return 'Denver';
			case 13035: return 'Dietmeier';
			case 12153: return 'Shawano';
			case 9831: return 'Rapids';
			case 8653: return 'Beaver Dam';
			case 8646: return 'Madison';
			case 8649: return 'Sun Prairie';
			case 18132: return 'Amery';
			case 18129: return 'Menomonie';
			case 18124: return 'Eau Claire';
			case 18121: return 'Rice Lake';
			case 18116: return 'Hudson';
			case 18113: return 'Chippewa Falls';


			default: return strval($id).': un-handled';
		}
	}

	//=======================================================================

	final protected function getMouthguardColors() {
		return [
			'Clear' => 'Clear',
			'Black' => 'Black',
			'Blue' => 'Blue',
			'Green' => 'Green',
			'Maroon' => 'Maroon',
			'Pink' => 'Pink',
			'Purple' => 'Purple',
			'Red' => 'Red',
			'White' => 'White',
			'Yellow' => 'Yellow',
			'Black/Red' => 'Black/Red',
			'Black/White' => 'Black/White',
			'Blue/Yellow' => 'Blue/Yellow',
			'Purple/White' => 'Purple/White',
			'Purple/White' => 'Purple/White',
			'Green Camo' => 'Green Camo',
			'Pink Camo' => 'Pink Camo',
			'Patriotic' => 'Patriotic',
			'Rainbow' => 'Rainbow',
			'Unsure' => 'Unsure'
		];
	}

	final protected function getStateList() {
		return [
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'DC' => 'District Of Columbia',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming',
		];
	}

	final protected function getOffices() {
		$brand_locations = get_locations_for_brand($this->brand->ID);
		$loc_array = [];
		foreach ($brand_locations as $location) {
			$loc_array[$location->ID] = $location->post_title;
		}
		return $loc_array;
	}

	public function on_form_submission_manager() {
		include_once get_stylesheet_directory().'/inc/form-submissions/form_submission_manager.php';
		if(function_exists('displaySubmissions')) displaySubmissions();
	}

	private function registerFormSubmissionManager() {
		add_action('admin_menu', function() {
			$menu_id = add_menu_page(
				'Website Submissions',
				'Form Submissions',
				'manage_form_submissions',
				'form-submissions',
				[$this, 'on_form_submission_manager'],
				'dashicons-list-view',
				4
			);
			add_action("load-$menu_id", function() {
				$args = array(
					'label' => 'Number of items per page:',
					'default' => 15,
					'option' => 'submissions_per_page'
				);
				add_screen_option('per_page', $args);
			});
		});

		add_filter('set-screen-option', function($status, $option, $value) {
		  return $value;
		}, 10, 3);

		add_action('init', function() {
			add_role('form_manager', 'Form Manager', array());
		});

		add_action('admin_init', function() {
			$role = get_role('administrator');
			$role->add_cap('manage_form_submissions');

			$role = get_role('form_manager');
			$role->add_cap('read');
			$role->add_cap('manage_form_submissions');
			$role->add_cap('unfiltered_html');
		});

		//=====================================[SUBMISSION EXPORT]=====================================//
		add_action('admin_init', function() {
			global $wpdb;

			$wpdb->query("
			CREATE TABLE IF NOT EXISTS `form_submission_notes` (
			`ID` int(11) NOT NULL,
			`userID` int(11) NOT NULL,
			`formName` varchar(100) NOT NULL,
			`formID` int(11) NOT NULL,
			`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`note` text NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			");

			$wpdb->query("
			CREATE TABLE IF NOT EXISTS `form_submissions` (
			`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`form` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
			`ip_address` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
			`timestamp` timestamp NULL DEFAULT NULL,
			`metadata` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
			PRIMARY KEY (`ID`)
			) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			");

			if(isset($_GET['export'])) {
				include_once get_stylesheet_directory().'/inc/form-submissions/form_submission_manager.php';
				global $wpdb;
				$where = '';

				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());

				if(!in_array($current, get_all_views())) return;

				$form = $current;

				$db = null;

				$mapping = array(
					'contact' => 'contact',
					'consultation' => 'consultation',
					'appointment' => 'appointment',
					'refer' => 'refer',
					'community' => 'community',
					'orthodontic-referral' => 'orthodontic-referral',
					'review' => 'review',
                    'invisalign-virtual-care' => 'invisalign-virtual-care',
                    'pediatric-contact' => 'pediatric-contact',
                    'pediatric-consultation' => 'pediatric-consultation',
                    'pediatric-appointment' => 'pediatric-appointment',
                    'pediatric-refer' => 'pediatric-refer',
                    'pediatric-community' => 'pediatric-community',
                    'pediatric-orthodontic-referral' => 'pediatric-orthodontic-referral',
                    'pediatric-review' => 'pediatric-review',
				);
				$form = $mapping[$current];
				$results = $wpdb->get_results("select * from form_submissions where form='$form' order by timestamp desc;");

				ob_start();
				$fh = fopen('php://output', 'w');

				// Preprocess all results
				foreach($results as $k => $r) {
					$metadata = (array)json_decode($r->metadata);
					$data = (array)$r;
					unset($data['metadata']);
					/*unset($metadata['form_name'], $metadata['email_address'], $metadata['user_port'], $metadata['submit'], $metadata['submit_btn'], $metadata['fields_message'], $metadata['message'], $metadata['fields_comments'], $metadata['email_address_confirm'], $metadata['g-recaptcha-response'], $metadata['timestamp'], $metadata['ip'], $metadata['fields_first_name'], $metadata['first_name'], $metadata['last_name'], $metadata['fields_last_name'], $metadata['name'], $metadata['fields_name'], $metadata['email'], $metadata['submit']);*/

					$data = array_merge($data, $metadata);
					$data['office_preference'] = $this->locationNameFromID($data['office_preference']);

					$data['form_id'] = get_form_name($current);

					$previous_notes = $wpdb->get_results($wpdb->prepare('select * from form_submission_notes where formName=%s and formID=%d order by timestamp desc', $form, $r->id));
					$notes = [];
					foreach($previous_notes as $note) {
						$user_data = get_userdata($note->userID);
						$username = '';
						if(!empty($user_data->display_name)) $username = $user_data->display_name;
						else $username = $user_data->user_login;
						$attribution = $username.'; '.date('F j, Y, g:i A', strtotime($note->timestamp));
						$notes[] = $note->note.PHP_EOL.'- '.$attribution;
					}
					$data['notes'] = implode(PHP_EOL.'---------------------------------'.PHP_EOL, $notes);

					$r->data = $data;
				}

				// Fetch a unique array of all keys
				$keys = array();
				$mapped_keys = array();
				foreach($results as $r) foreach($r->data as $k => $v) if(!in_array($k, $keys)) $keys[] = $k;
				foreach($keys as $k => $v) {
					if(empty($key_label_mapping[$v])) {
						unset($keys[$k]);
					}
					else {
						$mapped_keys[$k] = $key_label_mapping[$v];
					}
				}

				// Export the header column
				fputcsv($fh, $mapped_keys);

				// Export all data
				foreach($results as $r) {
					$data = array();
					foreach($keys as $key) {
						if(isset($r->data[$key])) $data[] = stripslashes($r->data[$key]);
						else $data[] = 'N/A';
					}
					fputcsv($fh, $data);
				}

				fclose($fh);

				$contents = ob_get_clean();
				header("Content-type: text/csv");
				header("Content-Disposition: attachment; filename=\"submission-export_".date('m-d-Y_h-i-sa').'.csv\"');
				// header("Content-Type: application/force-download");
				// header("Content-Type: application/octet-stream");
				// header("Content-Type: application/download");
				header("Content-Description: File Transfer");
				header('Connection: Keep-Alive');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header("Pragma: no-cache");
				// header('Pragma: public');
				header('Content-Length: '.strlen($contents));
				echo $contents;

				exit;
			}
		});
	}
}
