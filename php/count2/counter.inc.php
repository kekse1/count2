<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

class Counter extends \kekse\FileSystem
{
	public $carrier;

	public function __construct($session, $carrier, $type, ... $args)
	{
		$this->carrier = $carrier;
		$this->type = self::checkType($type);
		return parent::__construct($session, ... $args);
	}
	
	public static function checkType($type)
	{
		if(!is_string($type))
		{
			$type = 'visit';
		}
		else switch($type = strtolower($type))
		{
			case 'click':
				break;
			case 'visit':
			default:
				$type = 'visit';
				break;
		}
		
		return $type;
	}
	
	public function getType()
	{
		if(!$this->type)
			return 'visit';
		return $this->type;
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public function getValue()
	{
		$path = $this->getValuePath();

		if(!\kekse\FileSystem::isFile($path, true))
		{
			return 0;
		}

		return (int)file_get_contents($path);
	}

	public function increment()
	{
		$path = $this->getValuePath();

		if(!\kekse\FileSystem::isFile($path, true, true))
		{
			return $this->getValue();
		}

		$fh = fopen($path, 'w+');

		if(!$fh)
		{
			return $this->getValue();
		}

		flock($fh, LOCK_EX);
		$result = (int)fread($fh, KEKSE_COUNT2_LIMIT_FILE_SIZE);
		fseek($fh, 0);
		$result = ++$result;
		$string = (string)$result;
		ftruncate($fh, strlen($string));
		fwrite($fh, $string);
		fclose($fh);
		return $result;
	}
	
	public function getPath($prefix = '')
	{
		return \kekse\FileSystem::joinPath(
			$this->session->configuration->get('path'),
			$this->session->configuration->get($this->getType()),
			$prefix . $this->carrier);
	}
	
	public function getValuePath()
	{
		return $this->getPath(KEKSE_COUNT2_PREFIX_VALUE);
	}
	
	public function getCachePath()
	{
		return $this->getPath(KEKSE_COUNT2_PREFIX_CACHE);
	}
	
	public function getConfigPath()
	{
		return $this->getPath(KEKSE_COUNT2_PREFIX_CONFIG);
	}
}

?>
