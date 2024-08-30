<?php

http_response_code(500);
header("Content-Type: application/json");

$errorMsg = [
	'success' => false,
	'msg' => 'Something went wrong!'
];

if($_ENV['ENVIRONMENT'] == 'development'){
	$errorMsg = [
		'success' => false,
		'msg' => $heading,
		'details' => $message
	];
}



die(json_encode($errorMsg));