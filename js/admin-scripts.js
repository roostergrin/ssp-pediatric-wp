(function($) {
	function copyToClipboard(text) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(text).select();
		document.execCommand("copy");
		$temp.remove();
	}

	$('code').on('click', function() {
		copyToClipboard($(this).text());
	});
})(jQuery);
