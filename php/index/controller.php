<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\index;

require_once(__DIR__ . '/../kekse/main.php');
require_once(__DIR__ . '/../kekse/parameter.php');

class Controller extends \kekse\Quant
{
	public $parameter;

	public function __construct(... $args)
	{
		if(parent::isCLI())
		{
			throw new \Error('This is not a CLI script [yet]');
		}

		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

