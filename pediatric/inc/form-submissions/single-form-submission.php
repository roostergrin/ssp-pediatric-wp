<?php

global $wpdb;

$GLOBALS['key_label_mapping'] = $key_label_mapping;

function filter_submission_array($in) {
	$map = $GLOBALS['key_label_mapping'];
	$ret = array();
	foreach($in as $k => $v) {
		if ($k == '__f') continue;
		if (empty($k)) continue;
		if (isset($map[$k])) $k = $map[$k];
		if (empty($k)) continue;
		if (empty($v)) $v = '<em>N/A</em>';
		$ret[$k] = $v;
	}
	return $ret;
}

$id = (int)$_GET['submission'];
$form = $_GET['form'];
$submissions = array();

if (!in_array($form, get_all_views())) {
	echo 'Error: Submission not found.';
} else {
	$mapping = array(
		'contact' => 'contact',
		'consultation' => 'consultation',
        'custom-mouthguard' => 'custom-mouthguard',
		'appointment' => 'appointment',
		'refer' => 'refer',
		'community' => 'community',
		'orthodontic-referral' => 'orthodontic-referral',
		'review' => 'review',
        'pediatric-contact' => 'pediatric-contact',
		'pediatric-appointment' => 'pediatric-appointment',
		'pediatric-community' => 'pediatric-community',
		'pediatric-orthodontic-referral' => 'pediatric-orthodontic-referral',
	);
	$form = $mapping[$form];

	$submissions = $wpdb->get_results($wpdb->prepare('select * from form_submissions where ID = %d', $id));
	if (count($submissions)) {
		// Construct general details
		$general = $submissions[0];
		$general_metadata = json_decode($general->metadata);
		$submission_details = filter_submission_array($general_metadata);
		unset($general->metadata, $general->form, $general->submit);
		$general->form_id = get_form_name($_GET['form']);
		$general->name = $general_metadata->first_name." ".$general_metadata->last_name;
		$general->email = '<a href="mailto:'.$general_metadata->email_address.'">'.$general_metadata->email_address.'</a>';
		$general->ip_address = '<a href="http://www.infobyip.com/ip-'.$general->ip_address.'.html" target="_blank">'.$general->ip_address.'</a>';
		$general->timestamp = date("F j, Y, g:i a", strtotime($general->timestamp));
		$general = (array)$general;
		$general_details = filter_submission_array($general);
?>
		<div class="wrap">
			<h2>Submission Details - <?php echo  $general_metadata->first_name ?> <?php echo  $general_metadata->last_name ?> (#<?php echo  $general['ID'] ?>) - <?php echo  get_form_name($_GET['form']) ?></h2>
			<div class="no-print" style="margin:0.5em 0 1.5em;">
				<button class="button button-primary" onclick="javascript:print();">Print Submission</button>
			</div>
			<div class="module_wrapper">
				<section>
					<h3>General Information</h3>
					<?php foreach($general_details as $label => $value): ?>
					<div>
						<span class="label"><?php echo  $label ?></span> <span class="value">
						<?php
							if ($label == 'Position') {
								global $careers;
								$career = $careers->career_by_id[$value];
								echo '<a href="'.$career->url.'">'.$career->position_title.', '.$career->location->name.'</a>';
							} else {
								echo  $value;
							}  ?>
						</span>
					</div>
					<?php endforeach ?>
				</section>
				<section>
					<h3>Submission Details</h3>
					<?php foreach($submission_details as $label => $value): ?>
						<div>
						<? if($label === 'Office Preference') : ?>
							<span class="label"><?php echo  $label ?></span> <span class="value"><?= get_location_name_from_id($value); ?></span>
						<? else: ?>
						<span class="label"><?php echo  $label ?></span> <span class="value"><?php echo  stripslashes($value) ?></span>
						<? endif; ?>
					</div>
					<?php endforeach ?>
				</section>
				<section>
					<div id="submission-notes">
						<h3>Notes</h3>
						<form id="current_note" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>" method="post">
							<input type="hidden" name="form_name" value="<?php echo esc_attr(get_form_name($_GET['form'])) ?>">
							<input type="hidden" name="form_id" value="<?php echo absint($id) ?>">
							<label for="note">Please enter a note for this submission:</label>
							<textarea name="note" id="note" maxlength="65535"></textarea>
							<div class="submit_wrapper">
								<input class="button button-primary" type="submit" value="Submit Note">
							</div>
						</form>
					</div>
					<?php
					$previous_notes = $wpdb->get_results($wpdb->prepare('select * from form_submission_notes where formName=%s and formID=%d order by timestamp desc', $form, $id));
					if (!empty($previous_notes)):
					?>
					<h3>Previous Notes:</h3>
					<div id="previous_notes">
						<?php foreach($previous_notes as $note): ?>
						<div class="note">
							<?php echo  stripslashes(apply_filters('the_content', htmlentities($note->note))) ?>
							<?php
							$user_data = get_userdata($note->userID);
							$username = '';
							if (!empty($user_data->display_name)) $username = $user_data->display_name;
							else $username = $user_data->user_login;
							?>
							<div class="attribution">Submitted by <?php echo  $username ?> on <?php echo  date('F j, Y, g:i A', strtotime($note->timestamp)) ?></div>
						</div>
						<?php endforeach ?>
					</div>
					<?php endif ?>
				</section>
			</div>
		</div>
		<?php if (isset($_GET['print'])): ?>
		<script>
		window.print();
		</script>
		<?php endif ?>
		<style>
		.module_wrapper { margin-top:1em; }
		.module_wrapper > section {
			background-color:#fff;
			padding:1em;
		}
		.module_wrapper > section h3 {
			padding-bottom: 0.25em;
			margin:0 0 1em;
			border-bottom: solid 2px #F1F1F1;
		}
		.module_wrapper > section div + div { margin-top:1em; }
		.module_wrapper > section span.label { font-weight:bold; }
		.module_wrapper > section span.label:after { content:': '; }
		.module_wrapper > section + section { margin-top:1.5em; }

		#current_note label, #current_note textarea {
			display:block;
			width:100%;
		}
		#current_note .submit_wrapper {
			text-align:right;
			margin-top:0.5rem;
		}
		#current_note textarea {
			resize:vertical;
			min-height:150px;
		}
		#current_note { margin-bottom:1rem; }
		#previous_notes { margin-top:1rem; }
		#current_note label { font-weight:bold; margin-bottom:0.25rem; }
		.note .attribution {
				font-weight:bold;
				font-style:italic;
				font-size:0.95em;
		}
		.note + .note {
			margin-top:1.5rem;
			padding-top:0.5rem;
			border-top:dotted 1px #ccc;
		}
		@media print {
			html {
				background:#fff;
				padding:0;
			}
			html.wp-toolbar { padding-top:0; }
			.wrap {
				margin:0;
			}
			body {
				color:#000;
				padding:20px;
			}
			body, html { height:auto; }
			.module_wrapper > section {
				padding:1em 0;
			}
			.module_wrapper > section h3 {
				border-bottom:solid 1px black;
			}
			#wpcontent, #wpfooter {
				margin-left:0;
				padding-left:0;
			}
			#wpbody-content {
				float:none!important;
				padding-bottom:0!important;
			}
			.no-print,
			#submission-notes,
			#adminmenumain,
			#wpadminbar,
			#screen-meta,
			#screen-meta-links,
			#wpfooter,
			#wp-auth-check-wrap {
				display:none!important;
			}
		}
		</style>
		<?php
	} else {
		echo 'Error: Submission not found.';
	}
}
