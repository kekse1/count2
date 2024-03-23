<?php
namespace kekse;
require_once(__DIR__ . '/../kekse/math.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$a = '  12.34  ';
$b = ' 7    ';
$c = '  7 px ';
$d = '  pt  ';
$A = unit($a);
$B = unit($b);
$C = unit($c, null, 'pt');
$D = unit($d);
var_dump($A);echo "\n";
var_dump($B);echo "\n";
var_dump($C);echo "\n";
var_dump($D);echo "\n";
?>

