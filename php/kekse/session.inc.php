<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/log.inc.php');
require_once(__DIR__ . '/environment.inc.php');
require_once(__DIR__ . '/configuration.inc.php');

class Session extends Quant
{
	public $controller = null;
	public $log = null;

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
		$this->log = new Log($this, $this->environment);
		$this->configuration = new Configuration($this);
	}
}

?>
