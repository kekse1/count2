<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/filesystem.inc.php');

class Configuration extends Quant
{
	public $scheme = null;

	public function __construct($session = null, $scheme = null, ... $args)
	{
		$this->session = $session;
		$this->scheme = $scheme;
		return parent::__construct(... $args);
	}

	public static function fromJSON($path, $session = null, ... $args)
	{
		$scheme = FileSystem::readFile($path);

		if(!$scheme)
		{
			return null;
		}
		
		$scheme = json_decode($scheme, true, KEKSE_JSON_DEPTH);
		
		if(!is_array($scheme))
		{
			return null;
		}
		
		return new Configuration($session, $scheme, ... $args);
	}

	public function __destruct()
	{
		unset($this->session);
		return parent::__destruct();
	}

	public function check($config)
	{
		if(!is_array($this->scheme))
		{
			return null;
		}

		//TODO/
	}
}

?>
