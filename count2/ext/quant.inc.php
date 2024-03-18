<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

class Quant
{
	public $NAME;
	public $ARGS;
	public $TIME;

	public function __construct($name, ... $args)
	{
		$this->TIME = timestamp();
		$this->ARGS = $args;

		if(is_string($name))
		{
			$this->NAME = $name;
		}
		else
		{
			$this->NAME = 'Quant';
		}

		echo "__construct({$this->NAME})\n";
	}

	public function __destruct()
	{
		echo "__destruct({$this->NAME})\n";
	}

	public function __toString()
	{
		return '(' . $this->NAME . ')';
	}

	public function getName()
	{
		return $this->NAME;
	}

	public function getArgs()
	{
		return $this->ARGS;
	}

	public function getTime()
	{
		return $this->TIME;
	}
}

require_once('math.inc.php');
require_once('environment.inc.php');
require_once('filesystem.inc.php');
require_once('numeric.inc.php');
require_once('security.inc.php');
require_once('timing.inc.php');

?>
