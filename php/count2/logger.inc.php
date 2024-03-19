<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');
require_once('ext/terminal.inc.php');

class Logger extends \kekse\Quant
{
	public function __construct(... $args)
	{
		return parent::__construct('Logger', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function errorHandler(... $args)
	{
		return self::handler(... $args);
	}

	public static function exceptionHandler(... $args)
	{
		return self::handler(... $args);
	}

	public static function handler(... $args)
	{
		var_dump($args);
		//\kekse\Terminal::error(... $args);
		exit(255);
	}
}

set_error_handler('\kekse\count2\Logger::errorHandler');
set_exception_handler('\kekse\count2\Logger::exceptionHandler');

?>
