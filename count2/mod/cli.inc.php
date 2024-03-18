<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');//TODO/

class CLI extends \kekse\Quant
{
	public function __construct(... $args)
	{
		if(!self::isCLI())
		{
			throw new \Error('Not allowed since PHP doesn\'t run in CLI mode!');
		}

		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function getMode()
	{
		return php_sapi_name();
	}

	public static function isCLI()
	{
		return (self::getMode() === 'cli');
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
