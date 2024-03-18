<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/terminal.inc.php');//TODO/
require_once('ext/getopt.inc.php');

class Console extends \kekse\Terminal
{
	public function __construct(... $args)
	{
		if(!parent::isTTY())
		{
			throw new \Error('Not allowed since PHP doesn\'t run in TTY mode!');
		}

		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
