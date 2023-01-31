(function($) {
	$.validator.addMethod('emailTLD', function(v, e) {
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return !!filter.test(v);
	}, '*');

	$.validator.addMethod('phoneUS', function(v, e) {
		v = v.replace(/\s+/g, "");
		return this.optional(e) || v.length > 9 &&
			v.match(/^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i);
	}, '*');

    $.validator.addMethod("dob", function (value, element) {
         var minDate = Date.parse("01/01/1910");
         var today=new Date();
         var DOB = Date.parse(value);
         if((DOB <= today && DOB >= minDate)) {
            return true;
         }
         else {
            return false;
         }
    });

	let validator = $('form#form_'+__va.name).validate({
		ignore: ':hidden:not([name="g-recaptcha-response"]):not(input[type="radio"]):not(input[type="checkbox"]):not([id="office_preference"])',
		errorPlacement: function(error, element) {
			$(element).closest('.row-item').find('.tooltip').html($(error).html());
		},
		submitHandler: function(form) {
			window.last_form_executed = form;
			grecaptcha.execute();
		},
		highlight: function(element, errorClass, validClass) {
			$(element).closest('.row-item').find('.tooltip').removeClass('hidden');
			$(element).closest('.row-item').addClass('error');
			$(element).addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).closest('.row-item').find('.tooltip').addClass('hidden');
            $(element).closest('.row-item').removeClass('error');
			$(element).removeClass('error');
		},
		invalidHandler: function(form, validator) {
			let target = $(validator.errorList[0].element).attr('type') == 'hidden' ? $(validator.errorList[0].element).closest('.row-item') : $(validator.errorList[0].element),
				adminbar = ($('#wpadminbar').outerHeight()||0),
				header = $(window).width() <= 782 ? ($('#mobile-navigation').outerHeight()||0) : ($('section.header > .content').outerHeight()||0),
				utility = ($('section.header-utility').outerHeight()||0),
				offset = Math.floor(target.offset().top - header - utility - adminbar - 20)

			$('html,body').animate({
				scrollTop: offset
			}, 300);
		},
		rules: __va.rules,
		messages: __va.messages
	});

	function getParameterByName( name ){
	  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	  var regexS = "[\\?&]"+name+"=([^&#]*)";
	  var regex = new RegExp( regexS );
	  var results = regex.exec( window.location.href );
	  if( results == null )
		return "";
	  else
		return decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	function getReferralSource() {
		if(getParameterByName('gclid').length > 0 || document.location.hostname.indexOf(getParameterByName('scid').length) > 0 || getParameterByName('utm_source').toLowerCase() == 'reachlocal' || getParameterByName('utm_medium').toLowerCase() == 'cpc') return 'Google (PPC)';
		else if(document.referrer.toLowerCase().indexOf('linkedin.com') >= 0 || getParameterByName('utm_source').toLowerCase() == 'linkedin') return 'LinkedIn';
		else if(getParameterByName('utm_source').toLowerCase() == 'facebook' || document.referrer.toLowerCase().indexOf('facebook.com') >= 0) return 'Facebook';
		return 'Web';
	}

    if(getParameterByName('utm_source').toLowerCase().length > 0) {
        $('input[name="utm_source"]').val(getParameterByName('utm_source').toLowerCase());
        $('input[name="utm_medium"]').val(getParameterByName('utm_medium').toLowerCase());
        $('input[name="utm_campaign"]').val(getParameterByName('utm_campaign').toLowerCase());
        $('input[name="utm_term"]').val(getParameterByName('utm_term').toLowerCase());
        $('input[name="utm_content"]').val(getParameterByName('utm_content').toLowerCase());
    }

})(jQuery);
