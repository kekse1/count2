<?php
namespace kekse;
require_once(__DIR__ . '/../kekse/text.php');
header('Content-Type: text/plain;charset=UTF-8');
$text = 'eins <b>zwei</b> drei';
$len = Text::strlen($text);
$txt = Text::less($text);
var_dump($len);echo PHP_EOL;
var_dump($txt);echo PHP_EOL;
?>

