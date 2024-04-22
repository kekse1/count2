<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/style.ansi.php');

echo (ANSI::bold('bold') . PHP_EOL);
echo (ANSI::fg('vordergrund: rgb(100, 255, 40)', 100, 255, 40) . PHP_EOL);
echo (ANSI::color('colored string', [100,255,40], [80,100,120]) . PHP_EOL);
echo (ANSI::style('dies ist ein test', 'underline', 'bold', 'strike', [0,200,100,0.8], 'rgba(30, 60, 90, 0.25)') . PHP_EOL);

?>
