<?php
require_once(__DIR__ . '/count2/main.php');

if(php_sapi_name() === 'cli')
{
	var_dump($GLOBALS[2]);
}
else
{
	var_dump($GLOBALS[2]->session->parameter->getValues());
}

//$GLOBALS[2]->text('hello world!');
?>
