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

	public function __construct($controller, ... $args)
	{
		$this->controller = $controller;

		$this->environment = new \kekse\Environment($this);

		$this->configuration = new \kekse\Configuration($this);
		$this->configuration->importSchemeFromJSON(KEKSE_COUNT2_JSON_CONFIG);

		$this->makeSession();

		parent::__construct(... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
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
		if(parent::isTTY())
		{
			$this->loadConsoleModules();
			$this->console = new Console($this);
		}
		else
		{
			$this->loadBrowserModules();

			$this->parameter = new \kekse\Parameter($this);
			$this->parameter->importSchemeFromJSON(KEKSE_COUNT2_JSON_PARAM);
			$this->connection = new \kekse\Connection($this);

			$fingerprint = $this->parameter->getString('fingerprint');

			if($fingerprint)
			{
				$this->loadFingerprintModules();
				$this->fingerprint = new Fingerprint($this, $fingerprint);
			}
		}
	}

	private function counting()
	{
		//check ip, cookies, fingerprint(!?); ..
		//'counting.inc.php'.. etc.
		//w/ returning current/new value (todo: danach, ..
		//... zb radix, hide, .. w/ drawing vs text..
	}

	private function prepare($value)
	{
		//hide, radix, ... ?text=..., ..
		//return (string)
	}

	private function output($value)
	{
		$string = $this->prepare($value);

		if($this->parameter->getBoolean('draw'))
		{
			$this->draw($string);
		}
		else
		{
			$this->text($string);
		}

		return $string;
	}

	private function draw($data)
	{
		if(!$this->configuration->getBoolean('draw'))
		{
			throw new \Error('Drawing is not allowed');
		}
		else
		{
			require_once(__DIR__ . '/drawing.inc.php');
		}

		$this->drawing = new Drawing($this);

		//conf['drawing']? ..!
		//params['text'] ? config['text'] > 0 ? ..
		//params['zero'] ? ..
	}

	private function text($data)
	{
		$this->connection->setTypeHeader(
			$this->parameter->getString('type'));
		$this->connection->setLengthHeader(strlen($data));
		return $this->connection->write($data);
	}
}

?>
