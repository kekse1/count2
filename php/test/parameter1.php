<?php
header('Content-Type: text/plain;charset=UTF-8');
require_once('kekse/parameter.inc.php');
$q = $_SERVER['QUERY_STRING'];
$q = \kekse\Parameter::parse($q);
var_dump($q);echo '\n';
$q = \kekse\Parameter::encode($q);
var_dump($q);echo '\n';
$q = \kekse\Parameter::render($q);
var_dump($q);echo '\n';
?>