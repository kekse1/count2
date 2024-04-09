<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

//
require_once(__DIR__ . '/controller.inc.php');
//try{
$controller = $GLOBALS['count2'] = new Controller();
//}catch(\Throwable $err) { var_dump($err); }
//
var_dump($controller);

//

?>
