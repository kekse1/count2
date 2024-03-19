<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');

class Panel extends \kekse\Quant
{
	public function __construct(... $args)
	{
		return parent::__construct('Panel', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>