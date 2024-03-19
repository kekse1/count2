<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/configuration.inc.php');//TODO/(here)
require_once(__DIR__ . '/session.inc.php');

class Controller extends \kekse\Quant
{
	public function __construct(... $args)
	{
		$this->session = new Session($this);
		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
