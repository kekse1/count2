<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

class Cookies extends \kekse\Quant
{
	public function __construct($session, ... $args)
	{
		$this->session = $session;
		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
