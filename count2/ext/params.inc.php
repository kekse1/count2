<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');

class Parameters extends Quant
{
	public $params;

	public function __construct($params, ... $args)
	{
		parent::__construct('Parameters', ... $args);

		if(is_string($params))
		{
			$this->params = self::parseString($params);
		}
		else if(is_array($params))
		{
			$this->params = [ ... $params ];
		}
		else
		{
			$this->params = null;
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public static function parseString($string)
	{
	}
}

?>
