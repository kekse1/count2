<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
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
function parseJSON($value)
{
	return json_decode($value, true, KEKSE_LIMIT_JSON);
}

function renderJSON($string)
{
	return json_encode($string, 0, KEKSE_LIMIT_JSON);
}

function timestamp($diff = null)
{
	if(PHP_INT_SIZE < 8)
	{
		if(is_int($diff))
		{
			return (time() - $diff);
		}
		
		return time();
	}
	
	$result = explode(' ', microtime());
	$result = (intval($result[1] * 1E3) + intval(round($result[0] * 1E3)));
	
	if(is_int($diff))
	{
		return ($result - $diff);
	}
	
	return $result;
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
if(!Quant::isCLI() && KEKSE_INSTANT_HEADER) try
{
	@header('Content-Type: ' . KEKSE_CONTENT_TYPE);
}
catch(\Throwable $err)
{
}

//
?>
