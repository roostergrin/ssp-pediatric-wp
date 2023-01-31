(function($) {	
	$.fn.serialize_form = function() {
		var data = new FormData();
		$.each($(this).find('input[type="file"]'), function(i, tag) {
			$.each($(tag)[0].files, function(i, file) {
				data.append(tag.name, file);
			});
		});
		let params = $(this).serializeArray();
		$.each(params, function(i, val) {
			data.append(val.name, val.value);
		});
		return data;
	}

	function getNowDateTimeStap() {
		var nowDate = new Date();
		var nowHours = nowDate.getHours();
		var nowMinutes = nowDate.getMinutes();
		var nowMillis = nowDate.getMilliseconds().toString().slice(0, -1);
		nowMinutes = nowMinutes < 10 ? '0'+nowMinutes : nowMinutes;
		var nowStrTime = nowHours + ':' + nowMinutes + ':' + nowMillis;

		return nowDate.getFullYear() + '-' + ('0' + (nowDate.getMonth()+1)).slice(-2) + "-" + nowDate.getDate() + " " + nowStrTime;
	}

	function createSuccessFormSubmitDatalayerEvents(formData){
		if(!window.dataLayer) return;

		var eventModel = {};
		var formName = formData.get('form_name');
		var locationSelectionOnlyForms = ['orthodontic-referral', 'refer', 'contact']
		var formSelectedLocation = (formData.get('office_preference') ? $(window.last_form_executed).find('.row-office_preference .fancy-select-title').text() : '');

		if(formName == 'appointment') {
			eventModel['location_select'] = formSelectedLocation;
			eventModel['booked_day'] = formData.get('appt_day');
			eventModel['time_preference'] = formData.get('appt_time');			
		}		

		if(formName == 'community') {
			eventModel['event_date'] = formData.get('event_date');
			eventModel['location_select'] = formSelectedLocation;
		}

		if(locationSelectionOnlyForms.includes(formName)) {
			eventModel['location_select'] = formSelectedLocation;
		}
				

		// add submission timestamp
		eventModel['form_submit_timestamp'] = getNowDateTimeStap();

		window.dataLayer.push({
			event: 'form_submit_ok',
			eventModel: eventModel
		});
	}

	window.onGoogleReCaptchaSubmit = function(token) {
		$(window.last_form_executed).find('.g-recaptcha-response').val(token);
		$(window.last_form_executed).find('input[type="submit"]').val('Please wait ...').prop('disabled', true);
		let form = $(window.last_form_executed);

		let action = form.attr('action');
		let data = new FormData(window.last_form_executed);

		$(window.last_form_executed).find('.g-recaptcha-response').remove();
		if (!$('input[name="ajaxing"]').length) form.append($('<input type="hidden" name="ajaxing" value="1">'));
		$.ajax({
			type: 'POST',
			url: action,
			data: data,
			cache: false,
			processData: false,
			contentType: false
		}).done(function($response) {
			if($response == 'valid') {
				if (window.dataLayer !== undefined) {
					window.dataLayer.push({
						 event: 'formSubmit'
					});
				}

				// generate Google tracking data layer events 
				// based on what type of form successfully submitted
				createSuccessFormSubmitDatalayerEvents(data);

				var text = '';
				if(data.get('form_name') == 'review') {
					text = "We appreciate you taking the time to share your kind words and rockstar smile with us!";
				} else {
					text = "We\'ve received your message and will respond shortly.";
				}

				$('.form-wrapper').empty().append(
					$('<h2>').text('Thank You!'),
					$('<p>').text(text)
				);

				/**
				 * When form is submitted and the Thank You message displays,
				 * the form is removed, which makes one of the floating bubble
				 * images go all the way down to the footer only on the 
				 * /kids-dentist-appointment/ page
				 */
				if($('body').hasClass('page-template-kids-dentist-appointment')){
					if(innerWidth > 1193) {
						$('.form-wrapper').parent('aside').css({paddingBottom: 625});
					}
				}

				$('html,body').animate({scrollTop: $('.form-wrapper').offset().top - 200});
			} else {
				if (window.dataLayer !== undefined) {
					window.dataLayer.push({
						event: 'form_submit_fail'
					});
				}
			}
		});
	}

	$('#event_date, #event_deadline').datetimepicker({
		minDate: new Date(),
		format: 'm/d/Y',
		formatDate: 'm/d/Y',
		formatTime: 'g:ia',
		timepicker: false
	});

	// Fancy Select Code...
	{
		// If single-location brand, preselect that location
		$('#fancy-select').ready(function() {
			var list = $('#fancy-select ul li');
			if(list.length == 1) {
				$('ul.select-options').css('column-count', '1')
				$('#office_preference').val(list.attr('id'));
				$('.fancy-select-title').text(list.text());
			}
		})

		$('.fancy-select .fancy-select-title').on('click', function(e) {
			$('.options').toggleClass('hidden');
			$(this).toggleClass('active');
		});

		$('.fancy-select ul.select-options > li').on('click', function(e) {
			$(this).closest('.fancy-select').find('.fancy-select-title').toggleClass('active');
		});

		$('#fancy-select ul li').on('click', function(e) {
			e.preventDefault();
			let text = $(this).html();
			let id = $(this).attr('id');
			$('.fancy-select-title').html(text);
			$('.options').addClass('hidden');
			$('#office_preference').val(id);
		});

		// Fancy Select Colors
		$('.fancy-select-colors .fancy-select-colors-title').on('click', function(e) {
			$('.options-colors').toggleClass('hidden');
			$(this).toggleClass('active');
		});

		$('.fancy-select-colors ul.select-options > li').on('click', function(e) {
			$(this).closest('.fancy-select-colors').find('.fancy-select-colors-title').toggleClass('active');
		});

		$('#fancy-select-colors ul li').on('click', function(e) {
			e.preventDefault();
			let text = $(this).html();
			let id = $(this).attr('id');
			$('.fancy-select-colors-title').html(text);
			$('.options-colors').addClass('hidden');
			$('#mouthguard_colors').val(id);
		});

		if ($('body').hasClass('page-template-share-feedback')) {
			$('#fancy-select ul li').on('click', function(e) {
				e.preventDefault();
				let text = $(this).html();
				let id = $(this).attr('id');
				let facebook_link = $('#fancy-select-2 ul li[id='+id+']').data('facebook_link');
				let google_link = $('#fancy-select-2 ul li[id='+id+']').data('google_link');
				$('.fancy-select-title-2').html(text);
				$('.options-2').addClass('hidden');
				$('.share-review a.share-link.facebook').attr('href', facebook_link).removeAttr("disabled");
				$('.share-review a.share-link.google').attr('href', google_link).removeAttr("disabled");
				if (!$('html').hasClass('review-office-preference-selected')) {
					$('html').addClass('review-office-preference-selected');
				}
			});

			$('.fancy-select .fancy-select-title-2').on('click', function(e) {
				$('.options-2').toggleClass('hidden');
				$(this).toggleClass('active');
			});

			$('.fancy-select ul.select-options-2 > li').on('click', function(e) {
				$(this).closest('.fancy-select').find('.fancy-select-title-2').toggleClass('active');
			});

			$('#fancy-select-2 ul li').on('click', function(e) {
				e.preventDefault();
				let text = $(this).html();
				let id = $(this).attr('id');
				let facebook_link = $(this).data('facebook_link');
				let google_link = $(this).data('google_link');
				$('.fancy-select-title').html(text);
				$('.fancy-select-title-2').html(text);
				$('.options').addClass('hidden');
				$('.options-2').addClass('hidden');
				$('#office_preference').val(id);
				$('.share-review a.share-link.facebook').attr('href', facebook_link).removeAttr("disabled");
				$('.share-review a.share-link.google').attr('href', google_link).removeAttr("disabled");
				if (!$('html').hasClass('review-office-preference-selected')) {
					$('html').addClass('review-office-preference-selected');
				}
			});
		}

		$('#office_preference').on('change', function() {
			if($(this).val() != '') {
				$('.row-virtual_consultation_link').removeClass('hidden');
			} else {
				$('.row-virtual_consultation_link').addClass('hidden');
			}
		});
	}

	// FileUploader plugin
	if ($('body').hasClass('page-template-share-feedback')) {
		// enable fileuploader plugin
		$('input[name="review_files"]').fileuploader({
			changeInput: ' ',
			theme: 'boxafter',
			enableApi: true,
			thumbnails: {
				box: '<div class="fileuploader-items">' +
						  '<ul class="fileuploader-items-list"></ul>' +
					  '</div>' +
					  '<div class="fileuploader-input">' +
						  '<div class="fileuploader-input-inner">' +
							  '<h3>${captions.feedback} ${captions.or} <a>${captions.button}</a></h3>' +
						  '</div>' +
						  '<button type="button" class="fileuploader-input-button"><i class="icon-plus"></i></button>' +
					  '</div>',
				item: '<li class="fileuploader-item">' +
						   '<div class="columns">' +
							   '<div class="column-thumbnail">${image}<span class="fileuploader-action-popup"></span></div>' +
							   '<div class="column-title">' +
								   '<div title="${name}">${name}</div>' +
								   '<span>${size2}</span>' +
							   '</div>' +
							   '<div class="column-actions">' +
								   '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="icon-remove"></i></button>' +
							   '</div>' +
							   '${progressBar}' +
						   '</div>' +
					   '</li>',
			},
			afterRender: function(listEl, parentEl, newInputEl, inputEl) {
				var plusInput = parentEl.find('.fileuploader-input'),
					api = $.fileuploader.getInstance(inputEl.get(0));

				plusInput.on('click', function() {
					api.open();
				});

				api.getOptions().dragDrop.container = plusInput;
			},
			upload: {
				url: ajaxurl,
				data: {
					action: 'upload_review_files'
				},
				type: 'POST',
				enctype: 'multipart/form-data',
				start: true,
				synchron: true,
				beforeSend: null,
				onSuccess: function(result, item) {
					console.log('onSuccess returned');
					console.log('result', result);
					console.log('item', item);
					var data = {};

					if (result && result.files)
						data = result;
					else
						data.hasWarnings = true;

					// if success
					if (data.isSuccess && data.files[0]) {
						item.name = data.files[0].name;
						item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
					}

					// if warnings
					if (data.hasWarnings) {
						for (var warning in data.warnings) {
							alert(data.warnings[warning]);
						}

						item.html.removeClass('upload-successful').addClass('upload-failed');
						return this.onError ? this.onError(item) : null;
					}

					item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');

					item.html.find('.column-title span').html(item.size2);
					setTimeout(function() {
						item.progressBar.fadeOut(400);
					}, 400);
				},
				onError: function(item) {
					console.log('onError returned');
					console.log('item', item);
					if (item.progressBar) {
						item.html.find('.column-title span').html(item.size2);
						item.progressBar.hide().find('.bar').width(0);
					}

					item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
						'<button type="button" class="fileuploader-action fileuploader-action-retry" title="Retry"><i class="fileuploader-icon-retry"></i></button>'
					) : null;
				},
				onProgress: function(data, item) {
					item.html.find('.column-title span').html(data.percentage == 99 ? 'Uploading...' : data.loadedInFormat + ' / ' + data.totalInFormat);
					item.progressBar.show().find('.bar').width(data.percentage + "%");
				},
				onComplete: null,
			},
			onRemove: function(item) {
				$.post(ajaxurl, {
					action: 'remove_review_files',
					file: item.name
				});
			},
			captions: $.extend(true, {}, $.fn.fileuploader.languages['en'], {
				feedback: 'Drag photos, video, or both here',
				or: 'or',
				button: 'Browse',
			}),
		});
	}

	window.onGoogleReCaptchaLoad = function() {
		if($('form#form_contact').length) {
			form_contact_recaptcha_widget = grecaptcha.render('google-recaptcha-container_contact', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_consultation').length) {
			form_consultation_recaptcha_widget = grecaptcha.render('google-recaptcha-container_consultation', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_consultation-no-virtual-consultation').length) {
			form_consultation_no_virtual_consultation_recaptcha_widget = grecaptcha.render('google-recaptcha-container_consultation-no-virtual-consultation', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_appointment').length) {
			form_appointment_recaptcha_widget = grecaptcha.render('google-recaptcha-container_appointment', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_refer').length) {
			form_refer_recaptcha_widget = grecaptcha.render('google-recaptcha-container_refer', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_community').length) {
			form_community_recaptcha_widget = grecaptcha.render('google-recaptcha-container_community', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_orthodontic-referral').length) {
			form_doctorreferral_recaptcha_widget = grecaptcha.render('google-recaptcha-container_orthodontic-referral', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_review').length) {
			form_review_recaptcha_widget = grecaptcha.render('google-recaptcha-container_review', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
		if($('form#form_custom-mouthguard').length) {
			form_review_recaptcha_widget = grecaptcha.render('google-recaptcha-container_custom-mouthguard', {
				sitekey: google_recaptcha_configuration.site_key,
				callback: onGoogleReCaptchaSubmit,
				size: 'invisible',
				badge: 'inline'
			});
		}
	}

	{
		// FORM LABEL ACTIVE STATE
		var fields = $("form .row-item select, form .row-item input, form .row-item textarea");

		fields.each(function() {
			if(($(this).val() || '').length >
				0) {
				$(this).addClass('fill');
				var th = $(this), el = th.prop('tagName').toLowerCase() == 'select' ? th.parent() : th;
				el.next('label').addClass('active');
			}
		});

		fields.on('focus', function() {
			$(this).addClass('fill');
			var th = $(this), el = th.prop('tagName').toLowerCase() == 'select' ? th.parent() : th;
			el.next('label').addClass('active');
		});

		// Removed this because the Textarea label was moving on mouseover.
		// fields.on('mouseover', function() {
		// 	$(this).addClass('fill');
		// 	var th = $(this), el = th.prop('tagName').toLowerCase() == 'select' ? th.parent() : th;
		// 	el.next('label').addClass('active');
		// });

		fields.on('change input keydown paste', function() {
			if($(this).is(':focus') && $(this).val() && $(this).val().length > 0 && (!$(this).is('[type="radio"]') || $(this).prop('checked'))) {
				$(this).parent().siblings().removeClass('active');
				$(this).parent().addClass('active');
				$(this).addClass('fill');
				var th = $(this), el = th.prop('tagName').toLowerCase() == 'select' ? th.parent() : th;
				el.next('label').addClass('active');
			}
		});

		fields.on('blur', function() {
			if(($(this).val() || '').length == 0 && (!$(this).is('[type="radio"]') || !$(this).prop('checked'))) {
				$(this).removeClass('fill');
				var th = $(this), el = th.prop('tagName').toLowerCase() == 'select' ? th.parent() : th;
				el.next('label').removeClass('active');
			}
		});

		$('form .row-item > div span.radio > i').on('click', function(e) {
			$(this).siblings('label').trigger('click');
			$(this).siblings('input[type=radio]').prop('checked', true);
			$(this).closest('.row-item').removeClass('error').find('.error').removeClass('error');
			$(this).closest('.row-item').find('div.tooltip').remove();
		});

		$('form .row-item > div span.radio > label').on('click', function(e) {
			$(this).parent().parent().find('label').removeClass('active');
			$(this).addClass('active');
			$(this).siblings('label').trigger('click');
			$(this).siblings('input[type=radio]').prop('checked', true);
			$(this).closest('.row-item').removeClass('error').find('.error').removeClass('error');
			$(this).closest('.row-item').find('div.tooltip').remove();
		});

		$('form .row-item .checkbox > i').on('click', function(e) {
			$(this).siblings('label').trigger('click');
			if($(this).siblings('input[type=checkbox]').is(":checked")) {
				$(this).siblings('input[type=checkbox]').prop('checked', true);
			} else {
				$(this).siblings('input[type=checkbox]').prop('checked', false);
			}

			$(this).closest('.row-item').removeClass('error').find('.error').removeClass('error');
			$(this).closest('.row-item').find('div.tooltip').remove();
		});

		$('form .row-item input[type="tel"]').each(function() {
			$(this).mask("000-000-0000", {placeholder:''});
		});
		$('form .row-item.date input[type="text"]').each(function() {
			$(this).mask("00/00/0000", {placeholder:''});
		});

		$('form .row-item input[name="patient_dob"]').each(function() {
			$(this).mask("00/00/0000", {placeholder:''});
		});

		$('form .tooltip a').on('click', function(e) {
			e.preventDefault();
			$(this).closest('.tooltip').slideUp();
			$(this).closest('.row-item').find('input, textarea, select').first().val($(this).html()).trigger('focus').trigger('blur');
		});

		$('form .tooptip > i').on('click', function() {
			$(this).next().click();
		});

		$('input[type=file]').on('change', function() {
			let files = $(this).get(0).files;
				file_names = Object.keys(files).map(function(key, index) {
					return files[key].name;
				});

			$('.upload').addClass('hidden');
			$('.file-name').html(file_names.join(', '));
		});
	}
//
	if ($('body').hasClass('page-template-free-orthodontic-consultation')) {
		let clicked = false;
		function checkClicked() {
			if (clicked === false) {
				$('#form_consultation .row-2, #form_consultation-no-virtual-consultation .row-2').hide();
				$('#form_consultation .row-3, #form_consultation-no-virtual-consultation .row-3').hide();
				$('#form_consultation .row-4, #form_consultation-no-virtual-consultation .row-4').hide();
				$('#form_consultation .row-5, #form_consultation-no-virtual-consultation .row-5').hide();
			} else {
				$('#form_consultation .row-2, #form_consultation-no-virtual-consultation .row-2').slideDown();
				$('#form_consultation .row-3, #form_consultation-no-virtual-consultation .row-3').slideDown();
				$('#form_consultation .row-4, #form_consultation-no-virtual-consultation .row-4').slideDown();
				$('#form_consultation .row-5, #form_consultation-no-virtual-consultation .row-5').slideDown();
				$('#form_consultation  .form-submit, #form_consultation-no-virtual-consultation  .form-submit').val('Submit your request');
			}
		}
		function checkSize() {
			if (window.innerWidth < 782) {
				clicked = false;
				checkClicked();
			} else {
				clicked = true;
				checkClicked();
			}
		}
		checkSize();
		$('#form_consultation  .form-submit').on('click', function(e) {
			e.preventDefault();
			if (clicked === false) {
				clicked = true;
				checkClicked();
			} else {
				if ($('#form_consultation').submit()) {
					clicked = false;
				}
			}
		});
		checkClicked();
	}

	$('span.upload').on('click', function(e) {
		$(this).prev().trigger('click');
	});
})(jQuery);
