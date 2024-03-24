<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/configuration.inc.php');
require_once(__DIR__ . '/../kekse/environment.inc.php');
require_once(__DIR__ . '/../kekse/terminal.inc.php');

class Session extends \kekse\Session
{
	public $controller;

	public $environment;
	public $configuration;

	public $console = null;

	public $parameter = null;
	public $connection = null;

	public $fingerprint = null;

	public function __construct($controller, ... $args)
	{
		$this->controller = $controller;

		$this->environment = new \kekse\Environment($this);
		$this->configuration = \kekse\Configuration::fromJSON(KEKSE_COUNT2_JSON_CONFIG, $this);

		$this->makeSession();

		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	private function loadConsoleModules()
	{
		require_once(__DIR__ . '/console.inc.php');
	}

	private function loadBrowserModules()
	{
		require_once(__DIR__ . '/../kekse/parameter.inc.php');
		require_once(__DIR__ . '/../kekse/connection.inc.php');
	}

	private function loadFingerprintModules()
	{
		require_once(__DIR__ . '/fingerprint.inc.php');
	}

	private function makeSession()
	{
		if(\kekse\Terminal::isTTY())
		{
			$this->loadConsoleModules();
			$this->console = new Console($this);
		}
		else
		{
			$this->loadBrowserModules();

			$this->parameter = \kekse\Parameter::withJSON(KEKSE_COUNT2_JSON_PARAM, $this);
			$this->connection = new \kekse\Connection($this);

			$fingerprint = $this->parameter->getString('fingerprint');

			if($fingerprint)
			{
				$this->loadFingerprintModules();
				$this->fingerprint = new Fingerprint($this, $fingerprint);
			}
		}
	}
}

?>
