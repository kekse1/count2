<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/filesystem.inc.php');
require_once(__DIR__ . '/map.inc.php');

class Configuration extends Map
{
	public function __construct($session = null, $values = null, ... $args)
	{
		return parent::__construct($session, $values, ... $args);
	}

	public static function fromJSON($path, $session = null, ... $args)
	{
		$values = FileSystem::readFile($path);

		if(!$values)
		{
			return null;
		}
		
		$values = parseJSON($values);
		
		if(!is_array($values))
		{
			return null;
		}

		return new Configuration($session, $values, ... $args);
	}

	public function __destruct()
	{
		unset($this->session);
		return parent::__destruct();
	}
	
	public function addDifferences($diff, $check = true)
	{
		$result = 0;
		$diff = Map::castValues($diff);
		$hasValues = is_array($this->values);

		foreach($diff as $key => $value)
		{
			if($hasValues && $check)
			{
				if(!array_key_exists($key, $this->values))
				{
					continue;
				}
			}

			$this->values[$key] = $value;
		}

		return $result;
	}
	
	public function addDifferencesFromJSON($path)
	{
		$diff = FileSystem::readFile($path);
		
		if(!$diff)
		{
			return null;
		}
		
		$diff = parseJSON($diff);

		if(!is_array($diff))
		{
			return null;
		}

		return $this->addDifferences($diff);
	}
}

?>
