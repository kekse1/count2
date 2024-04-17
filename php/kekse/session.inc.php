<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/main.inc.php');
//require_once(__DIR__ . '/error.inc.php');
//require_once(__DIR__ . '/environment.inc.php');
require_once(__DIR__ . '/configuration.inc.php');

//
class Session extends Quant
{
	public $controller = null;
	public $error = null;

	public $environment = null;
	public $configuration = null;

	public $console = null;

	public $connection = null;
	public $parameter = null;

	public function __construct($controller = null, ... $args)
	{
		parent::__construct($this, ... $args);
		$this->controller = $controller;
		$this->create();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	protected function create()
	{
		$this->environment = new Environment($this);
		$this->error = $GLOBALS['ERROR'] = new ERROR($this);
		$this->configuration = new Configuration($this);

		if(parent::isCLI())
		{
			require_once(__DIR__ . '/console.inc.php');
			$this->console = new Console($this);
		}
		else
		{
			require_once(__DIR__ . '/connection.inc.php');
			require_once(__DIR__ . '/parameter.inc.php');

			$this->connection = new Connection($this);
			$this->parameter = $this->connection->parameter;
		}
	}
}

$GLOBALS['ERROR'] = null;

?>
