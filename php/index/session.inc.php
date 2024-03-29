<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\index;

require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/../kekse/parameter.inc.php');

class Session extends \kekse\Session
{
	public $parameter;

	public function __construct(... $args)
	{
		if(parent::isTTY())
		{
			throw new \Error('This is not [yet] a TTY/CLI script');
		}

		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

