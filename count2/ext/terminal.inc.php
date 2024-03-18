<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('ext/quant.inc.php');//TODO/
require_once('ext/ansi.inc.php');

class Terminal extends Quant
{
	public function __construct(... $args)
	{
		if(!self::isTTY())
		{
			throw new \Error('Not allowed since PHP doesn\'t run in TTY mode!');
		}

		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function isTTY()
	{
		return (php_sapi_name() === 'cli');
	}

	public static function log(... $args)
	{
	}

	public static function info(... $args)
	{
	}

	public static function warn(... $args)
	{
	}

	public static function error(... $args)
	{
	}
}

?>
