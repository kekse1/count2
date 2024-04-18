<?php
require_once(__DIR__ . '/count2/main.php');

if(php_sapi_name() === 'cli')
{
	var_dump($GLOBALS[2]);
}
else
{
	header('Content-Type: text/plain; charset=UTF-8');
	var_dump($GLOBALS[2]);
	//var_dump($GLOBALS[2]->session->parameter->getValues());
	//echo PHP_EOL; var_dump($GLOBALS[2]->session->parameter->getValues()[0]);
}

//$GLOBALS[2]->text('hello world!');
?>
