<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('configuration.inc.php');
require_once('ext/environment.inc.php');
require_once('ext/parameter.inc.php');
require_once('connection.inc.php');
require_once('ext/terminal.inc.php');
require_once('controller.inc.php');

class Session extends \kekse\Quant
{
	public $controller = null;

	public $configuration = null;
	public $environment = null;

	public $parameter = null;
	public $connection = null;

	public $console = null;

	public function __construct(... $args)//$configuration = null, $environment = null, $parameter = null, $connection = null, ... $args)
	{
		for($i = 0; $i < count($args); ++$i)
		{
			if($args[$i] instanceof Controller)
			{
				$this->controller = array_splice($args, $i--, 1)[0];
			}
			else if($args[$i] instanceof Configuration)
			{
				$this->configuration = array_splice($args, $i--, 1)[0];
			}
			else if(!\kekse\Terminal::isTTY() && $args[$i] instanceof \kekse\Parameter)
			{
				$this->parameter = array_splice($args, $i--, 1)[0];
			}
			else if(!\kekse\Terminal::isTTY() && $args[$i] instanceof Connection)
			{
				$this->connection = array_splice($args, $i--, 1)[0];
			}
			else if($args[$i] instanceof \kekse\Environment)
			{
				$this->environment = array_splice($args, $i--, 1)[0];
			}
		}

		if(!$this->controller)
		{
			throw new \Error('Missing Controller instance');
		}

		if(!\kekse\CLI::isCLI())
		{
			if(!$this->parameter && isset($_SERVER['QUERY_STRING']))
			{
				$this->parameter = new \kekse\Parameter($this, $_SERVER['QUERY_STRING']);
			}

			if(!$this->connection && !\kekse\CLI::isCLI())
			{
				$this->connection = new Connection($this);
			}
		}

		if(!$this->environment)
		{
			$this->environment = new \kekse\Environment($this);
		}

		if(!$this->configuration)
		{
			$this->configuration = new Configuration($this);
		}

		return parent::__construct('Session', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
