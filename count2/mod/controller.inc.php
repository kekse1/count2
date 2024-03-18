<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');
require_once('configuration.inc.php');//TODO/(here)
require_once('session.inc.php');

class Controller extends \kekse\Quant
{
	public $session = null;
	
	public function __construct(... $args)
	{
		$this->session = new Session($this);
		return parent::__construct('Controller', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
