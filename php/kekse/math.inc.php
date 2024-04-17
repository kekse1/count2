<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
const KEKSE_UNIT_THROW = true;

//
require_once(__DIR__ . '/constants.inc.php');

//
class Math
{
	public static function bytes($bytes, $unitOrBase = KEKSE_UNIT_BASE, $precision = KEKSE_UNIT_PRECISION, $string = KEKSE_UNIT_STRING)
	{
		if(!is_bool($string))
		{
			throw new \Exception('Invalid $string argument (not a Boolean type)');
		}
		
		$max = null;
		$unit = null;
		$base = KEKSE_UNIT_BASE;
		
		if(!is_int($precision) || $precision < 0)
		{
			$precision = null;
		}
		
		if(is_int($unitOrBase))
		{
			switch($base = $unitOrBase)
			{
				case 1024:
					$max = (count(KEKSE_UNIT_1024) - 1);
					break;
				case 1000:
					$max = (count(KEKSE_UNIT_1000) - 1);
					break;
				default:
					if(KEKSE_UNIT_THROW)
					{
						throw new \Error('Invalid $base argument [ 1024, 1000 ]');
					}
					break;
			}
		}
		else if(is_string($unitOrBase))
		{
			$findUnit = function($u) use(&$base)
			{
				$u = strtolower($u);
				
				switch($u)
				{
					case 'b':
					case 'byte':
					case 'bytes':
						return [ 0, $base ];
				}
				
				$len = count(KEKSE_UNIT_1024);

				for($i = 0; $i < $len; ++$i)
				{
					if(strtolower(KEKSE_UNIT_1024[$i]) === $u)
					{
						return [ $i, 1024, $len - 1 ];
					}
				}
				
				$len = count(KEKSE_UNIT_1000);
				
				for($i = 0; $i < $len; ++$i)
				{
					if(strtolower(KEKSE_UNIT_1000[$i]) === $u)
					{
						return [ $i, 1000, $len - 1 ];
					}
				}
				
				return false;
			};
			
			$r = $findUnit($unitOrBase);
			
			if($r === false)
			{
				throw new \Error('Unable to find unit \'' . $unitOrBase . '\'');
			}
			
			[ $unit, $base, $max ] = $r;
		}
		else switch($base)
		{
			case 1024:
				$max = count(KEKSE_UNIT_1024) - 1;
				break;
			case 1000:
				$max = count(KEKSE_UNIT_1000) - 1;
				break;
		}

		$index = 0;
		$rest = (double)$bytes;
		
		while($rest >= $base)
		{
			if($max !== null && $index >= $max)
			{
				break;
			}
			else if($unit !== null && $index === $unit)
			{
				break;
			}
			
			$rest /= $base;
			++$index;
		}

		switch($base)
		{
			case 1024:
				$unit = KEKSE_UNIT_1024[$index];
				break;
			case 1000:
				$unit = KEKSE_UNIT_1000[$index];
				break;
			default:
				$unit = '';
				break;
		}

		$orig = $rest;

		if($precision !== null)
		{
			$rest = round($rest, $precision);
		}
		
		if(fmod($rest, 1) == 0)
		{
			$rest = (int)$rest;
		}
		
		if($string)
		{
			return ((string)$rest . ' ' . $unit);
		}

		$result = [ $rest, $index, $base, $unit, $orig ];
		$result['value'] = $rest;
		$result['index'] = $index;
		$result['base'] = $base;
		$result['unit'] = $unit;
		$result['orig'] = $orig;

		return $result;
	}
	
	public static function px2pt($value)
	{
		return ($value * 0.75);
	}
	
	public static function pt2px($value)
	{
		return ($value / 0.75);
	}
	
	public static function getIndex($index, $length)
	{
		if($length < 1)
		{
			return null;
		}

		if(($index = ((int)$index % ($length = (int)$length))) < 0)
		{
			$index = (($length + $index) % $length);
		}

		return $index;
	}
}

//
?>
