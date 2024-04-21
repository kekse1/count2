<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//require_once(__DIR__ . '/main.php');
require_once(__DIR__ . '/filesystem.php');
require_once(__DIR__ . '/map.php');

class Environment extends Quant
{
	public $headers = null;

	public $file;
	public $real;
	
	public function __construct($session = null, ... $args)
	{
		$details = self::getScriptDetails();
		$this->file = $details[0];
		$this->real = $details[1];

		if(php_sapi_name() !== 'cli')
		{
			require_once(__DIR__ . '/connection.php');
			$this->headers = Connection::requestHeaders();
		}
		
		parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
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

		$file['path'] = Path::join($file['root'], $_SERVER['SCRIPT_NAME']);
		$real['path'] = realpath($file['path']);
		
		$file['dir'] = dirname($file['path']);
		$real['dir'] = dirname($real['path']);

		$file['name'] = basename($file['path']);
		$real['name'] = basename($real['path']);

		$file['base'] = basename($file['name'], '.php');
		$real['base'] = basename($real['name'], '.php');
		
		return [ $file, $real ];
	}

	public static function get($key = null, $cast = KEKSE_CAST)
	{
		$result = getenv($key);

		if($result === false)
		{
			$result = null;
		}
		else if($cast)
		{
			if(is_array($result)) foreach($result as $key => $value)
			{
				$result[$key] = Map::castValue($value);
			}
			else
			{
				$result = Map::castValue($result);
			}
		}

		else if($cast && is_string($result))
		{
			$result = Map::castValue($result);
		}

		return $result;
	}
}

?>
