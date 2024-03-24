<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

//
define('KEKSE_COUNT2_JSON_CONFIG', __DIR__ . '/json/config.json');
define('KEKSE_COUNT2_JSON_PARAM', __DIR__ . '/json/param.json');

//
require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/controller.inc.php');

//
$controller = $GLOBALS['count2'] = new Controller();

//
var_dump($controller);
echo "\n\n\n";
var_dump((string)$controller);
echo "\n\n";

//

?>
