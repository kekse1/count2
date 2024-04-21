<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

require_once(__DIR__ . '/../kekse/main.php');
require_once(__DIR__ . '/constants.php');
require_once(__DIR__ . '/session.php');

class Controller extends \kekse\Quant
{
	public function __construct(... $args)
	{
		parent::__construct(new Session($this), ... $args);
		$this->start();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	private function start()
	{
		if(parent::isCLI())
		{
			$this->cli();
		}
		else
		{
			$this->web();
		}
	}
	
	private function cli()
	{
		//
	}
	
	private function web()
	{
		//
	}
}

?>
