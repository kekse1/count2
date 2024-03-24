<?php
namespace kekse;
header('Content-Type: text/plain;charset=UTF-8');
require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/../kekse/parameter.inc.php');
$p = Parameter::withJSON(__DIR__ . '/../json/count2/param.json');
var_dump($p); echo "\n\n";
$r = $p->getString('fingerprint');
var_dump($r); echo "\n";
?>
