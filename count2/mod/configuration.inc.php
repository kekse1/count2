<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/filesystem.inc.php');

class Configuration extends \kekse\Quant
{
	private $file = [];
	private $real = [];
	
	public function __construct(... $args)
	{
		$details = self::getScriptDetails();
		
		$this->file = $details[0];
		$this->real = $details[1];
		
		return parent::__construct('Configuration', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
	
	public static function readJSON($path)
	{
		//
	}
	
	public static function getScriptDetails()
	{
		$file = [];
		$real = [];
		
		$file['root'] = $_SERVER['DOCUMENT_ROOT'];
		$real['root'] = realpath($_SERVER['DOCUMENT_ROOT']);
		
		$file['file'] = \kekse\FileSystem::resolvePath($_SERVER['SCRIPT_NAME']);
		$real['file'] = realpath($file['file']);
		
		$file['dir'] = dirname($file['file']);
		$real['dir'] = dirname($real['file']);
		
		$file['name'] = basename($file['file']);
		$real['name'] = basename($real['file']);
		
		$file['base'] = basename($file['file'], '.php');
		$real['base'] = basename($real['file'], '.php');
		
		return [ $file, $real ];
	}
}

?>
