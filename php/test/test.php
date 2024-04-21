<?php
require_once(__DIR__ . '/count2/main.php');
$result = print_r($GLOBALS[2], true);
$GLOBALS[2]->text($result);
?>
