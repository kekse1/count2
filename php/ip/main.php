<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
if(php_sapi_name() === 'cli')
{
	fprintf(STDERR, 'This script won\'t run in CLI mode.' . PHP_EOL);
	exit(1);
}
header('Content-Type: text/plain;charset=UTF-8');
$ip = $_SERVER['REMOTE_ADDR'];
header('Content-Length: ' . (string)strlen($ip));
echo $ip;
?>
