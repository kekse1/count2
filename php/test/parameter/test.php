<?php
namespace kekse;
header('Content-Type: text/plain;charset=UTF-8');
require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/../kekse/parameter.inc.php');
$p = new Parameter();
$p->importSchemeFromJSON(__DIR__ . '/../json/count2/param.json');
var_dump($p); echo "\n\n";
$r = $p->getString('fingerprint');
var_dump($r); echo "\n\n\n";
var_dump($p->get('type')); echo "\n";
var_dump($p->getString('type')); echo "\n";
var_dump($p->getNumber('type')); echo "\n";
?>
