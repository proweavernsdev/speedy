<?php

http_response_code(500);
header("Content-Type: application/json");

$errorMsg = [
	'success' => false,
	'msg' => $message
];

if($_ENV['ENVIRONMENT'] == 'development'){
	$errorMsg['details'] = [
		'filepath' => $filepath,
		'line' => $line,
		'stack_trace' => debug_backtrace()
	];
}

die(json_encode($errorMsg));
