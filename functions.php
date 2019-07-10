<?php

$includes = [
	'includes/utils.php',
	'includes/init.php',
	'includes/admin.php',
	'includes/helpers.php',
	'includes/menu.php',
	'includes/content.php'
];

if(function_exists('WC')){
	$includes[] = 'includes/woocommerce.php';
}

foreach ($includes as $file) {
	if (!$filepath = locate_template($file)) {
		trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
	}

	require_once $filepath;
}
unset($file, $filepath);

?>