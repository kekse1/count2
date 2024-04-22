<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/style.ansi.php');

echo (ANSI::bold('bold') . PHP_EOL);
<<<<<<< HEAD
echo (ANSI::color('colored string', [0,240,40], [90,110,130]) . PHP_EOL);
echo (ANSI::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200], [30, 50, 70], 'strike') . PHP_EOL);
echo (ANSI::fg('vordergrund: rgb(0, 240, 40)', 0, 240, 40) . PHP_EOL);
=======
echo (ANSI::color('colored string', [100,255,40], [80,100,120]) . PHP_EOL);
echo (ANSI::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200,0.8], [30, 60, 90, 0.25], 'strike') . PHP_EOL);
>>>>>>> b3a00008cea94f0a41d8b90873b39ddbad05a8b8

?>
