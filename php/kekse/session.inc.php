<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

class Session extends Quant
{
	public function __construct(... $args)
	{
		parent::__construct($this, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

?>
