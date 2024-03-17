<?php

//TODO/'mode' und 'isCLI' als get(), nicht als funktion.

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');

class CLI extends \kekse\Quant
{
	public function __construct(... $args)
	{
		if(!self::isCLI)
		{
			throw new \Error('Not allowed since PHP doesn\'t run in CLI mode!');
		}

		parent::__construct('CLI', ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public static mode()
	{
		return php_sapi_name();
	}

	public static isCLI()
	{
		return (self::mode === 'cli');
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
