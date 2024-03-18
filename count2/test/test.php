#!/usr/bin/env php
<?php
namespace kekse;
require('mod/configuration.inc.php');
$cfg = new Configuration();
var_dump($cfg->file);
var_dump($cfg->real);
?>
