<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/style.ansi.php');

echo (ANSI::bold('bold') . PHP_EOL);
echo (ANSI::color('colored string', [100,255,40], [80,100,120]) . PHP_EOL);
echo (ANSI::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200,0.8], [30, 60, 90, 0.25], 'strike') . PHP_EOL);

?>
