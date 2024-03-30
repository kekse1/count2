<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

require_once(__DIR__ . '/../kekse/main.inc.php');
require_once(__DIR__ . '/constants.inc.php');
require_once(__DIR__ . '/session.inc.php');

class Controller extends \kekse\Quant
{
	public function __construct(... $args)
	{
		parent::__construct(new Session($this), ... $args);
		$this->initialize();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	private function initialize()
	{
		if(parent::isTTY())
		{
			$this->tty();
		}
		else
		{
			$this->web();
		}
	}
	
	private function tty()
	{
		//
		require_once(__DIR__ . '/../kekse/console.inc.php');
		
		//
		$this->session->console = new \kekse\Console($this->session);
	}
	
	private function web()
	{
		//
		require_once(__DIR__ . '/../kekse/connection.inc.php');
		require_once(__DIR__ . '/../kekse/parameter.inc.php');
		
		//
		$this->session->connection = new \kekse\Connection($this->session);
		$this->session->parameter = new \kekse\Parameter($this->session, KEKSE_COUNT2_JSON_PARAM);
	}
}

?>
