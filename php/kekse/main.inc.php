<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

class Quant
{
	public $session = null;

	public $TIME;
	public $ARGS;

	public function __construct(... $args)
	{
		$this->TIME = timestamp();
		$this->ARGS = $args;

		echo '__construct(' . $this->className() . ')' . PHP_EOL;
	}

	public function __destruct()
	{
		echo '__destruct(' . $this->className() . ')' . PHP_EOL;
	}

	public function __toString()
	{
		return '(' . $this->classPath() . ';' . $this->runtime() . ')';
	}

	public function className()
	{
		$result = explode('\\',  get_class($this));
		return $result[count($result) - 1];
	}

	public function classPath()
	{
		return get_class($this);
	}

	public function runtime()
	{
		return timestamp($this->TIME);
	}
}

require_once(__DIR__ . '/math.inc.php');
require_once(__DIR__ . '/environment.inc.php');
require_once(__DIR__ . '/filesystem.inc.php');
require_once(__DIR__ . '/numeric.inc.php');
require_once(__DIR__ . '/security.inc.php');
require_once(__DIR__ . '/timing.inc.php');

?>
