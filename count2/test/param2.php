#!/usr/bin/env php
<?php
namespace kekse;
require_once('ext/param.inc.php');
$p = new Parameter('?eins zwei=drei vier&five&six&five&seven');
var_dump((string)$p);
var_dump($p->getSize());
?>
