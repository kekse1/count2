<?php
namespace kekse;
require_once(__DIR__ . '/../kekse/text.inc.php');
header('Content-Type: text/plain;charset=UTF-8');
$a = '  12.34  ';
$b = ' 7    ';
$c = '  7 px ';
$d = '  pt  ';
$A = Text::unit($a);
$B = Text::unit($b);
$C = Text::unit($c, null, 'pt');
$D = Text::unit($d);
var_dump($A);echo "\n";
var_dump($B);echo "\n";
var_dump($C);echo "\n";
var_dump($D);echo "\n";
?>

