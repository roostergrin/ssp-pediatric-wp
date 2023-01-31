<?php

//=====================================[SHARED]=====================================//
	$key_label_mapping = array(
		'ID' => 'Submission ID',
		'form_id' => 'Form Name',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'name' => 'Name',
		'full_name' => 'Name',
		'referring_practice_name' => 'Referring Practice',
		'referring_doctor_name' => 'Referring Doctor',
		'referring_doctor_email' => 'Referring Doctor\'s email',
		'referring_doctor_phone_number' => 'Referring Doctor Phone Number',
		'call_permission' => 'Permission to call to schedule appointment',
		'xrays_available' => 'Are X-rays available?',
		'parent_name' => 'Parent Name',
		'patient_name' => 'Patient Name',
		'patient_phone_number' => 'Patient Phone #',
		'patient_dob' => 'Patient DOB',
		'referred_reason' => 'Referred reason',
		'contact_name' => 'Contact name',
		'contact_title' => 'Contact title',
		'contact_email_address' => 'Contact email',
		'title_of_event' => 'Title of event or sponsorship',
		'website' => 'Website',
		'event_location' => 'Event Location',
		'event_date' => 'Event date',
		'event_deadline' => 'Deadline for participation',
		'event_age_group' => 'Expected age groups at event',
		'event_attendance' => 'Expected event attendance',
		'contact_phone_number' => 'Contact phone number',
		'email' => 'Email',
		'email_address' => 'Email',
		'email_address_confirm' => 'Email Address (Confirm)',
		'recipients' => 'Notification Email Recipients',
		'subject' => 'Notification Email Subject',
		'eid' => 'Entity ID',
		'location' => 'Location Name',
		'location_url' => 'Location URL',
		'timestamp_formatted' => 'Timestamp (Formatted)',
		'review_files_links' => 'Review Files (Links)',
		// 'timestamp_sql' => 'Timestamp (ISO 8601)',
		'company' => 'Company',
		'country' => 'Country',
		'address_1' => 'Address 1',
		'address_2' => 'Address 2',
		'city' => 'City',
		'state' => 'State',
		'zip_code' => 'ZIP Code',
		'zip' => 'ZIP Code',
		'phone' => 'Phone Number',
		'phone_number' => 'Phone Number',
		'office_preference' => 'Office Preference',
		//'form_type' => 'Form Type',
		'current_patient' => 'Current Patient',
		//'contact_method' => 'Contact Method',
		'question' => 'Question',
		'appt_day' => 'Appointment Day',
		'appt_time' => 'Appointment Time',
		'mouthguard_appointment' => 'Is this appointment for a custom mouthguard?',
		'mouthguard_comment' => 'Mouthguard Comment',
		'mouthguard_colors' => 'Mouthguard Colors',
		'position' => 'Position',
		'g-recaptcha-response' => '',
		'timestamp' => 'Timestamp',
		'ip' => 'IP Address',
		'ip_address' => 'IP Address',
		'notes' => 'Notes',
		'type_of_inquiry' => 'Type of Inquiry',
		'questions_comments' => 'Questions/comments',
		'fields_comments' => 'Questions/comments',
		'message' => 'Questions/comments',
		'fields_message' => 'Questions/comments',
		'comment' => 'Questions/comments',
		'utm_source' => 'Campaign Source (utm_source)',
		'utm_medium' => 'Campaign Medium (utm_medium)',
		'utm_term' => 'Campaign Term (utm_term)',
		'utm_content' => 'Campaign Content (utm_content)',
		'utm_campaign' => 'Campaign Name (utm_campaign)',
		'form_url' => 'Form URL',
		'form_name' => 'Form Name',
		'check_all_that_apply' => 'Check all that apply',
		'brand' => 'Brand',
	);

	function get_initial_form() {
		return get_all_views()[0];
	}

	function get_all_views() {
		return ['contact', 'questions', 'consultation', 'appointment', 'refer', 'community', 'orthodontic-referral', 'review', 'custom-mouthguard', 'invisalign-virtual-care', 'pediatric-contact', 'pediatric-appointment', 'pediatric-community', 'pediatric-orthodontic-referral'];
	}

	function is_valid_form_name($form_id) {
		return in_array($form_id, get_all_views());
	}

	function get_form_name($form_id) {
		if(!is_valid_form_name($form_id)) return 'Other';

		switch($form_id) {
			case 'contact':
				return 'Contact';
				break;
			case 'questions':
				return 'Free Consultation Questions';
				break;
			case 'consultation':
				return 'Consultation';
				break;
			case 'appointment':
				return 'Appointment';
				break;
			case 'refer':
				return 'Refer a friend';
				break;
			case 'community':
				return 'Community Involvement';
				break;
			case 'orthodontic-referral':
				return 'Dentist Referral';
				break;
			case 'review':
				return 'Review';
				break;
			case 'custom-mouthguard':
				return 'Custom Mouthguard';
				break;
			case 'invisalign-virtual-care':
				return 'Invisalign Virtual Care';
				break;
			case 'pediatric-contact':
				return 'Pediatric Contact';
				break;
			case 'pediatric-appointment':
				return 'Pediatric Appointment';
				break;
			case 'pediatric-community':
				return 'Pediatric Community Involvement';
				break;
			case 'pediatric-orthodontic-referral':
				return 'Pediatric Dentist Referral';
				break;
			default:
				return 'Unknown';
				break;
		}
	}

	//=====================================[SUBMISSION DETAILS]=====================================//
	if(!empty($_GET['page']) && $_GET['page'] == 'form-submissions' && !empty($_GET['submission']) && is_numeric($_GET['submission'])) {
		if(!empty($_POST)) {
			global $wpdb;
			$form = $_POST['form_name'];
			$id = $_POST['form_id'];
			$note = $_POST['note'];
			$user_id = get_current_user_id();

			if(strlen($form) && strlen($id) && strlen($note) && !empty($user_id)) {
				$wpdb->query($wpdb->prepare('insert into form_submission_notes (userID, formName, formID, note) values(%d,%s,%d,%s)', $user_id, $form, $id, $note));
			}
		}
		include 'single-form-submission.php';
	}

	//=====================================[SUBMISSION LISTING]=====================================//
	else {
		if(!class_exists('WP_List_Table')) require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');

		class Form_Submissions_Table extends WP_List_Table {
			private $_items_per_page = 0;
			private $_item_total_count = 0;

			public $mapping = array(
				'contact' => 'contact',
				'questions' => 'questions',
				'consultation' => 'consultation',
                'custom-mouthguard' => 'custom-mouthguard',
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

			function get_columns() {
				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());
                switch ($current) {
                    case "community" :
	                    $columns = [
		                    'ID' => 'ID',
		                    'name' => 'Contact Name',
		                    'date' => 'Date of Submission',
		                    'email' => 'Contact Email',
		                    'office_preference' => 'Office Preference',
							'brand_name' => 'Brand',
	                    ];
                    break;
                        case "pediatric-community" :
	                    $columns = [
		                    'ID' => 'ID',
		                    'name' => 'Contact Name',
		                    'date' => 'Date of Submission',
		                    'email' => 'Contact Email',
		                    'office_preference' => 'Office Preference',
							'brand_name' => 'Brand',
	                    ];
                    break;
                    case "orthodontic-referral":
	                    $columns = [
		                    'ID' => 'ID',
		                    'name' => 'Patient Name',
		                    'referring_practice_name' => 'Referring Practice Name',
		                    'date' => 'Date of Submission',
		                    // 'email' => 'Patient Email',
							'office_preference' => 'Office Preference',
							'brand_name' => 'Brand',
		                    'phone' => 'Patient Phone',		                    
	                    ];
                    break;
                        case "pediatric-orthodontic-referral":
	                    $columns = [
		                    'ID' => 'ID',
		                    'name' => 'Patient Name',
		                    'referring_practice_name' => 'Referring Doctor',
		                    'date' => 'Date of Submission',
		                    // 'email' => 'Patient Email',
							'office_preference' => 'Office Preference',
							'brand_name' => 'Brand',
		                    'phone' => 'Patient Phone',		                    
	                    ];
                    break;
                    case "refer":
	                    $columns = [
                        'ID' => 'ID',
                        'name' => 'Name',
                        'date' => 'Date of Submission',
                        'email' => 'Email',
                        'office_preference' => 'Office Preference',
						'brand_name' => 'Brand',
                    ];
                    break;
                    default:
                        $columns = [
                            'ID' => 'ID',
                            'name' => 'Name',
                            'date' => 'Date of Submission',
                            'email' => 'Email',
                            'phone' => 'Phone',
                            'office_preference' => 'Office Preference',
                            'brand_name' => 'Brand',
                        ];
                }
				$columns['comment'] = 'Comment';
				return $columns;
			}
			function get_sortable_columns() {
				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());
				return array(
					'ID'  => array('ID', false),
					// 'name'  => array('name', false),
					'date'  => array('date', false),
					//'email' => array('email', false),
				);
			}
			function no_items() {
				echo 'There are currently no form submissions.';
			}
			function get_views(){
				global $wpdb;
				$ret = array();
				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());
				foreach(get_all_views() as $form_id) $ret[$form_id] = '<a href="admin.php?page=form-submissions&form-name='.$form_id.'" '.($form_id == $current ? 'class="current"' : '').'>'.get_form_name($form_id).' <span class="count">('.$this->get_total_for_form($form_id, true).')</span></a>';
				return $ret;
			}
			function column_name($item) {
				$url = 'admin.php?page=form-submissions&submission='.$item['ID'].'&form='.$item['current'];
				$value = '<strong><a class="row-title" href="'.$url.'">'.$item['name'].'</a></strong> ('.$item['ip_address'].')';
				$actions = array(
					'view-details' => '<a href="'.$url.'">View Details</a>',
					'print-submission' => '<a target="_blank" href="'.$url.'&print">Print Submission</a>',
				);
				return sprintf('%1$s %2$s', $value, $this->row_actions($actions));
			}
			function column_default($item, $column) {
				return $item[$column];
			}
			function is_viewing_all_forms() {
				return empty($_REQUEST['form-name']) || $_REQUEST['form-name'] == get_initial_form();
			}
			function get_current_form_name() {
				return get_form_name(isset($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());
			}
			function get_limit_query() {
				$current_page =  $this->get_pagenum(); if(empty($current_page)) $current_page = 1;
				$items_per_page = $this->_items_per_page;
				$limit = ' LIMIT '.(($current_page - 1)*$items_per_page).','.$items_per_page;
				return $limit;
			}
			function get_where_query() {
				$str = '';

				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());

				if(!empty($_REQUEST['s'])) {
					$search = array_filter(explode(' ', $_REQUEST['s']));
					foreach($search as $k => $v) $search[$k] = ' metadata LIKE "'.esc_sql('%'.strtolower(trim($v)).'%').'" ';
					$query = implode(' OR ', $search);
					if(!empty($str)) $str = $str.' AND '.$query;
					else $str = $query;
				}

				if(empty($str)) $str = ' 1 ';
				return $str;
			}
			function get_orderby_query() {
				$orderby = empty($_REQUEST['orderby']) ? '' : $_REQUEST['orderby'];
				$order = empty($_REQUEST['order']) ? '' : $_REQUEST['order'];
				$default = ' ORDER BY timestamp desc ';

				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());

				if(empty($orderby) || (strcasecmp($order, 'asc') && strcasecmp($order, 'desc'))) return $default;
				elseif($orderby == 'date') return ' ORDER BY timestamp '.$order.' ';
				elseif($orderby == 'ID') {
					return ' ORDER BY ID '.$order.' ';
				}
				elseif($orderby == 'name') {
					return ' ORDER BY full_name '.$order.' ';
				}
				elseif($orderby == 'email') {
					return ' ORDER BY email_address '.$order.' ';
				}
				elseif($orderby == 'order') {
					return ' ORDER BY order '.$order.' ';
				}
				else return $default;
			}
			function extra_tablenav($a) {
				if(!count($this->items)) return;
				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());
				$url = admin_url('admin.php?page=form-submissions&form-name='.$current.'&export');
				echo '<style>.alignleft.actions.bulkactions{display:none;}</style><div class="alignleft actions"><a class="button button-primary" style="margin-bottom:1em;" href="'.$url.'">Export '.$this->get_current_form_name().' Submissions</a></div>';
			}
			function get_total_for_form($form = null, $formatted = true, $today = false) {
				global $wpdb;
				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());
				if(empty($form)) {
					$form = $current;
					$where = $this->get_where_query();
				}
				else $where = '1';

				$where_date = '1';
				if($today === true) {
					$date = new DateTime(current_time('Y-m-d H:i:s'), new DateTimeZone('America/Chicago'));
					$date->setTimezone(new DateTimeZone('America/Toronto'));
					$where_date = ' timestamp >= "'.$date->format('Y-m-d 00:00:00').'" ';
				}

				$form = $this->mapping[$form];
				$submissions = $wpdb->get_results("select count(*) as `count` from form_submissions where form='$form' AND ($where_date) AND ($where)");
				return $formatted ? number_format($submissions[0]->count) : $submissions[0]->count;
			}
			function get_items() {
				global $wpdb, $brands;
				$items = array();
				$limit = $this->get_limit_query();
				$where = $this->get_where_query();
				$orderby = $this->get_orderby_query();
				$current = (!empty($_REQUEST['form-name']) ? $_REQUEST['form-name'] : get_initial_form());
				if(!in_array($current, get_all_views())) return array();
				$form = $this->mapping[$current];
				$submissions = $wpdb->get_results("select * from form_submissions where form='$form' and ($where) $orderby $limit;");

				// Format data
				foreach($submissions as $data) {
					$metadata = json_decode($data->metadata);
					$phone = 'No Phone #'; if(!empty($metadata->phone_number)) $phone = $metadata->phone_number;
					$date = date("F j, Y, g:i a", strtotime($data->timestamp));
                    
					$office_id = $metadata->office_preference;
					$brand = array_filter( $brands->brands, function($brand) use ($office_id) {
						$brand_location_ids = wp_list_pluck(get_locations_for_brand($brand->ID), 'ID'); 
						return in_array( $office_id, $brand_location_ids );
					});

					$brand_name = array_values( $brand )[0]->post_title;
					$office_preference = get_the_title($metadata->office_preference);
					
					
					$notes = $wpdb->get_results($wpdb->prepare('select count(*) as "c" from form_submission_notes where formName=%s and formID=%d', $this->mapping[$current], $data->ID));
                    
                    $email = $metadata->email_address;
                    $referring_practice_name = $metadata->referring_practice_name;
                    
					switch ($form) {
                        case 'pediatric-community':
							$full_name = $metadata->contact_name;
                            $email = $metadata->contact_email_address;
							break;
						case 'community':
							$full_name = $metadata->contact_name;
                            $email = $metadata->email_address;
							break;
						case 'orthodontic-referral':
							$full_name = $metadata->patient_name;
							$phone = $metadata->patient_phone_number;
							break;
                        case 'pediatric-orthodontic-referral':
							$full_name = $metadata->patient_name;
							$phone = $metadata->patient_phone_number;
							$referring_practice_name = $metadata->referring_doctor_name;
							break;
						default:
							$full_name = $metadata->full_name;
							break;
					}
                    
                    //var_dump($metadata);die;
                    
					$items[] = array(
						'ID' => $data->ID,
						'name' => $full_name,
                        'referring_practice_name' => $referring_practice_name,
						'date' => $date,
						'email' => $email,
						'phone' => $phone,
						'office_preference' => $office_preference,
						'brand_name' => $brand_name,
						'comment' => $metadata->comment,
						//'notes' => $notes[0]->c,
						'ip_address' => $data->ip_address,
						'current' => $current,
					);
				}

				return $items;
			}
			function prepare_items() {
				$this->_items_per_page = $this->get_items_per_page('submissions_per_page', 15);
				$this->items = $this->get_items();
				$columns = $this->get_columns();
				$hidden = array();
				$sortable = $this->get_sortable_columns();
				$this->_column_headers = array($columns, $hidden, $sortable);
				$this->_item_total_count = $this->get_total_for_form(null, false);
				$this->set_pagination_args(array(
					'total_items' => $this->_item_total_count,
					'per_page'    => $this->_items_per_page
				));
			}
		}

		// This will be called from FormsClass.class.php so we can include this file from the admin_init hook (so exports will be able to be called before headers are sent)
		function displaySubmissions() {
			$table = new Form_Submissions_Table(); ?>
		<div class="wrap">
			<h2>Website Form Submissions</h2>
			<?php $table->prepare_items(); ?>
			<?php $table->views(); ?>
			<form method="get">
				<input type="hidden" name="page" value="form-submissions" />
				<?php if(!empty($_REQUEST['form-name'])): ?><input type="hidden" name="form-name" value="<?= empty($_REQUEST['form-name']) ? '' : $_REQUEST['form-name'] ?>" /><?php endif ?>
				<?php if(!empty($_REQUEST['orderby'])): ?><input type="hidden" name="orderby" value="<?= empty($_REQUEST['orderby']) ? '' : $_REQUEST['orderby'] ?>" /><?php endif ?>
				<?php if(!empty($_REQUEST['order'])): ?><input type="hidden" name="order" value="<?= empty($_REQUEST['order']) ? '' : $_REQUEST['order'] ?>" /><?php endif ?>
				<?php $table->search_box('Search', 'search-args'); ?>
			</form>
			<?php $table->display(); ?>
		</div><?php
		}
	}
