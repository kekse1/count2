<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/style.html.php');
header('Content-Type: text/html; charset=UTF-8');

echo (HTML::bold('bold') . '<br>' . PHP_EOL);
echo (HTML::color('colored string', [0,240,40], [90,110,130,0.8]) . '<br>' . PHP_EOL);
echo (HTML::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200], [30, 60, 90, 0.25], 'strike') . '<br>' . PHP_EOL);
echo (HTML::fg('vordergrund: rgb(0, 240, 40)', 0, 240, 40) . PHP_EOL);

?>
