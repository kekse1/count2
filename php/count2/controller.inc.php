<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

//require_once(__DIR__ . '/../kekse/configuration.inc.php');
require_once(__DIR__ . '/session.inc.php');

class Controller extends \kekse\Quant
{
	public function __construct(... $args)
	{
		parent::__construct(new Session($this), ... $args);
		$this->importConfigurationDifferences();
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	private function importConfigurationDifferences()
	{
		$real = [	'dir'	=> $this->session->environment->real['dir'],
				'base'	=> $this->session->environment->real['base'] ];
		$file = [	'dir'	=> $this->session->environment->file['dir'],
				'base'	=> $this->session->environment->file['base'] ];
		$real['full'] = \kekse\FileSystem::joinPath($real['dir'], $real['base']);
		$real['json'] = $real['full'] . '.json';
		$file['full'] = \kekse\FileSystem::joinPath($file['dir'], $file['base']);
		$file['json'] = $file['full'] . '.json';

		$result = [];

		if(\kekse\FileSystem::isFile($real['json'], true))
		{
			$real['data'] = \kekse\FileSystem::readFile($real['json']);
			$this->session->configuration->importValues(\kekse\parseJSON($real['data']));
			array_push($result, $real['json']);
		}

		if(\kekse\FileSystem::isFile($file['json'], true))
		{
			if(count($result) === 0)
			{
				$file['data'] = \kekse\FileSystem::readFile($file['json']);
				$this->session->configuration->importValues(\kekse\parseJSON($file['data']));
				array_push($result, $file['json']);
			}
			else if($real !== $file)
			{
				$file['data'] = \kekse\FileSystem::readFile($file['json']);
				$this->session->configuration->importValues(\kekse\parseJSON($file['data']));
				array_push($result, $file['json']);
			}
		}

		return $result;
	}

}

?>
