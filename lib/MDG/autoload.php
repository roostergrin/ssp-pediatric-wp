<?
spl_autoload_register(function($class_name) {
	if (strstr($class_name, 'MDG\\')) {
		$file = __DIR__.'/classes/'.str_replace('MDG\\', '', $class_name).'.php';
		if (file_exists($file)) { include_once $file; return; }
	}
});
