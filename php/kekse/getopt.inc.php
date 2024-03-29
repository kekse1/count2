<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

class GetOpt extends Quant extends Map
{
	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, null, null, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

?>
