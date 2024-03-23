<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

class Configuration extends \kekse\Quant
{
	public function __construct($session, ... $args)
	{
		$this->session = $session;
		$this->checkSession();
		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		unset($this->session);
		return parent::__destruct();
	}

	private function checkSession()
	{
		//if(...)
		//{
		//	require_once(__DIR__ . '/filesystem.inc.php');//ODER TODO @ session.inc.php..!???
		//}
		//else
		//{
		//
		//}
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
