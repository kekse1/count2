<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');

class Drawing extends \kekse\Quant
{
	private $image = null;

	private $mode;
	private $type;

	public function __construct($mode, $type, ... $args)
	{
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
		
		return parent::__construct('Drawing', ... $args);
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
	
	public function createImage($width, $height, $color = null)
	{
		$result = imagecreatetruecolor($width, $height);
		imagesavealpha($result, true);
		
		if($color === null)
		{
			imagefill($result, 0, 0, imagecolorallocatealpha($result, 255, 255, 255, 127));
		}
		else
		{
//zzzzzzzzz		imagefill($result, 0, 0, $color.....);//TODO/!!
		}

		if($width > 1 && $height > 1)
		{
			imagealphablending($result, true);
			imageantialias($result, true);
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
	}
	
	public function drawText($string)
	{
	}
	
	public function sendImageHeader()
	{
		switch($this->type)
		{
			case 'png': break; //return HTTP->sendTypeHeader('image/png'); (w/ instance of http);
			case 'jpg': break; //return HTTP->sendTypeHeader('image/jpeg'); (w/ instance of http);
		}

		throw new \Error('Invalid [type] member, can\'t send valid image header');
	}
	
	public function sendTextHeader()
	{
		//return HTTP->sendTypeHeader($type); (w/ instance of http)
	}
}

?>
