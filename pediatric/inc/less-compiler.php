<?php

add_action('template_redirect', function() {
	if(isset($_GET['compile'])) {
		compile_less();
	}
});