<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('timing.inc.php');

class Quant
{
	public $__name;
	public $__args;
	public $__time;

	public function __construct($name, ... $args)
	{
		$this->__time = timestamp();
		$this->__args = $args;

		if(is_string($name))
		{
			$this->__name = $name;
		}
		else
		{
			$this->__name = null;
		}

		echo "__construct({$this->__name})";
	}

	public function __destruct()
	{
		echo "__destruct({$this->__name})";
	}

	public function __toString()
	{
		$result;

		if(is_string($this->__name))
		{
			$result = '(' . $this->__name . ')';
		}
		else
		{
			$result = '(Element)';
		}

		return $result;
	}
}

?>
