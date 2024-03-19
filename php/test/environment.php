<?php
namespace kekse;
require_once(__DIR__ . '/kekse/environment.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$env = new \kekse\Environment(null);
echo ".file:\n";
foreach($env->file as $key => $value) echo "[$key] $value\n";
echo "\n\n.real:\n";
foreach($env->real as $key => $value) echo "[$key] $value\n";
?>
