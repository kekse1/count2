<?php
namespace kekse;
require('ext/environment.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$env = new \kekse\Environment();
echo ".file:\n";
foreach($env->file as $key => $value) echo "[$key] $value\n";
echo "\n\n.real:\n";
foreach($env->real as $key => $value) echo "[$key] $value\n";
?>
