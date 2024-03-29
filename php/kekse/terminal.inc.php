<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/../kekse/main.inc.php');//TODO/
require_once(__DIR__ . '/../kekse/ansi.inc.php');

class Terminal extends Quant
{
	public $console;

	public function __construct($session = null, $console = null, ... $args)
	{
		if(!self::isTTY())
		{
			throw new \Exception('Not allowed since PHP doesn\'t run in CLI mode!');
		}

		$this->console = $console;
		return parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function isTTY()
	{
		return (php_sapi_name() === 'cli');
	}

	public static function log($format, ... $args)
	{
		$result = sprintf($format, ... $args);
		printf($result . PHP_EOL);
		return $result;
	}

	public static function info($format, ... $args)
	{
		$result = sprintf($format, ... $args);
		printf($result . PHP_EOL);
		return $result;
	}

	public static function warn($format, ... $args)
	{
		$result = sprintf($format, ... $args);
		fprintf(STDERR, $result . PHP_EOL);
		return $result;
	}

	public static function error($format, ... $args)
	{
		$result = sprintf($format, ... $args);
		fprintf(STDERR, $result . PHP_EOL);
		return $result;
	}

	public static function debug($format, ... $args)
	{
		$result = sprintf($format, ... $args);
		fprintf(STDERR, $result . PHP_EOL);
		return $result;
	}

	public static function prompt($format, ... $args)
	{
		$result = sprintf($format, ... $args);
		$pad = str_pad('', strlen($result), ' ');

		$confirm = function() use(&$result, &$s)
		{
			fprintf(STDERR, $result);
			$res = readline($s);

			if($res === '')
			{
				return null;
			}
			
			switch(strtolower($res[0]))
			{
				case 'y': case '1': case '+': return true;
				case 'n': case '0': case '-': return false;
			}

			return null;
		};

		$result = null;

		while($result === null)
		{
			$result = $confirm();
		}

		return $result;
	}
}

?>
