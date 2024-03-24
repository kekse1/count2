<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

class Click extends Counter
{
	public function __construct($session, $carrier, ... $args)
	{
		return parent::__construct($session, $carrier, 'click', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
