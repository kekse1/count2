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

	public $fingerprint = null;

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
	
	private function checkSession($throw = true)
	{
		if($this->console === null)
		{
			$this->console->debug('Starting console session with new controller');
		}
		else if($this->parameter !== null)
		{
			$this->console->debug('Starting browser session with new controller');

			$fingerprint = $this->parameter->fingerprint;

			if($fingerprint)
			{
				$this->fingerprint = $fingerprint;
				$this->console->info('Session received fingerprint parameter (' . $fingerprint . ')');
			}
			else
			{
				$this->console->log('Session received NO fingerprint parameter');
			}
		}
		else if($throw)
		{
			throw new \Exception('Unable to determine session type');
		}
		else
		{
			$this->console->debug('Invalid session with new controller');
			return false;
		}

		return true;
	}
}

?>
