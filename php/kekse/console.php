<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/terminal.php');

class Console extends Terminal
{
	public $argv;
	public $argc;

	public function __construct($session, ... $args)
	{
		global $argv;
		global $argc;

		if(!parent::isCLI())
		{
			throw new \Exception('Not allowed since PHP doesn\'t run in CLI mode!');
		}

		$this->session = $session;

		if(is_int($argc) && isset($argv))
		{
			$this->argc = $argc;
			$this->argv = [ ... $argv ];
		}
		else if(is_int($_SERVER['argc']) && isset($_SERVER['argv']))
		{
			$this->argc = $_SERVER['argc'];
			$this->argv = [ ... $_SERVER['argv'] ];
		}
		else if(is_int($GLOBALS['argc']) && isset($GLOBALS['argv']))
		{
			$this->argc = $GLOBALS['argc'];
			$this->argv = [ ... $GLOBALS['argv'] ];
		}
		else
		{
			$this->argc = 0;
			$this->argv = [];
		}

		parent::__construct($session, ... $args);
		$this->getOptions();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function getOptions()
	{
		if($this->argc === 0)
		{
			return null;
		}
		else
		{
			require_once(__DIR__ . '/getopt.php');
		}
	}
	
	public function prompt(... $args)
	{
		$result = sprintf($format, ... $args);
		$pad = str_pad('', strlen($result), ' ');

		$confirm = function() use(&$result, &$s)
		{
			$this->writeError($result);
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

	public function log(... $args)
	{
	}

	public function info(... $args)
	{
	}

	public function warn(... $args)
	{
	}

	public function error(... $args)
	{
	}

	public function debug(... $args)
	{
	}

	public function table()
	{
throw new \Exception('TODO(!!) (w/ {format,ansi,html}.php!!!);');
	}
}

?>
