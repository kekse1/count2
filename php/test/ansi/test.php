<?php

namespace kekse;

if(php_sapi_name() !== 'cli')
{
	throw new \Exception('ANSI examples only work in CLI mode');
}

require_once(__DIR__ . '/../kekse/ansi.php');

echo (ANSI::bold('bold') . PHP_EOL);
echo (ANSI::color('colored string', [0,240,40], [90,110,130]) . PHP_EOL);
echo (ANSI::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200], [30, 50, 70], 'strike') . PHP_EOL);

?>
