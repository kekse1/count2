<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

const SIZE_UNITS_1024 = [ 'Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB' ];
const SIZE_UNITS_1000 = [ 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ];

function renderSize($bytes, $precision = 2, $base = 1024)
{
	$max = count(SIZE_UNITS_1024);
	$rest = $bytes;
	$index = 0;

	while($rest >= $base)
	{
		$rest /= $base;
		if(++$index >= ($max - 1)) break;
	}

	$result = round($rest, $precision);
	return ($result . ' ' . ($base === 1024 ? SIZE_UNITS_1024[$index] : SIZE_UNITS_1000[$index]));
}

function unit($string, $double = null, $unit = null, $fix = true)
{
	$result = [
		'value' => '',
		'unit' => '',
		'originalValue' => null,
		'originalUnit' => null
	];
	
	$fin = function(&$return, $already = true) use(&$result)
	{
		if(!$already)
		{
			$cast();
		}
		
		if($fix && $result['value'] == 0)
		{
			$result['unit'] = '';
		}
		
		array_push($result, $result['value'], $result['unit'], $result['originalValue'], $result['originalUnit']);
		return $return;
	};
	
	$cast = function() use(&$result, $double)
	{
		if(!is_string($result['value']))
		{
			return $result;
		}
		
		$result['value'] = (double)$result['value'];
		
		if($double !== true && fmod($result['value'], 1) == 0)
		{
			$result['value'] = (int)$result['value'];
		}
		
		return $result;
	};

	$len = strlen($string);

	if($len === 0)
	{
		return $fin($result, false);
	}

	$gotPoint = false;
	$gotUnit = false;
	$byte;
	
	for($i = 0; $i < $len; ++$i)
	{
		$byte = ord($string[$i]);

		if($byte >= 48 && $byte <= 57)
		{
			if(!$gotUnit && $gotPoint !== null)
			{
				$result['value'] .= chr($byte);
			}
		}
		else if($byte >= 97 && $byte <= 122)
		{
			$result['unit'] .= chr($byte);
			$gotUnit = true;
		}
		else if($byte >= 65 && $byte <= 90)
		{
			$result['unit'] .= chr($byte + 32);
			$gotUnit = true;
		}
		else if($byte === 46 && !$gotUnit)
		{
			if($double === false || $gotPoint)
			{
				$gotPoint = null;
			}
			else
			{
				$gotPoint = true;
				$result['value'] .= '.';
			}
		}
	}

	$cast();
	
	if(is_string($unit) && $result['unit'] !== $unit)
	{
		switch($unit)
		{
			case 'px':
				switch($result['unit'])
				{
					case 'px': break;
					case 'pt':
						$res;
						$result['originalValue'] = $result['value'];
						$result['originalUnit'] = $result['unit'];
						
						if($double === null)
						{
							$res = pt2px((double)$result['value']);
							if(fmod($res, 1) == 0) $res = (int)$res;
						}
						else
						{
							$res = pt2px($result['value']);
						}
						
						$result['value'] = $res;
						$result['unit'] = $unit;
						break;
					default:
						return null;
						//throw new \Exception('Given unit is not convertable [ `px`, `pt` ]');
				}
				break;
			case 'pt':
				switch($result['unit'])
				{
					case 'pt': break;
					case 'px':
						$res;
						$result['originalValue'] = $result['value'];
						$result['originalUnit'] = $result['unit'];
						
						if($double === null)
						{
							$res = px2pt((double)$result['value']);
							if(fmod($res, 1) == 0) $res = (int)$res;
						}
						else
						{
							$res = px2pt($result['value']);
						}
						
						$result['value'] = $res;
						$result['unit'] = $unit;
						break;
					default:
						return null;
						//throw new \Exception('Given unit is not convertable [ `px`, `pt` ]');
				}
				break;
			default:
				throw new \Exception('Invalid $unit defined [ `px`, `pt` ]');
				break;
		}
	}
	
	return $fin($result, true);
}

function px2pt($value)
{
	return ($value * 0.75);
}

function pt2px($value)
{
	return ($value / 0.75);
}

function getIndex($index, $length)
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

?>
