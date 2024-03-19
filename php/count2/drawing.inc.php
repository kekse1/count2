<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/color.inc.php');

class Drawing extends \kekse\Quant
{
	private $image = null;

	private $mode;
	private $type;

	public function __construct($session, $mode, $type, ... $args)
	{
		if(!extension_loaded('gd'))
		{
			throw new \Exception('Unable to find the GD library module');
		}
		
		$this->session = $session;
		
		if(is_string($mode) && strlen($mode) > 0)
		{
			$this->mode = self::checkMode($mode);
		}
		else
		{
			$this->mode = null;
		}
		
		if(is_string($type) && strlen($type) > 0)
		{
			$this->type = self::checkType($type);
		}
		else
		{
			$this->type = null;
		}
		
		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		$this->destroyImage();
		return parent::__destruct();
	}

	public function setMode($mode)
	{
		if(!($mode = self::checkMode($mode)))
		{
			throw new \Error('Invalid $mode argument');
		}
		
		return $this->mode = $mode;
	}
	
	public function getMode()
	{
		return $this->mode;
	}
	
	public function setType($type)
	{
		if(!($type = self::checkType($type)))
		{
			throw new \Error('Invalid $type argument');
		}
		
		return $this->type = $type;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public static function checkMode($mode)
	{
		if(!is_string($mode))
		{
			return null;
		}
		else switch(strtolower($mode))
		{
			case 'draw': return 'draw'; 
			case 'text': return 'text';
			case 'zero': return 'zero';
		}
		
		return null;
	}
	
	public static function checkType($type)
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
		else if(!is_number($alpha))
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
	
	public function sendImageHeader()
	{
		if(!$this->session)
		{
			throw new \Exception('There\'s no [session] available');
		}
		
		switch($this->type)
		{
			case 'png': return $this->session->connection->sendTypeHeader('image/png');
			case 'jpg': return $this->session->connection->sendTypeHeader('image/jpeg');
		}

		throw new \Error('Invalid [type] member, can\'t send valid image header');
	}
	
	public function sendTextHeader()
	{
		if(!$this->session)
		{
			throw new \Exception('There\'s no [session] available');
		}
		
		//TODO/config default etc..!!!
		//return HTTP->sendTypeHeader($type); (w/ instance of http)
	}
	
	public static getFont($name)
	{
		if(!is_string($name) || $name === '')
		{
			return null;
		}
		else if(! str_ends_with($name, '..ttf'))
		{
			$name .= '.ttf';
		}
	}
}

?>
