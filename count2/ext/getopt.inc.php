<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('./object.inc.php');

class GetOpt extends Quant
{
	public function __construct(... $args)
	{
		parent::__construct('GetOpt', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
