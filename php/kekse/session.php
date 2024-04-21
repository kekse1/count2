<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/main.php');
//require_once(__DIR__ . '/error.php');
//require_once(__DIR__ . '/environment.php');
require_once(__DIR__ . '/configuration.php');

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

	public $INPUT = 0;
	public $OUTPUT = 0;
	public $ERROR = 0;

	public function bytes()
	{
		return [
			'in' => $this->INPUT,
			'out' => ($this->OUTPUT + $this->ERROR),
			'input' => $this->INPUT,
			'output' => $this->OUTPUT,
			'error' => $this->ERROR
		];
	}

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
			require_once(__DIR__ . '/console.php');
			$this->console = new Console($this);
		}
		else
		{
			require_once(__DIR__ . '/connection.php');
			require_once(__DIR__ . '/parameter.php');

			$this->connection = new Connection($this);
			$this->parameter = $this->connection->parameter;
		}
	}
}

$GLOBALS['ERROR'] = null;

?>
