#!/usr/bin/env php
<?php
namespace kekse;
require_once('ext/param.inc.php');
$p = new Parameter('?eins=zwei&drei&vier&drei&drei');
var_dump((string)$p);
?>
