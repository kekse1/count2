<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

//
require_once(__DIR__ . '/../kekse/text.inc.php');

//
class Drawing extends \kekse\Quant
{
	private $image = null;

	public function __construct($session, ... $args)
	{
		if(!extension_loaded('gd'))
		{
			throw new \Error('Unable to find the GD library module');
		}
		
		parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		$this->destroyImage();
		parent::__destruct();
	}

	public static function checkType($type = 'png')
	{
		if(!is_string($type)) return null;
		else $type = strtolower($type);
		$types = imagetypes();
		
		switch($type)
		{
			case 'png':
				return ($types & IMG_PNG);
			case 'jpg':
				return ($types & IMG_JPG);
		}
		
		return false;
	}

	public static function allocateColor($red, $green, $blue, $alpha)
	{
		if(is_array($red))
		{
			$len = count($red);

			if($len === 4)
			{
				$alpha = $red[3];
			}
			else
			{
				$alpha = null;
			}
						
			if($len >= 3)
			{
				$blue = $red[2];
				$green = $red[1];
				$red = $red[0];
			}
			else
			{
				$blue = $green = $red = null;
			}
		}
		else if(!Number::isNumber($alpha))
		{
			$alpha = null;
		}
		
		if($alpha === null)
		{
			return imagecolorallocate($red, $green, $blue);
		}
		
		return imagecolorallocatealpha($red, $green, $blue, $alpha);
	}
	
	public function createImage($width, $height, $color = null)
	{
throw new \Error('TODO');
		$result = imagecreatetruecolor($width, $height);
		imagesavealpha($result, true);

		if($width > 1 && $height > 1)
		{
			imagealphablending($result, true);
			imageantialias($result, true);
		}
		
		if($color === null)
		{
			imagefill($result, 0, 0, imagecolorallocatealpha($result, 255, 255, 255, 127));
		}
		else
		{
//zzzzzzzzz		imagefill($result, 0, 0, $color.....);//TODO/!!
		}

		return $this->image = $result;		
	}
	
	public function destroyImage()
	{
		if(!$this->image) return false;
		imagedestroy($this->image);
		$this->image = null;
		return true;
	}
	
	public function drawZero()
	{
		$result = $this->createImage(1, 1);
	}
	
	public function drawValue($value)
	{
		//
	}
	
	public function drawText($string)
	{
		//
	}
	
	public function sentImageHeader()
	{
		if(!$this->session)
		{
			return false;
			//throw new \Exception('There\'s no [session] available');
		}
		
		switch($this->type)
		{
			case 'png': $this->session->connection->setType('image/png'); return true;
			case 'jpg': $this->session->connection->setType('image/jpeg'); return true;
		}

		return false;
		//throw new \Error('Invalid [type] member, can\'t send valid image header');
	}
	
	public function sendTextHeader()
	{
		if(!$this->session)
		{
			return false;
			//throw new \Exception('There\'s no [session] available');
		}

		$type = $this->session->parameter->getString('type');
		$this->session->connection->setType($type);

		return true;
	}
	
	public static getFont($name)
	{
		if(!is_string($name) || $name === '')
		{
			return null;
		}
		else if(! Text::endsWith($name, '.ttf', false))
		{
			$name .= '.ttf';
		}
	}
}

?>
