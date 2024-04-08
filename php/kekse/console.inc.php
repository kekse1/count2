<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/terminal.inc.php');

class Console extends Terminal
{
	public $argv;
	public $argc;

	public function __construct($session, ... $args)
	{
		global $argv;
		global $argc;

		if(!parent::isTTY())
		{
			throw new \Exception('Not allowed since PHP doesn\'t run in TTY mode!');
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

		if($this->argv > 0)
		{
			require_once(__DIR__ . '/getopt.inc.php');
			$this->getOptions();
		}

		parent::__construct($session, $this, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	private function getOptions()
	{
		//TODO/ using 'getopt.inc.php'!
	}
}

?>
