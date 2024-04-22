<?php
require_once(__DIR__ . '/count2/main.php');
$result = print_r($GLOBALS[2], true);
$GLOBALS[2]->text($result, null, true);
$GLOBALS[2]->text(PHP_EOL . PHP_EOL . print_r($GLOBALS[2]->bytes(), true) . PHP_EOL);
?>
