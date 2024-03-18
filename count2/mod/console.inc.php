<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/terminal.inc.php');//TODO/
require_once('ext/getopt.inc.php');

class Console extends \kekse\Terminal
{
	public $session = null;

	public $ARGV;
	public $ARGC;

	public function __construct(... $args)
	{
		if(!parent::isTTY())
		{
			throw new \Error('Not allowed since PHP doesn\'t run in TTY mode!');
		}

		for($i = 0; $i < count($args); ++$i)
		{
			if($args[$i] instanceof Session)
			{
				$this->session = array_splice($args, $i--, 1)[0];
			}
		}

		if(is_int($argc) && isset($argv))
		{
			$this->ARGC = $argc;
			$this->ARGV = $argv;
		}
		else if(is_int($_SERVER['argc']) && isset($_SERVER['argv']))
		{
			$this->ARGC = $_SERVER['argc'];
			$this->ARGV = $_SERVER['argv'];
		}
		else if(is_int($GLOBALS['argc']) && isset($GLOBALS['argv']))
		{
			$this->ARGC = $GLOBALS['argc'];
			$this->ARGV = $GLOBALS['argv'];
		}
		else
		{
			throw new \Error('Invalid server state (argument vector/count not accessable)');
		}

		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
