#!/usr/bin/env php
<?php
namespace kekse;
require_once('kekse/quant.inc.php');
var_dump(timestamp());
var_dump(timestamp(timestamp() - 1234));
?>
