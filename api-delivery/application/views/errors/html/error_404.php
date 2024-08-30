<?php

http_response_code(404);
header("Content-Type: application/json");

$errorMsg = [
	'success' => false,
	'msg' => 'Resource not found!'
];

if($_ENV['ENVIRONMENT'] == 'development'){
	$errorMsg = [
		'success' => false,
		'msg' => $heading,
		'details' => $message
	];
}



die(json_encode($errorMsg));