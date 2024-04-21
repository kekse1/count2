<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/map.php');

class GetOpt extends Map
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
