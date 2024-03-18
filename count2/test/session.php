#!/usr/bin/env php
<?php
namespace kekse\count2;
require_once('mod/session.inc.php');
$sess = new Session('?eins zwei=drei vier&&&five&&six&&six&six&seven');
var_dump((string)$sess);
echo "\n\n\n";
var_dump($sess);
?>
