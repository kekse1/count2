<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('quant.inc.php');
require_once('mod/session.inc.php');

class Environment extends Quant
{
	public $session = null;

	public $file = [];
	public $real = [];
	
	public function __construct(... $args)
	{
		for($i = 0; $i < count($args); ++$i)
		{
			if($args[$i] instanceof \kekse\count2\Session)
			{
				$this->session = array_splice($args, $i--, 1)[0];
			}
		}

		$details = self::getScriptDetails();
		
		$this->file = $details[0];
		$this->real = $details[1];
		
		return parent::__construct('Environment', ... $args);
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
