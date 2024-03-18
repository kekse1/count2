<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/parameter.inc.php');
require_once('mod/connection.inc.php');

class Session extends \kekse\Quant
{
	public $parameter;
	public $connection;

	public function __construct($parameter = null, $connection = null, ... $args)
	{
		if(is_string($parameter))
		{
			$this->parameter = new \kekse\Parameter($parameter);
		}
		else if(!$parameter)
		{
			$this->parameter = new \kekse\Parameter($_SERVER['QUERY_STRING']);
		}
		else
		{
			$this->parameter = $parameter;
		}

		if($connection)
		{
			$this->connection = $connection;
		}
		else
		{
			$this->connection = new Connection();
		}

		return parent::__construct('Session', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
