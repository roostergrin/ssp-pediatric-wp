<?
    $location = is_location();
	$brand = is_brand();
	$form_url = 'http'.($_SERVER['HTTPS'] == 'on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<form 
  action="<?= brand_url($action ?? '#form_'.$form_name); ?>" 
  method="POST" 
  id="form_<?= $form_name; ?>" 
  class="form_<?= $form_name; ?><? if($form_name == 'step'): ?> contact-form-scroll<? endif; ?><? if($network_error): ?> network-error<? endif; ?><? if(sanitize_title($brand->palette) == 'smiles-in-motion'): ?> label-green<? endif; ?>" 
  autocomplete="on" 
  novalidate="novalidate" 
  enctype="multipart/form-data; charset=utf-8; boundary=<?= rand(2, 1000); ?>"
>
	<input type="hidden" name="__f" value="<?= $form_hash; ?>">
	<input type="hidden" name="form_url" value="<?= $form_url; ?>">
	<input type="hidden" name="gclid" value="<? if(!empty($_SESSION['gclid'])) echo $_SESSION['gclid']; ?>">
	<input type="hidden" name="utm_source" value="<? if(isset($_SESSION['UTMs']) && !empty($_SESSION['UTMs']->utm_source)) echo $_SESSION['UTMs']->utm_source; ?>">
	<input type="hidden" name="utm_medium" value="<? if(isset($_SESSION['UTMs']) && !empty($_SESSION['UTMs']->utm_medium)) echo $_SESSION['UTMs']->utm_medium; ?>">
	<input type="hidden" name="utm_campaign" value="<? if(isset($_SESSION['UTMs']) && !empty($_SESSION['UTMs']->utm_campaign)) echo $_SESSION['UTMs']->utm_campaign; ?>">
	<input type="hidden" name="utm_term" value="<? if(isset($_SESSION['UTMs']) && !empty($_SESSION['UTMs']->utm_term)) echo $_SESSION['UTMs']->utm_term; ?>">
	<input type="hidden" name="utm_content" value="<? if(isset($_SESSION['UTMs']) && !empty($_SESSION['UTMs']->utm_content)) echo $_SESSION['UTMs']->utm_content; ?>">
	<? if(isset($_GET['full_name']) == 1) { ?>
		<input type="hidden" name="is_step" value="2">
	<? } ?>
	<? foreach($hidden as $name => $value): ?>
		<input type="hidden" name="<?= $name; ?>" id="<?= $name; ?>" value="<?= isset($_REQUEST[$name]) ? $_REQUEST[$name] : $value; ?>">
	<? endforeach; ?>
	<? if((count($api_errors) || !empty($form_errors))): ?>
		<div class="form-errors pull orange">
			<p>Our apologies, it appears that something did not go as planned. Please check the information below and re-submit the form.</p>
			<? if(count(array_filter($api_errors))): ?>
				<ul><li><?= implode('</li><li>', $api_errors); ?></li></ul>
			<? endif; ?>
		</div>
	<? endif; ?>
	<? foreach($rows as $i => $row) : ?>
		<? if($form_name === 'orthodontic-referral' && $i == 0) {
			echo '<div class="referral-flex-form-container"><div class="form-left">';
			
		} elseif($form_name === 'orthodontic-referral' && $i == 6) {
			echo '</div><div class="form-right">';
		}
		?>
		<div class="row row-<?= $i; ?><?= (!empty($row[0] == 'panoramic_xray_file') || !empty($row[0] == 'event_flyer_file')) ? ' '.($row[0]) : ''; ?>">
		<?
			foreach($row as $name):
				$field = $instance->getField($name);
				$active = false;
				$classes = '';
		?>
			<div class="row-item row-<?= $name; ?><?= (!empty($field->class)) ? ' '.($field->class) : ''; ?>">
				<? if($name == 'cta'): ?>
					<input type="submit" class="cta orange large fill form-submit" value="<?= $cta; ?>" /></div>
				<? continue; endif; ?>
				<? if(($name == 'office_preference' || $name == 'office_preference_hidden') && is_location()): ?>
					<input type="hidden" name="office_preference" id="office_preference" value="<?= $location->ID; ?>" />
				<? elseif($field->type == 'textarea'): ?>
					<textarea maxlength="600"  data-min-height="25px" row="5" name="<?= $name; ?>" id="<?= $name; ?>"><? if(isset($_REQUEST[$name])) { echo $_REQUEST[$name]; $active = true; }?></textarea>
				<? elseif($field->type == 'html'): ?>
					<?= $field->label; ?>
				<? elseif($field->type == 'hidden' && property_exists($field, 'value')): ?>
					<input type="hidden" name="<?= esc_attr($name); ?>" value="<?= esc_attr($field->value); ?>">
				<? elseif($field->type == 'fancy-select'):
					$offices = get_locations_for_brand(is_brand()->ID);
					if(get_the_id() === 6704) {
						$offices = array_filter($offices, function($var) {
							return $var->state === 'MN';
						});
					} elseif(get_the_id() === 6706) {
						$offices = array_filter($offices, function($var) {
							return $var->state === 'WI';
						});
					} elseif(get_the_id() === 8659) {
						$offices = array_filter($offices, function($office) {
							return in_array($office->ID, [1247, 1249, 1251, 1253, 1257, 1265, 1269]);
						});
					} elseif(get_the_id() === 10783) {
						$offices = array_filter($offices, function($office) {
							return in_array($office->ID, [1259, 1271]);
						});
					}		
				?>
					<div class="fancy-select" id="fancy-select">
						<? if (!empty($offices)): ?>
							<div class="fancy-select-title">Your <? if($form_name == 'refer') echo "friend's"; ?> office preference*</div>
							<div class="options hidden">
								<ul class="select-options">
								<? foreach ($offices as $o): ?>
									<li id="<?= $o->ID ?>"><?= $o->post_title ?></li>
								<? endforeach ?>
								</ul>
							</div>
						<? endif ?>
						<input type="hidden" name="<?= esc_attr($name); ?>" id="<?= esc_attr($name); ?>" value="<?= esc_attr($field->value); ?>">
					</div>
				<? elseif($field->type == 'fancy-select-colors'):
					$found = false;
					if(empty($_REQUEST[$name]) && !empty($field->default_option)) {
						$_REQUEST[$name] = $field->default_option;
					}
					foreach($field->options as $k => $v) {
						if(isset($_REQUEST[$name]) && $k == $_REQUEST[$name]) {
							$found = true;
							$active = true;
							break;
						}
					}
				?>
					<div class="fancy-select-colors" id="fancy-select-colors">
						<div class="fancy-select-colors-title">Preferred mouthguard colors*</div>
						<div class="options-colors hidden">
							<ul class="select-options">
							<? foreach ($field->options as $k => $v): ?>
								<li id="<?= esc_attr($v); ?>"><?= $v ?></li>
							<? endforeach ?>
							</ul>
						</div>
						<input type="hidden" name="<?= esc_attr($name); ?>" id="<?= esc_attr($name); ?>" value="<?= esc_attr($field->value); ?>">
					</div>
				<? elseif($field->type == 'select'): ?>
				<?
					$found = false;
					if(empty($_REQUEST[$name]) && !empty($field->default_option)) {
						$_REQUEST[$name] = $field->default_option;
					}
					foreach($field->options as $k => $v) {
						if(isset($_REQUEST[$name]) && $k == $_REQUEST[$name]) {
							$found = true;
							$active = true;
							break;
						}
					}
				?>
					<div class="select-wrapper">
						<select name="<?= $name; ?>" id="<?= $name; ?>">
							<option value="" disabled="disabled"<? if(!$found) echo ' selected="selected"'; ?>>&nbsp;</option>
							<? foreach($field->options as $k => $v): ?>
								<option value="<?= esc_attr($v); ?>"<? if(isset($_REQUEST[$name]) && $v == $_REQUEST[$name]) echo ' selected="selected"';?>><?= $v ?></option>
							<? endforeach ?>
						</select>
					</div>
				<? elseif($field->type == 'select-optgroup'): ?>
				<?
					$found = false;
					foreach($field->options as $k => $v) {
						if(isset($_REQUEST[$name]) && $k == $_REQUEST[$name]) {
							$found = true;
							$active = true;
							break;
						}
					}
				?>
					<div class="select-wrapper">
						<select name="<?= $name; ?>">
							<option value="" disabled="disabled"<? if(!$found) echo ' selected="selected"'; ?>>&nbsp;</option>
							<? foreach($field->options as $label => $day): ?>
								<optgroup label="<?= $label; ?>">
								<? foreach($day as $i => $j): ?>
									<option value="<?= $j; ?>"<? if(isset($_REQUEST[$name]) && $i == $_REQUEST[$name]) echo ' selected="selected"';?>><?= $j ?></option>
								<? endforeach ?>
								</optgroup>
							<? endforeach ?>
						</select>
					</div>
				<? elseif($field->type == 'radio'): ?>
					<div class="radio-title title-<?= $name; ?>"><?=$field->label; ?><? if(in_array($name, $required)) echo ' *'; ?></div>
					<div class="radio-options<?= !empty($name) ? ' '.($name) : ''; ?>">
						<? foreach($field->options as $value => $label): ?>
							<span class="radio">
								<input type="radio" tabindex="2" name="<?= $name; ?>" id="<?= $name.'['.esc_attr($value).']'; ?>" value="<?= $label; ?>"<? if((isset($_REQUEST[$name]) && $label == $_REQUEST[$name]) || (!isset($_REQUEST[$name]) && property_exists($field, 'default') && $value == $field->default)) { echo ' checked'; $active = true; } ?><? if(in_array($name, $required)) echo ' required'; ?>>
								<i></i>
								<label for="<?= $name.'['.esc_attr($value).']'; ?>"><q><?= $label ?></q></label>
							</span>
							<?
								if($label == 'Other'){ ?>
									<input type="<?= $field->type; ?>" name="<?= $name; ?>" id="<?= $name; ?>" value="<? if(isset($_REQUEST[$name])) {echo $_REQUEST[$name]; $active = true;} ?>">
								<? } ?>
						<? endforeach; ?>
					</div>
				<? elseif($field->type == 'checkbox'): ?>
					<p class="checkbox">
						<input type="<?= $field->type; ?>" name="<?= $name; ?>" id="<?= $name; ?>" value="">
						<i></i>
						<label style="display: inline; padding-right: 0;" class="<?= $name; ?>" for="<?= $name; ?>"><?= $field->label; ?><? if(in_array($name, $required)) echo ' *'; ?></label>
					</p>
				<? elseif($field->type == 'checkbox-group'): ?>
					<div class="checkbox-title title-<?= $name; ?>"><?= $field->label; ?></div>
					<div class="checkbox-options<?= !empty($name) ? ' '.($name) : ''; ?>">
						<? foreach($field->options as $value => $label): ?>
							<span class="checkbox">
								<input type="checkbox" tabindex="2" name="<?= $name; ?>[]" id="<?= esc_attr(sanitize_title($label)); ?>" value="<?= $label; ?>"<? if((isset($_REQUEST[$name]) && $label == $_REQUEST[$name]) || (!isset($_REQUEST[$name]) && property_exists($field, 'default') && $value == $field->default)) { echo ' checked'; $active = true; } ?><? if(in_array($name, $required)) echo ' required'; ?>>
								<i></i>
								<label for="<?= esc_attr(sanitize_title($label)); ?>"><q><?= $label; ?></q></label>
							</span>
						<? endforeach; ?>
					</div>
				<?php elseif($field->type == 'file'): ?>
					<span class="upload">No file selected</span>
					<input type="<?= $field->type; ?>" name="<?= $name.(($field->multiple ?? false) ? '[]' : '') ?>" id="<?= $name; ?>" value="" accept="<?= implode(',', $field->accept ?? ['image/jpeg', 'image/png', 'image/gif']) ?>"<? if ($field->multiple ?? false) echo ' multiple="multiple"' ?>>
					<div class="file-name"></div>
				<? elseif($field->type == 'review_file'): ?>
					<input type="file" id="<?= $name; ?>" name="review_files">
				<? elseif($field->type == 'captcha'): ?>
					<? /*<div class="g-recaptcha" data-sitekey="<?= $field->key; ?>"></div> */?>
					<div class="g-recaptcha" data-sitekey="<?= $field->key; ?>" data-size="invisible"></div>
				<? elseif($field->type == 'msg'): ?>
					<p><?= $field->label; ?></p>
				<? elseif($field->type == 'date'): ?>
					<input maxlength="250" type="<?= $field->type; ?>" name="<?= $name; ?>" data-value="<? if(isset($_REQUEST[$name])) {echo $_REQUEST[$name]; $active = true; } ?>">
				<? else: ?>
					<input maxlength="250" type="<?= $field->type; ?>" name="<?= $name; ?>" id="<?= $name; ?>"  value="<? if(isset($_REQUEST[$name])) {echo $_REQUEST[$name]; $active = true; } ?>">
				<? endif; ?>
				<? if($field->type != 'fancy-select' && $field->type != 'fancy-select-colors' && $field->type != 'html' && $field->type != 'radio' && $field->type != 'radio' && $field->type != 'msg' && $field->type != 'checkbox-group' && $field->type != 'checkbox' && $field->type != 'captcha'): ?>
					<label for="<?= $name; ?>" class="<?= ($active) ? $name.' active' : $name; ?><? if($name == 'office_preference' && is_location()) echo ' hidden'; ?>"><?= $field->label; if(in_array($name, $required)) echo ' *'; ?></label>
				<? endif; ?>
				<div class="tooltip<? if(empty($form_errors[$name])) echo ' hidden'; ?>"><? if(!empty($form_errors[$name])) echo $field->error; ?></div>
				<? if(isset($field->show_opt_in) && !empty($field->show_opt_in)): ?>
					<div class="email-additional">
						<div class="tooptip tooptip-optin checkbox">
							<input type="checkbox" name="optin" id="optin"<? if(!isset($_GET['full_name']) || (isset($_GET['full_name']) && $_REQUEST['optin'] == 'on')) echo ' checked="checked"'; ?>>
							<i></i>
							<label for="optin" class="optin-label">Opt in for newsletter</label>
						</div>
					</div>
				<? endif; ?>
			</div>
		<?
				if($name == 'contact-method') echo '</div>';
			endforeach;
		?>
	</div>
	<? endforeach;  ?>
	<? if($form_name === 'orthodontic-referral') {
		echo '</div></div>';
	} ?>
	<div id="google-recaptcha-container_<?= $form_name; ?>"></div>
</form>
