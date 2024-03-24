<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

class Logger extends \kekse\Quant
{
	public function __construct($session = null, ... $args)
	{
		return parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function errorHandler(... $args)
	{
		return self::handler('error', ... $args);
	}

	public static function exceptionHandler(... $args)
	{
		return self::handler('exception', ... $args);
	}

	public static function handler($type, ... $args)
	{
		var_dump($args);
		//\kekse\Terminal::error(... $args);
		
		switch($type)
		{
			case 'error':
				exit(255);
			case 'exception':
				break;
		}
	}
}

set_error_handler('\kekse\count2\Logger::errorHandler');
set_exception_handler('\kekse\count2\Logger::exceptionHandler');

?>
