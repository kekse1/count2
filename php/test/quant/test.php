<?php
namespace kekse;
require_once(__DIR__ . '/../kekse/main.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$q = new Quant();
var_dump($q->classPath());
?>
