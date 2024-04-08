<?php
namespace kekse;
require_once(__DIR__ . '/../kekse/main.inc.php');
var_dump(is_numeric("0\n1", 10));		// false
var_dump(is_numeric("0\n1", 99));		// true
var_dump(is_numeric("0\n1", 256));		// true
var_dump(is_numeric('  ---3.14 FF  ', 16));	// false
var_dump(is_numeric('  ---3.14FF ', 16));	// false
var_dump(is_numeric('---3.14fF', 16));		// true
var_dump(is_numeric(' +-1234 ab '));		// false
var_dump(is_numeric('+-1234g '));		// false
var_dump(is_numeric('-+1234.56'));		// true
var_dump(is_numeric('-+1234'));			// true
var_dump(is_numeric('-+11012', 2));		// false
var_dump(is_numeric('-+1101', 2));		// true
var_dump(is_numeric(' -++1101 ', 2));		// false
var_dump(is_numeric(' --++1234 '));		// false
?>
