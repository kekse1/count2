<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/filesystem.inc.php');
require_once(__DIR__ . '/map.inc.php');

class Configuration extends Map
{
	public function __construct($session = null, $scheme = null, $values = null, ... $args)
	{
		parent::__construct($session, $scheme, $values, ... $args);
		$this->checkEnvironment();
	}
	
	public function __destruct()
	{
		unset($this->session);
		parent::__destruct();
	}
	
	public function checkEnvironment()
	{
		if(!$this->session->environment)
		{
			return null;
		}
		
		$real = [	'dir'	=> $this->session->environment->real['dir'],
				'base'	=> $this->session->environment->real['base'] ];
		$file = [	'dir'	=> $this->session->environment->file['dir'],
				'base'	=> $this->session->environment->file['base'] ];
		$real['full'] = \kekse\FileSystem::join($real['dir'], $real['base']);
		$real['json'] = $real['full'] . '.json';
		$file['full'] = \kekse\FileSystem::join($file['dir'], $file['base']);
		$file['json'] = $file['full'] . '.json';

		$result = [];

		if(\kekse\FileSystem::isFile($real['json'], true))
		{
			$real['data'] = \kekse\FileSystem::readFile($real['json']);
			$this->importValues(\kekse\parseJSON($real['data']));
			array_push($result, $real['json']);
		}

		if(\kekse\FileSystem::isFile($file['json'], true))
		{
			if(count($result) === 0)
			{
				$file['data'] = \kekse\FileSystem::readFile($file['json']);
				$this->importValues(\kekse\parseJSON($file['data']));
				array_push($result, $file['json']);
			}
			else if($real !== $file)
			{
				$file['data'] = \kekse\FileSystem::readFile($file['json']);
				$this->importValues(\kekse\parseJSON($file['data']));
				array_push($result, $file['json']);
			}
		}

		return $result;
	}
}

?>
