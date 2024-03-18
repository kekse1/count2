<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

define('KEKSE_LIMIT_STRING', 255);

class Security extends Quant
{
	public function __construct(... $args)
	{
		return parent::__construct('Security', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
	
	public static function secure($string, $type)
	{
		if(!is_string($string) || $string === '')
		{
			return null;
		}
		else if(!is_string($type))
		{
			throw new \Error('Invalid $type argument');
		}
		else switch($type = strtolower($type))
		{
			case 'host':
			case 'path':
				break;
			default: throw new \Error('Invalid $type argument');
		}
		
		$len = strlen($string);

		if($len > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		
		$result = '';
		$l = 0;
		$byte;
		$last;
		$add;

		for($i = 0; $i < $len; ++$i)
		{
			if((($byte = ord($string[$i])) >= 97 && $byte <= 122) || $byte >= 48 && $byte <= 57)
			{
				$add = chr($byte);
			}
			else if($byte >= 65 && $byte <= 90)
			{
				switch($type)
				{
					case 'host': $add = chr($byte + 32); break;
					case 'path': $add = chr($byte); break;
				}
			}
			else if($byte === 46)
			{
				if($result === '' || $last !== '.')
				{
					$add = '.';
				}
			}
			else if($byte === 40 || $byte === 41)
			{
				$add = chr($byte);
			}
			else if($byte >= 43 && $byte <= 45)
			{
				$add = chr($byte);
			}
			else if($string[$i] === DIRECTORY_SEPARATOR)
			{
				if($result !== '' && $last !== DIRECTORY_SEPARATOR)
				{
					$add = DIRECTORY_SEPARATOR;
				}
			}
			else if($byte === 95 || $byte === 58 || $byte === 35 || $byte === 61)
			{
				$add = chr($byte);
			}
			else
			{
				$add = '';
			}
			
			if($add !== '')
			{
				$result .= $add;
				
				if(++$l >= KEKSE_LIMIT_STRING)
				{
					break;
				}
				
				$last = $add;
			}
		}
		
		if($l === 0)
		{
			return null;
		}
		
		return $result;
	}
	
	public static function secureHost($host)
	{
		return self::secure($host, 'host');
	}
	
	public static function securePath($path)
	{
		return self::secure($path, 'path');
	}
	
	public static function removeBinary($string, $null = false, $removeSpaces = false)
	{
		if(!is_string($string))
		{
			return ($null ? null : '');
		}
		
		$len = strlen($string);
		
		if($len > KEKSE_LIMIT_STRING)
		{
			return ($null ? null : '');
		}

		$result = '';
		
		if($len > 0)
		{
			$byte;

			for($i = 0; $i < $len; ++$i)
			{
				if($removeSpaces && $string[$i] === ' ')
				{
					continue;
				}
				else if(($byte = ord($string[$i])) > 31 && $byte !== 127)
				{
					$result .= $string[$i];
				}
			}
		}

		if($null && strlen($result) === 0)
		{
			return null;
		}

		return $result;
	}
	
	public static function removeWhiteSpaces($string, $null = false)
	{
		self::removeBinary($string, $null, true);
	}
	
	public static function checkString($string, $removeBinary = true, $trim = true)
	{
		if(!is_string($string))
		{
			return null;
		}
		else if($string === '')
		{
			return null;
		}
		else if(strlen($string) > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		
		if($removeBinary)
		{
			$string = self::removeBinary($string, true, false);
		}
		
		if($trim)
		{
			$string = trim($string);
		}
		
		return $string;
	}
	
	public static function isValidString($value)
	{
		if(!is_string($value))
		{
			return false;
		}
		else if(strlen($value) > KEKSE_LIMIT_STRING)
		{
			return false;
		}
		
		return true;
	}

	public static function limitString($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		return substr($string, 0, KEKSE_LIMIT_STRING);
	}
}

?>
