<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/filesystem.inc.php');
require_once('session.inc.php');

class Configuration extends \kekse\Quant
{
	public $session = null;

	public function __construct(... $args)
	{
		for($i = 0; $i < count($args); ++$i)
		{
			if($args[$i] instanceof Session)
			{
				$this->session = array_splice($args, $i--, 1)[0];
			}
		}

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
