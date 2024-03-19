<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('kekse/filesystem.inc.php');

class Configuration extends \kekse\Quant
{
	public $session;

	public function __construct($session, ... $args)
	{
		$this->session = $session;
		return parent::__construct('Configuration', ... $args);
	}

	public function __destruct()
	{
		unset($this->session);
		return parent::__destruct();
	}
	
	public static function readJSON($path)
	{
		//
	}

	public static function writeJSON($path, $item)
	{
		//
	}
}

?>
