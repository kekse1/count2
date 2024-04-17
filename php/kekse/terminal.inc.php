<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/main.inc.php');
require_once(__DIR__ . '/ansi.inc.php');

//
class Terminal extends Quant
{
	public function __construct($session = null, ... $args)
	{
		if(!self::isCLI())
		{
			throw new \Exception('Not allowed since PHP doesn\'t run in CLI mode!');
		}

		parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

//
?>
