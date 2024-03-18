<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/filesystem.inc.php');

class Configuration extends \kekse\Quant
{
	public function __construct(... $args)
	{
		return parent::__construct('Configuration', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
	
	public static function readJSON($path)
	{
		//
	}

	public static function writeJSON($path, $item)
	{
		//
	}
}

?>
