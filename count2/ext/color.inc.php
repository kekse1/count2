<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('security.inc.php');

class Color extends Quant
{
	public function __construct($name, ... $args)
	{
		return parent::__construct('Color', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function fixColor($array, $gd = null)
	{
		if(!is_bool($gd))
		{
			$gd = self::withGD();
		}
		
		$array = [ ... $array ];
		$len = count($array);
		
		if($len < 3 || $len > 4)
		{
			return null;
		}
		
		for($i = 0; $i < 3; ++$i)
		{
			if(is_double($array[$i]))
			{
				if($array[$i] < 0.0)
				{
					$array[$i] = 0;
				}
				else if($array[$i] > 1.0)
				{
					$array[i] = 255;
				}
				else
				{
					$array[$i] = (int)($array[$i] * 255);
				}
			}
			else if(is_int($array[$i]))
			{
				if($array[$i] < 0)
				{
					$array[$i] = 0;
				}
				else if($array[$i] > 255)
				{
					$array[$i] = 255;
				}
			}
		}
		
		if($len === 3)
		{
			$array[3] = 1.0;
		}
		else if(is_int($array[3]))
		{
			$array[3] = (double)($array[3] / 255);
		}
		
		if($gd)
		{
			$array = self::fixColorGD($array);
		}
		
		return $array;
	}
	
	//TODO/bigger testing..!!!
	public static function fixColorGD($value, $int = false)
	{
		$array = null;
		$result = [ ... $value ];
		
		if(is_array($value))
		{
			$len = count($array = $result);
			
			if($len === 0 || $len > 4)
			{
				return null;
			}
			else if($len === 3)
			{
				$result = 1.0;
			}
			else
			{
				$result = $result[3];
			}
		}
		
		if(is_int($result))
		{
			$result = (double)($result / 255);
		}
		else if(!is_double($result))
		{
			return null;
		}
		
		$result = (int)(127 - ($result * 127));
		
		if($array !== null)
		{
			$array[3] = $result;
			return $array;
		}
		
		return $result;
	}
	
	public static function isColor($value)
	{
		if(is_array($value))
		{
			return self::colorCheck($value);
		}
		else if(is_string($value))
		{
			return (self::colorIsList($value) || self::colorIsHexadecimal($value));
		}
		
		return false;
	}
	
	public static function colorIsList($value)
	{
		if(!is_string($value) || $value === '')
		{
			return false;
		}
		
		$len = strlen($value);
		
		if($len > KEKSE_LIMIT_STRING)
		{
			return false;
		}
		else if(str_contains($value, '#'))
		{
			return false;
		}
		else if(!str_contains($value, ','))
		{
			return false;
		}
		else if(!($value = Security::checkString($value, true, true)))
		{
			return false;
		}
		
		$len = strlen($value);
		$byte;
		
		for($i = 0; $i < $len; ++$i)
		{
			if(($byte = ord($value[$i])) === 46 || $byte === 44)
			{
				continue;
			}
			else if($byte <= 32)
			{
				continue;
			}
			else if(!ctype_digit(chr($byte)))
			{
				return false;
			}
		}
		
		return true;
	}
	
	public static function colorIsHexadecimal($value)
	{
		if(!is_string($value) || $value === '')
		{
			return false;
		}
		
		$len = strlen($value);
		
		if($len > KEKSE_LIMIT_STRING)
		{
			return false;
		}
		else if(str_contains($value, ','))
		{
			return false;
		}
		else if(!($value = Security::checkString($value, true, true)))
		{
			return false;
		}
		
		if($value[0] === '#')
		{
			$value = substr($value, 1);
		}
		
		$len = strlen($value);
		
		switch($len)
		{
			case 3: case 4: case 6: case 8: break;
			default: return false;
		}
		
		for($i = 0; $i < $len; ++$i)
		{
			if(!(self::getHexadecimalSymbol($value[$i])))
			{
				return false;
			}
		}
		
		return true;
	}
	
	public static function getHexadecimalSymbol($char)
	{
		$char = $char[0];
		$byte;
		
		if(($byte = ord($char)) >= 48 && $byte <= 57)
		{
			return $char;
		}
		else if($byte >= 97 && $byte <= 102)
		{
			return $char;
		}
		else if($byte >= 65 && $byte <= 70)
		{
			return chr($byte + 32);
		}
		
		return '';
	}
	
	public static function colorCheck($array)
	{
		if(!is_array($array))
		{
			return false;
		}
		
		$len = count($array);
		
		if($len < 3 || $len > 4)
		{
			return false;
		}
		
		for($i = 0; $i < 3; ++$i)
		{
			if(!is_int($array[$i]))
			{
				return false;
			}
			else if($array[$i] < 0 || $array[$i] > 255)
			{
				return false;
			}
		}
		
		if($len === 4)
		{
			if(is_int($array[3]))
			{
				if($array[3] < 0 || $array[3] > 255)
				{
					return false;
				}
			}
			else if(is_double($array[3]))
			{
				if($array[3] < 0.0 || $array[3] > 1.0)
				{
					return false;
				}
			}
		}
		
		return true;
	}
	
	public static function withGD()
	{
		if(CLI::isCLI()) return false;
		return extension_loaded('gd');
	}
	
	public static function getColor($value, $gd = null)
	{
		$len;
		
		if(!is_bool($gd))
		{
			$gd = self::withGD();
		}
		
		if(!is_string($value))
		{
			if(self::colorCheck($value))
			{
				return self::fixColor($value, $gd);
			}
			
			return null;
		}
		else if($value === '')
		{
			return null;
		}
		else if(($len = strlen($value)) > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		else if(!($value = Security::checkString($value, true, true)))
		{
			return null;
		}
		
		if(substr($value, 0, 5) === 'rgba(')
		{
			$value = substr($value, 5);
		}
		else if(substr($value, 0, 4) === 'rgb(')
		{
			$value = substr($value, 4);
		}
		
		if($value[$len - 1] === ')')
		{
			$value = substr($value, 0, -1);
		}
		
		$result;
		
		if(self::colorIsHexadecimal($value))
		{
			$result = self::getColorHexadecimal($value);
		}
		else if(self::colorIsList($value))
		{
			$result = self::getColorList($value);
		}
		
		if($result !== null)
		{
			$result = self::fixColor($result, $gd);
		}
		
		return $result;
	}
	
	public static function getColorHexadecimal($string)
	{
		if(!is_string($string))
		{
			if(self::colorCheck($string))
			{
				return $string;
			}
			
			return null;
		}
		else if($string === '')
		{
			return null;
		}
		
		$len = strlen($string);
		
		if($len > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		else if(str_contains($string, ','))
		{
			return null;
		}
		else if(!($string = Security::checkString($string, true, true)))
		{
			return null;
		}
		
		if($string[0] === '#')
		{
			$string = substr($string, 1);
		}
		
		$len = strlen($string);
		$result = '';
		$l = 0;
		$s;
		
		for($i = 0; $i < $len; ++$i)
		{
			if(($s = self::getHexadecimalSymbol($string[$i])) !== '')
			{
				$result .= $s;
				++$l;
			}
		}
		
		if($l !== 3 && $l !== 4 && $l !== 6 && $l !== 8)
		{
			return null;
		}
		else if($l === 3 || $l === 4)
		{
			$r = '';
			
			for($i = 0; $i < $l; ++$i)
			{
				$r .= $result[$i] . $result[$i];
			}
			
			$result = $r;
			
			if($l === 3)
			{
				$result .= 'ff';
			}
		}
		else if($l === 6)
		{
			$result .= 'ff';
		}
		
		$l = 8;
		$string = $result;
		$result = [];
		$tmp = '';
		
		for($i = 0, $j = 0; $i < $l; $i += 2, ++$j)
		{
			$result[$j] = hexdec($string[$i] . $string[$i + 1]);
		}
		
		return $result;
	}
	
	public static function getColorList($string)
	{
		if(!is_string($string))
		{
			if(self::colorCheck($string))
			{
				return $string;
			}
			
			return null;
		}
		else if($string === '')
		{
			return null;
		}
		
		$len = strlen($string);
		
		if($len > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		else if(!str_contains($string, ','))
		{
			return null;
		}
		else if(!($string = Security::checkString($string, true, true)))
		{
			return null;
		}
		
		$split = explode(',', $string);
		$result = [];
		$item = '';
		$byte;
		$len = count($split);
		
		if($len === 3)
		{
			$split[3] = '1';
		}
		else if($len !== 4)
		{
			return null;
		}
		
		for($i = 0; $i < 3; ++$i)
		{
			if(is_number($split[$i]))
			{
				$result[$i] = (int)str_replace('.', '', $split[$i]);
			}
			else
			{
				return null;
			}
		}
		
		$result[3] = (double)$split[3];
		return $result;
	}
}

?>
