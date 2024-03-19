<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/main.inc.php');

class Environment extends Quant
{
	public $file;
	public $real;
	
	public function __construct($session, ... $args)
	{
		$this->session = $session;

		$details = self::getScriptDetails();
		$this->file = $details[0];
		$this->real = $details[1];
		
		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
	
	public static function getScriptDetails()
	{
		$file = [];
		$real = [];
		
		if(!!$_SERVER['DOCUMENT_ROOT'])
		{
			$file['root'] = $_SERVER['DOCUMENT_ROOT'];
			$real['root'] = realpath($file['root']);
		}
		else
		{
			$file['root'] = getcwd();
			$real['root'] = realpath($file['root']);
		}

		$file['file'] = FileSystem::joinPath($file['root'], $_SERVER['SCRIPT_NAME']);
		$real['file'] = realpath($file['file']);
		
		$file['dir'] = dirname($file['file']);
		$real['dir'] = dirname($real['file']);

		$file['name'] = basename($file['file']);
		$real['name'] = basename($real['file']);

		$file['base'] = basename($file['name'], '.php');
		$real['base'] = basename($real['name'], '.php');
		
		return [ $file, $real ];
	}
}

?>
