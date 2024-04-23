<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/style.html.php');
header('Content-Type: text/html; charset=UTF-8');

echo (HTML::bold('bold') . '<br>' . PHP_EOL);
echo (HTML::fg('vordergrund: rgb(100, 255, 40)', 100, 255, 40) . '<br>' . PHP_EOL);
echo (HTML::color('colored string', [100,255,40], [80,100,120]) . '<br>' . PHP_EOL);
echo (HTML::style('dies ist ein test', 'underline', 'bold', 'strike', [0,200,100,0.8], 'rgba(30, 60, 90, 0.25)') . '<br>' . PHP_EOL);

?>
