<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/html.php');
header('Content-Type: text/html; charset=UTF-8');

echo (HTML::bold('bold') . '<br>' . PHP_EOL);
echo (HTML::color('colored string', [0,240,40], [90,110,130]) . '<br>' . PHP_EOL);
echo (HTML::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200], [30, 50, 70], 'strike') . '<br>' . PHP_EOL);

?>
