<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/filesystem.inc.php');

class Configuration extends \kekse\Quant
{
	public function __construct($session, ... $args)
	{
		$this->session = $session;
		return parent::__construct(... $args);
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
