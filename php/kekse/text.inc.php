<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/math.inc.php');

//
class Text
{
	public static function unique($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		$len = strlen($string);

		if($len === 0)
		{
			return $string;
		}
		
		$result = '';

		for($i = 0; $i < $len; ++$i)
		{
			if(!Text::contains($result, $string[$i], true))
			{
				$result .= $string[$i];
			}
		}

		return $result;
	}
	
	public static function trim($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		$result = '';
		$len = strlen($string);
		$start = -1;
		$stop = -1;
		$byte;

		for($i = 0; $i < $len; ++$i)
		{
			$byte = ord($string[$i]);

			if($byte > 32 && $byte !== 127)
			{
				$start = $i;
				break;
			}
		}

		for($i = $len - 1; $i > $start; --$i)
		{
			$byte = ord($string[$i]);

			if($byte > 32 && $byte !== 127)
			{
				$stop = $i;
				break;
			}
		}

		if($start > -1)
		{
			$string = substr($string, $start);
		}

		if($stop > -1)
		{
			$string = substr($string, 0, $stop - $start + 1);
		}

		return $string;
	}
	
	public static function removeWhiteSpaces($string)
	{
		return self::removeBinary($string, true);
	}
	
	public static function removeBinary($string, $whiteSpaces = false)
	{
		if(!is_string($string))
		{
			return null;
		}
		
		$len = strlen($string);
		
		if($len === 0)
		{
			return $string;
		}
		
		$result = '';
		$byte;
		
		for($i = 0; $i < $len; ++$i)
		{
			$byte = ord($string[$i]);
			
			if($byte === 32 && $whiteSpaces)
			{
				continue;
			}
			else if($byte > 31 && $byte !== 127)
			{
				$result .= $string[$i];
			}
		}
		
		return $result;
	}
	
	public static function containsBinary($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		$len = strlen($string);
		
		if($len === 0)
		{
			return false;
		}
		
		$byte;

		for($i = 0; $i < $len; ++$i)
		{
			$byte = ord($string[$i]);

			if($byte < 32 || $byte === 127)
			{
				return true;
			}
		}

		return false;
	}
	
	public static function isLowerCase($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		return (strtolower($string) === $string);
	}
	
	public static function isUpperCase($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		return (strtoupper($string) === $string);
	}
	
	public static function endsWith($haystack, $needle, $caseSensitive = true)
	{
		if(! (is_string($haystack) && is_string($needle)))
		{
			throw new \Exception('Invalid arguments (expecting Strings)');
		}
		else if(!$caseSensitive)
		{
			$haystack = strtolower($haystack);
			$needle = strtolower($needle);
		}
		
		return str_ends_with($haystack, $needle);
	}
	
	public static function startsWith($haystack, $needle, $caseSensitive = true)
	{
		if(! (is_string($haystack) && is_string($needle)))
		{
			throw new \Exception('Invalid argument(s) (expecting Strings)');
		}
		else if(!$caseSensitive)
		{
			$haystack = strtolower($haystack);
			$needle = strtolower($needle);
		}
		
		return str_starts_with($haystack, $needle);
	}

	public static function contains($haystack, $needle, $caseSensitive = true)
	{
		if(! (is_string($haystack) && is_string($needle)))
		{
			throw new \Exception('Invalid argument(s) (expecting Strings)');
		}
		else if(!$caseSensitive)
		{
			$haystack = strtolower($haystack);
			$needle = strtolower($needle);
		}
	
		return str_contains($haystack, $needle);
	}

	public static function unit($string, $double = null, $unit = null, $fix = true)
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
			
			if($double === false)
			{
				$result['value'] = (int)$result['value'];
			}
			else if($double === null && fmod($result['value'], 1) == 0)
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
								$res = Math::pt2px((double)$result['value']);
								if(fmod($res, 1) == 0) $res = (int)$res;
							}
							else
							{
								$res = Math::pt2px($result['value']);
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
								$res = Math::px2pt((double)$result['value']);
								if(fmod($res, 1) == 0) $res = (int)$res;
							}
							else
							{
								$res = Math::px2pt($result['value']);
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
					return null;
					//throw new \Exception('Invalid $unit defined [ `px`, `pt` ]');
					break;
			}
		}
		
		return $fin($result, true);
	}
	
	public static function at($string, $index, $needle = null, $caseSensitive = true)
	{
		$lenString;

		if(!is_string($string))
		{
			throw new \Exception('Invalid $string argument (not a String)');
		}
		else if($string === '')
		{
			return null;
		}
		else if(is_int($index))
		{
			if(($index = Math::getIndex($index, ($lenString = strlen($string)))) === null)
			{
				return null;
			}
		}
		else
		{
			throw new \Exception('Invalid $index argument (no Integer)');
		}

		if(!(is_string($needle) && $needle !== ''))
		{
			return $string[$index];
		}

		$lenNeedle = strlen($needle);

		if(($lenNeedle + $index) > $lenString)
		{
			return false;
		}

		$cmp = substr($string, $index, $lenNeedle);

		if(!$caseSensitive)
		{
			$needle = strtolower($needle);
			$cmp = strtolower($cmp);
		}

		return ($needle === $cmp);
	}
}

//
?>
