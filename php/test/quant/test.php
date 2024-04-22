<?php
namespace kekse;
require_once(__DIR__ . '/../kekse/quant.php');
header('Content-Type: text/plain;charset=UTF-8');
$q = new Quant();
var_dump($q->classPath());
?>
