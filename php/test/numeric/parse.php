<?php
namespace kekse;
require_once(__DIR__ . '/../kekse/main.inc.php');
var_dump(parse("---0\n1", 10));
var_dump(parse(" --- 0\n1", 99));
var_dump(parse(" ---0\n1", 256));
var_dump(parse('  ---3.14 FF  ', 16));
var_dump(parse('  ---3.14FF ', 16));
var_dump(parse('---3.14fF ', 16));
var_dump(parse(' +-1234 ab '));
var_dump(parse('+-1234g '));
var_dump(parse('-+1234.56'));
var_dump(parse('-+1234'));
var_dump(parse('-+11012', 2));
var_dump(parse('-+1101', 2));
var_dump(parse(' -++1101 ', 2));
var_dump(parse(' --+-+ 1234 '));
?>
