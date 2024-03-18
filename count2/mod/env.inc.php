<?php

//TODO/'mode' und 'isCLI' als get(), nicht als funktion.

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');//TODO/

class Environment extends \kekse\Quant
{
	public function __construct(... $args)
	{
		return parent::__construct('Environment', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function log(... $args)
	{
	}

	public static function info(... $args)
	{
	}

	public static function warn(... $args)
	{
	}

	public static function error(... $args)
	{
	}
}

?>
