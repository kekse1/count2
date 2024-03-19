<?php
namespace kekse\count2;
require_once('kekse/quant.inc.php');
require_once('count2/session.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$env = new Session(new \kekse\Environment());
var_dump($env);
?>
