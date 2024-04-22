<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/style.ansi.php');

echo (ANSI::bold('bold') . PHP_EOL);
echo (ANSI::color('colored string', [0,240,40], [90,110,130]) . PHP_EOL);
echo (ANSI::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200], [30, 50, 70], 'strike') . PHP_EOL);
echo (ANSI::fg('vordergrund: rgb(0, 240, 40)', 0, 240, 40) . PHP_EOL);

?>
