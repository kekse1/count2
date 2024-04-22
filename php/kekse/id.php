<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/numeric.php');
require_once(__DIR__ . '/functions.php');

//
class UUID
{
	const SEPARATOR = '-';
	const SCHEME = [ 8, 4, 4, 4, 12 ];
	const RADIX = 16;

	protected static function stringLength($separator = null)
	{
		if(!is_string($separator) || strlen($separator) !== 1)
		{
			$separator = self::SEPARATOR;
		}
		
		$result = 0;
		$scheme = self::SCHEME;
		
		foreach($scheme as $sub)
		{
			$result += $sub + 1;
		}
		
		return ($result - 1);
	}
	
	public static function random($separator = null)
	{
		if(!is_string($separator) || strlen($separator) !== 1)
		{
			$separator = self::SEPARATOR;
		}

		$alphabet = Number::alphabet(self::RADIX);
		$alphabetLen = strlen($alphabet);
		$scheme = self::SCHEME;
		$schemeLen = count($scheme);
		$result = '';
		
		for($i = 0; $i < $schemeLen; ++$i)
		{
			for($j = 0; $j < $scheme[$i]; ++$j)
			{
				$result .= $alphabet[Number::randomInt($alphabetLen - 1, 0)];
			}
			
			$result .= $separator;
		}
		
		return substr($result, 0, -1);
	}
	
	public static function isValid($value)
	{
		if(!is_string($value))
		{
			return null;
		}
		else if($value === '')
		{
			return false;
		}
		
		$uuidLen = self::stringLength();
		$valueLen = strlen($value);
		
		if($valueLen !== $uuidLen)
		{
			return false;
		}
		
		$alphabet = Number::alphabet(self::RADIX);
		
		if(!$alphabet)
		{
			throw new \Error('Unable to get alphabet (for radix ' . (string)self::RADIX . ')');
		}
		
		$alphabetLen = strlen($alphabet);
		$scheme = self::SCHEME;
		$schemeLen = count($scheme);
		$symbol = $value[$scheme[0]];

		if(str_contains($alphabet, $symbol))
		{
			return false;
		}
		
		for($i = 1, $mul = $scheme[0]; $i < $schemeLen - 1; ++$i)
		{
			if($value[($mul += $scheme[$i]) + $i] !== $symbol)
			{
				return false;
			}
		}
		
		$string = '';
		
		for($i = 0, $mul = 0; $i < $schemeLen; ++$i)
		{
			$string .= substr($value, $mul + $i, $scheme[$i]);
			$mul += $scheme[$i];
		}
		
		$stringLen = strlen($string);
		
		for($i = 0; $i < $stringLen; ++$i)
		{
			if(! str_contains($alphabet, $string[$i]))
			{
				return false;
			}
		}
		
		return true;
	}
}

class ID extends UUID
{
	const SEPARATOR = '/';
	const RADIX = 1;
	
	public static function random($sep = true, $uuidSep = null)
	{
		return (parent::random($uuidSep) . self::getRest($sep));
	}
	
	public static function isValid($id)
	{
		if(!is_string($id))
		{
			return null;
		}
		else if($id === '')
		{
			return false;
		}
		
		$uuidLength = parent::stringLength();
		
		if(strlen($id) <= ($uuidLength + 1))
		{
			return false;
		}
		
		return parent::isValid(substr($id, 0, $uuidLength));
	}
	
	private static function getRest($sep = true)
	{
		$result = Number::renderInt(timestamp(), self::RADIX);
		
		if($sep === true)
		{
			$sep = self::SEPARATOR;
		}
		else if($sep === false)
		{
			$sep = ' ';
		}
		else if(!is_string($sep) || strlen($sep) !== 1)
		{
			$sep = self::SEPARATOR;
		}
		
		if($sep)
		{
			$result = $sep . $result;
		}
		
		return $result;
	}
}

//
?>
