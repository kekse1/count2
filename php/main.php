<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

//
require_once('mod/controller.inc.php');
$controller = $GLOBALS['count2'] = new Controller();

//
var_dump($controller);
echo "\n\n\n";
var_dump((string)$controller);
echo "\n\n";

//

?>
