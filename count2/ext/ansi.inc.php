<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('ext/quant.inc.php');//TODO/
require_once('ext/terminal.inc.php');

class ANSI extends Quant
{
	public function __construct(... $args)
	{
		if(!self::isTTY())
		{
			throw new \Error('Not allowed since PHP doesn\'t run in TTY mode!');
		}

		return parent::__construct('ANSI', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function reset()
	{
		return "\e[0m";
	}

	public static function fg($red, $green, $blue)
	{
	}

	public static function bg($red, $green, $blue)
	{
	}

	public static function bold()
	{
		return "\e[1m";
	}

	public static function underline()
	{
		return "\e[4m";
	}
}

?>
