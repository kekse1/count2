<?php
namespace kekse\count2;
require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/../count2/controller.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$ctrl = new Controller();
var_dump($ctrl);
?>
