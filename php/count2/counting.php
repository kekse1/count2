<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

require_once(__DIR__ . '/../kekse/filesystem.php');

class Counting extends \kekse\FileSystem
{
	public $type;

	public function __construct($session, ... $args)
	{
		parent::__construct($session, ... $args);
	}

	/*private function checkCarrier($carrier)
	{
		switch($this->type)
		{
			case 'visit':
				if(!$this->session->configuration->getBoolean('visit'))
				{
					throw new \Error('Counting visits is not allowed');
				}
				else if($this->session->parameter->getString('carrier'))
				{
					if(!$this->session->configuration->getBoolean('override'))
					{
						throw new \Error('Can\'t override carrier (not allowed)');
					}
				}
				break;
			case 'click':
				if(!$this->session->configuration->getBoolean('click'))
				{
					throw new \Error('Counting clicks is not allowed');
				}
				else if(!$this->session->parameter->getString('carrier'))
				{
					throw new \Error('Missing carrier parameter');
				}
				break;
			case 'mail':
				if(!$this->session->configuration->getBoolean('mail'))
				{
					throw new \Error('Counting mails is not allowed');
				}
				else if(!$this->session->parameter->getString('carrier'))
				{
					throw new \Error('Missing carrier parameter');
				}
				break;
		}
	}

	public static function count()
	{
		return [ 'visit', 'click', 'mail' ];
	}
	
	public static function checkType($type, $error = true)
	{
		if(!is_string($type))
		{
			$type = 'visit';
		}

		$count = self::count();

		if(in_array($type = strtolower($type), $count))
		{
			return $type;
		}
		else if($error)
		{
			throw new \Error('Invalid $type');
		}

		return 'visit';
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}*/

/*	public function getValue()
	{
		$path = $this->getValuePath();

		if(!\kekse\FileSystem::isFile($path, true))
		{
			return 0;
		}

		return (int)file_get_contents($path);
	}
 */

	//TODO @ \kekse\numeric.php.. @ rdx = 256! ;-)
	/*public function incrementValue()
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
	}*/
}

?>
