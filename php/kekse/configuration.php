<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/filesystem.php');
//require_once(__DIR__ . '/path.php');
require_once(__DIR__ . '/map.php');

class Configuration extends Map
{
	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, ... $args);
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
		$real['full'] = Path::join($real['dir'], $real['base']);
		$real['json'] = $real['full'] . '.json';
		$file['full'] = Path::join($file['dir'], $file['base']);
		$file['json'] = $file['full'] . '.json';

		$result = [];

		if(FileSystem::isFile($real['json'], true))
		{
			$real['data'] = FileSystem::readFile($real['json']);
			$this->importValues(parseJSON($real['data']));
			array_push($result, $real['json']);
		}

		if(FileSystem::isFile($file['json'], true))
		{
			if(count($result) === 0)
			{
				$file['data'] = FileSystem::readFile($file['json']);
				$this->importValues(parseJSON($file['data']));
				array_push($result, $file['json']);
			}
			else if($real !== $file)
			{
				$file['data'] = FileSystem::readFile($file['json']);
				$this->importValues(parseJSON($file['data']));
				array_push($result, $file['json']);
			}
		}

		return $result;
	}
}

?>
