<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
//require_once(__DIR__ . '/main.php');
require_once(__DIR__ . '/ansi.php');
require_once(__DIR__ . '/environment.php');

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

	public static function width()
	{
		$result = Environment::get('COLUMNS', true);

		if($result === null)
		{
			return 0;
			return (int)@exec('tput cols 2>/dev/null');
		}

		return $result;
	}

	public static function height()
	{
		$result = Environment::get('LINES', true);

		if($result === null)
		{
			return (int)@exec('tput lines 2>/dev/null');
		}

		return $result;
	}
}

//
?>
