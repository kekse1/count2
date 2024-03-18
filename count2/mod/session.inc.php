<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('mod/configuration.inc.php');
require_once('ext/environment.inc.php');
require_once('ext/parameter.inc.php');
require_once('mod/connection.inc.php');

class Session extends \kekse\Quant
{
	public $configuration = null;
	public $parameter = null;
	public $connection = null;
	public $environment = null;

	public function __construct(... $args)//$configuration = null, $environment = null, $parameter = null, $connection = null, ... $args)
	{
		for($i = 0; $i < count($args); ++$i)
		{
			if($args[$i] instanceof Configuration)
			{
				$this->configuration = array_splice($args, $i--, 1)[0];
			}
			else if($args[$i] instanceof \kekse\Parameter)
			{
				$this->parameter = array_splice($args, $i--, 1)[0];
			}
			else if($args[$i] instanceof Connection)
			{
				$this->connection = array_splice($args, $i--, 1)[0];
			}
			else if($args[$i] instanceof \kekse\Environment)
			{
				$this->environment = array_splice($args, $i--, 1)[0];
			}
		}

		return parent::__construct('Session', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
