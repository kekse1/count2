<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/environment.inc.php');
require_once(__DIR__ . '/../kekse/terminal.inc.php');

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
			require_once(__DIR__ . '/console.inc.php');
			$this->console = new Console($this);
		}
		else
		{
			require_once(__DIR__ . '/../kekse/connection.inc.php');
			require_once(__DIR__ . '/../kekse/parameter.inc.php');

			$this->connection = new \kekse\Connection($this);
			$this->parameter = new \kekse\Parameter($this);
		}

		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
