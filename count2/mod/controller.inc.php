<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');
require_once('configuration.inc.php');
require_once('session.inc.php');

class Controller extends \kekse\Quant
{
	public $session = null;
	
	private $console = null;

	public function __construct(... $args)
	{
		for($i = 0; $i < count($args); ++$i)
		{
			if($args[$i] instanceof Session)
			{
				$this->session = array_splice($args, $i--, 1)[0];
			}
		}

		if(!$this->session)
		{
			$this->session = new Session(... $args);
		}

		if(\kekse\Terminal::isTTY())
		{
			require_once('ext/terminal.inc.php');
			$this->console = new Console($this);
		}

		return parent::__construct('Controller', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
