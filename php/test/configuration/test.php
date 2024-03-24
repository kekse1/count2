<?php
namespace kekse;
header('Content-Type: text/plain;charset=UTF-8');
require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/../kekse/configuration.inc.php');
$c = new Configuration();
$c->importValuesFromJSON(__DIR__ . '/../json/count2/config.json');
var_dump($c); echo "\n\n";
$r1 = $c->getInteger('path');
$r2 = $c->getString('path');
$r3 = $c->get('path');
$r4 = $c->set('path', '/tmp');
$r5 = $c->setNumber('path', 3.14);
$r6 = $c->get('path');
var_dump($r1); echo "\n";
var_dump($r2); echo "\n";
var_dump($r3); echo "\n";
var_dump($r4); echo "\n";
var_dump($r5); echo "\n";
var_dump($r6); echo "\n";
?>
