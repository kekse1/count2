<?php

namespace kekse;

require_once(__DIR__ . '/../kekse/filesystem.php');

header('Content-Type: text/plain; charset=UTF-8');

$fs1 = new FileSystem(null, true);
$fs2 = new FileSystem(null, './tmp');
$fs3 = new FileSystem(null, '/tmp/');
$fs4 = new FileSystem(null, 'tmp/');
$fs5 = new FileSystem(null, 'tmp');

var_dump($fs1); echo PHP_EOL;
var_dump($fs2); echo PHP_EOL;
var_dump($fs3); echo PHP_EOL;
var_dump($fs4); echo PHP_EOL;

?>
