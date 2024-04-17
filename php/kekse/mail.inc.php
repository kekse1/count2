<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/main.inc.php');

//
class Mail extends Quant
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
