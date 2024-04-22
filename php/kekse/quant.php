<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/io.php');

//
class Quant extends IO
{
	public $session = null;

	public $time;
	public $args;

	public function __construct($session = null, ... $args)
	{
		$this->session = $session;
		$this->time = timestamp();
		$this->args = $args;
	}

	public function __destruct()
	{
		//
	}

	public function __toString()
	{
		return $this->classPath();// . ';' . $this->runtime();
	}

	public function className()
	{
		$result = explode('\\',  get_class($this));
		return $result[count($result) - 1];
	}

	public function classPath()
	{
		return get_class($this);
	}

	public function runtime()
	{
		return timestamp($this->time);
	}
}

//
require_once(__DIR__ . '/error.php');
require_once(__DIR__ . '/environment.php');
require_once(__DIR__ . '/session.php');
require_once(__DIR__ . '/math.php');
require_once(__DIR__ . '/numeric.php');
require_once(__DIR__ . '/text.php');
require_once(__DIR__ . '/security.php');

//
?>
