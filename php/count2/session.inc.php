<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/configuration.inc.php');
require_once(__DIR__ . '/../kekse/environment.inc.php');

class Session extends \kekse\Session
{
	public $controller;

	public $environment;
	public $configuration;

	public $console = null;

	public $parameter = null;
	public $connection = null;
	
	public $fingerprint = null;
	public $count = null;

	public function __construct($controller, ... $args)
	{
		$this->controller = $controller;

		$this->environment = new \kekse\Environment($this);

		$this->configuration = new \kekse\Configuration($this);
		$this->configuration->importSchemeFromJSON(KEKSE_COUNT2_JSON_CONFIG);

		parent::__construct(... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

?>
