<?php
namespace kekse\count2;
require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/../count2/session.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$sess = new Session(null);
var_dump($sess);
?>
