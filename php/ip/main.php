<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
if(php_sapi_name() === 'cli')
{
	fprintf(STDERR, 'This script won\'t run in CLI mode, so please use your HTTPD!' . PHP_EOL);
	exit(1);
}
else
{
	require_once(__DIR__ . '/../kekse/constants.php');
	@header('Content-Type: ' . KEKSE_CONTENT_TEXT);
}

$ip = $_SERVER['REMOTE_ADDR'];
header('Content-Length: ' . (string)strlen($ip));
echo $ip;
?>
