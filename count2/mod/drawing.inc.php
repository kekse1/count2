<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');

class Drawing extends \kekse\Quant
{
	public function __construct(... $args)
	{
		parent::__construct('Drawing', ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

?>