<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('kekse/environment.inc.php');
require_once('kekse/terminal.inc.php');

class Session extends \kekse\Quant
{
	public $controller;

	public $environment;
	public $configuration;

	public $console = null;

	public $parameter = null;
	public $connection = null;

	public function __construct($controller, ... $args)
	{
		$this->controller = $controller;

		$this->environment = new \kekse\Environment($this);
		$this->configuration = new Configuration($this);

		if(\kekse\Terminal::isTTY())
		{
			require_once('console.inc.php');
			$this->console = new Console($this);
		}
		else
		{
			require_once('kekse/connection.inc.php');
			require_once('kekse/parameter.inc.php');

			$this->connection = new \kekse\Connection($this);
			$this->parameter = new \kekse\Parameter($this);
		}

		return parent::__construct('Session', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
