<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/counting.php');

class Mail extends Counting
{
	public function __construct($session, ... $args)
	{
		parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

?>
