<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

class Counter extends \kekse\FileSystem
{
	public $carrier;

	public function __construct($session, $carrier, ... $args)
	{
		$this->carrier = $carrier;
		return parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	/*public function increment()//($host)
	{
	}*/
}

?>
