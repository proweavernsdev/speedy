<?php

http_response_code($exception->getCode());
header("Content-Type: application/json");

$errorMsg = [
	'success' => false,
	'msg' => $message
];

if($_ENV['ENVIRONMENT'] == 'development'){
	$errorMsg['details'] = [
		'filepath' => $exception->getFile(),
		'line' => $exception->getLine(),
		'stack_trace' => $exception->getTrace()
	];
}

die(json_encode($errorMsg));


