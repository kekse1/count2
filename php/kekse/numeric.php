<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
const KEKSE_NUMERIC_ZERO_LIMIT = 8;
const KEKSE_NUMERIC_RADIX = 10;

//
const KEKSE_ALPHABET_DECIMAL = '0123456789';
const KEKSE_ALPHABET_LOWER = 'abcdefghijklmnopqrstuvwxyz';
const KEKSE_ALPHABET_UPPER = 'ABCDEFGHIJKLMONPQRSTUVWXYZ';
const KEKSE_ALPHABET_REGULAR = KEKSE_ALPHABET_DECIMAL . KEKSE_ALPHABET_LOWER;
const KEKSE_ALPHABET_EXTENDED = KEKSE_ALPHABET_REGULAR . KEKSE_ALPHABET_UPPER;
const KEKSE_ALPHABET_ALPHA = KEKSE_ALPHABET_LOWER . KEKSE_ALPHABET_UPPER;

//
require_once(__DIR__ . '/constants.php');
require_once(__DIR__ . '/text.php');

//
class Number
{
	public static function cast($value)
	{
		if(is_int($value))
		{
			return $value;
		}
		else if(!is_double($value))
		{
			return null;
		}
		else if(fmod($value, 1) == 0)
		{
			return (int)$value;
		}
		
		return $value;
	}
	
	public static function isNumber($value, $radix = null, $double = true)
	{
		if(\is_int($value) || \is_double($value))
		{
			return true;
		}
		else if($radix !== null)
		{
			if(!self::isRadix($radix))
			{
				throw new \Error('Invalid $radix argument');
			}

			return self::isNumeric($value, $radix, $double);
		}

		return false;
	}
	
	public static function isNumeric($value, $radix = KEKSE_NUMERIC_RADIX, $double = null)
	{
		if(!is_string($value))
		{
			return self::isNumber($value);
		}

		$len = strlen($value);

		if($len === 0 || $len > KEKSE_LIMIT_STRING)
		{
			return false;
		}
		else if(self::positiveRadix($radix) === 256)
		{
			return true;
		}

		$alpha;

		if(!($alpha = self::alphabet($radix)))
		{
			throw new \Error('Invalid $radix argument');
		}
		else if(!($value = self::prepareString($value, $radix, $double, false)))
		{
			return false;
		}

		if(Text::contains($alpha, '.', true) && $double)
		{
			return null;
		}

		$hadPoint = ($double === false ? null : false);
		$radix = strlen($alpha);
		$len = strlen($value);
		$byte;

		for($i = 0; $i < $len; ++$i)
		{
			if(!Text::contains($alpha, $value[$i], true))
			{
				if($value[$i] === '-')
				{
					continue;
				}
				else if($value[$i] === '.')
				{
					if($hadPoint !== false)
					{
						return false;
					}
					
					$hadPoint = true;
				}
				else
				{
					return false;
				}
			}
		}
		
		if($double === true && $hadPoint === false)
		{
			return false;
		}
		
		return true;
	}
		
	public static function parseInt($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::parse($string, $radix, false, $throw);
	}

	public static function parseDouble($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::parse($string, $radix, true, $throw);
	}

	public static function parseFloat($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::parseDouble($string, $radix, $throw);
	}

	public static function parseNumber($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::parse($string, $radix, null, $throw);
	}
	
	public static function parse($string, $radix = KEKSE_NUMERIC_RADIX, $double = null, $throw = KEKSE_THROW_NUMERIC)
	{
		if(!is_string($string))
		{
			if(self::isNumber($string))
			{
				if($double === true)
				{
					return (double)$string;
				}
				else if($double === false)
				{
					return (int)$string;
				}

				return self::cast($string);
			}
			else if($throw)
			{
				throw new \Error('Invalid $string argument');
			}

			return null;
		}

		$alpha;

		if(($alpha = self::alphabet($radix)) === null)
		{
			if($throw)
			{
				throw new \Error('Invalid $radix argument');
			}

			return null;
		}
		
		$byteRadix = (self::positiveRadix($radix) === 256);
		$negativeRadix = (is_int($radix) && $radix < 0);
		
		if($byteRadix || Text::contains($alpha, '.', true))
		{
			$double = false;
		}

		$string = self::prepareString($string, $radix, $double, true);
		$radix = strlen($alpha);
		$negative = ($string[0] === '-');
		
		if($negative)
		{
			$string = substr($string, 1);
		}
		
		if($string === '' || $string === '.')
		{
			if($double)
			{
				return 0.0;
			}
			
			return 0;
		}

		$split = explode('.', $string, ($double === false ? 1 : 2));
		$len = count($split);
		
		$lenInt = strlen($split[0]);
		$lenDouble = ($len > 1 ? strlen($split[1]) : 0);
		$result = 0;
		$pos;
		$b;
		
		//
		for($i = $lenInt - 1, $mul = 1; $i >= 0; --$i)
		{
			if($byteRadix)
			{
				$b = ord($split[0][$i]);

				if(!$negativeRadix)
				{
					$result += ($b * $mul);
				}
				else
				{
					$result += ((255 - $b) * $mul);
				}
			}
			else
			{
				$pos = strpos($alpha, $split[0][$i]);
				
				if($pos === false)
				{
					if($throw)
					{
						throw new \Exception('Character \'' . $split[0][$i] . '\' not found in alphabet for radix with len = ' . $radix);
					}

					break;
					//return null;
				}
				
				$result += ($mul * $pos);
			}
			
			$mul *= $radix;
		}
		
		if($lenDouble > 0)
		{
			$result = (double)$result;

			for($i = 0, $mul = (double)(1 / $radix); $i < $lenDouble; ++$i)
			{
				$pos = strpos($alpha, $split[1][$i]);
				
				if($pos === false)
				{
					if($throw)
					{
						throw new \Exception('Character \'' . $split[1][$i] . '\' not found in alphabet for radix with len = ' . $radix);
					}

					break;
					//return null;
				}
				
				$result += ($mul * $pos);
				$mul /= $radix;
			}
		}
		
		if($double === null)
		{
			$result = self::cast($result);//fmod()
		}
		else if($double)
		{
			$result = (double)$result;
		}
		else
		{
			$result = (int)$result;
		}
		
		if($negative)
		{
			$result = (0 - $result);
		}

		//
		return $result;
	}
	
	public static function renderInt($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::render($value, $radix, false, $throw);
	}

	public static function renderDouble($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::render($value, $radix, true, $throw);
	}

	public static function renderFloat($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::renderDouble($value, $radix, $throw);
	}

	public static function renderNumber($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_THROW_NUMERIC)
	{
		return self::render($value, $radix, null, $throw);
	}

	public static function render($value, $radix = KEKSE_NUMERIC_RADIX, $double = null, $throw = KEKSE_THROW_NUMERIC)
	{
		if(is_string($value))
		{
			if(self::isNumeric($value, $radix, $double))
			{
				return $value;
			}
			else if($throw)
			{
				throw new \Error('Invalid $value argument');
			}
			
			return null;
		}
		else if(!self::isNumber($value))
		{
			if($throw)
			{
				throw new \Error('Invalid $value argument');
			}

			return null;
		}
		else if(!is_bool($double))
		{
			$double = null;
		}

		$alpha;

		if(($alpha = self::alphabet($radix)) === null)
		{
			if($throw)
			{
				throw new \Error('Invalid $radix argument');
			}

			return null;
		}

		$byteRadix = (is_int($radix) && self::positiveRadix($radix) === 256);
		$negativeRadix = ($radix < 0);
		$radix = strlen($alpha);
		$negative = ($value < 0);
		if($negative) $value = abs($value);

		//
		$result = '';
		$rest = (double)$value;
		
		while($rest >= $radix)
		{
			$result = $alpha[(int)$rest % $radix] . $result;
			$rest /= $radix;
		}
		
		if($rest > 0)
		{
			$result = $alpha[(int)$rest] . $result;
		}

		if($double !== false && (($rest = (double)fmod((double)$value, 1)) != 0))
		{
			$zero = 0;
			$started = false;
			
			while((double)fmod($rest, 1) != 0)
			{
				$rest = (double)($rest * $radix);
				
				if(!$started && $rest < 1)
				{
					++$zero;
				}
				else
				{
					$started = true;
				}
			}
			
			$rest = str_repeat($alpha[0], $zero) . self::render($rest, $radix, $double, $throw);
			$len = strlen($rest);
			
			if(KEKSE_NUMERIC_ZERO_LIMIT > 0)
			{
				$count = 0;
				$cut = -1;

				for($i = 0; $i < $len; ++$i)
				{
					if($rest[$i] === $alpha[0])
					{
						if(++$count >= KEKSE_NUMERIC_ZERO_LIMIT)
						{
							$cut = $i;
							break;
						}
					}
					else
					{
						$count = 0;
					}
				}
				
				if($cut > -1)
				{
					$rest = substr($rest, 0, $cut - KEKSE_NUMERIC_ZERO_LIMIT + 1);
				}
			}
			
			$result .= '.' . $rest;
		}
		
		if($double === true)
		{
			if(!Text::contains($result, '.', true))
			{
				$result .= '.0';
			}
		}

		if($negative)
		{
			$result = '-' . $result;
		}

		return $result;
	}
	
	//?
	private static function numericSign($string, $noMinusInAlpha = true, $noPlusInAlpha = true)
	{
		if(!is_string($string))
		{
			throw new \Error('Invalid $string argument');
		}
		else if($string === '')
		{
			return [ '', false ];
		}
		else if(!($noMinusInAlpha || $noPlusInAlpha))
		{
			return [ $string, false ];
		}
		
		$result = '';
		$negative = false;
		$hadSigns = false;
		$len = strlen($string);

		for($i = 0; $i < $len; ++$i)
		{
			if($hadSigns)
			{
				$result .= $string[$i];
			}
			else if($string[$i] === '+' && $noPlusInAlpha)
			{
				continue;
			}
			else if($string[$i] === '-' && $noMinusInAlpha)
			{
				$negative = !$negative;
			}
			else
			{
				$result .= $string[$i];
				$hadSigns = true;
			}
		}

		return [ $result, $negative ];
	}
	
	private static function prepareString($string, $radix, $double = null, $filter = true)
	{
		if(!is_string($string))
		{
			throw new \Error('Invalid $string argument');
		}
		else if($string === '')
		{
			return '';
		}
		else if(strlen($string) > KEKSE_LIMIT_STRING)
		{
			return '';
		}
		else if(self::positiveRadix($radix) === 256)
		{
			return $string;
		}

		$alpha;
		
		if(($alpha = self::alphabet($radix)) === null)
		{
			throw new \Error('Invalid $radix argument');
		}
		else
		{
			$radix = strlen($alpha);
		}
		
		if(Text::contains($alpha, '.', true))
		{
			$double = false;
		}

		if(Text::isLowerCase($alpha))
		{
			$string = strtolower($string);
		}
		else if(Text::isUpperCase($alpha))
		{
			$string = strtoupper($string);
		}

		$negative;
		$noMinusInAlpha = !Text::contains($alpha, '-', true);
		$noPlusInAlpha = !Text::contains($alpha, '+', true);

		if(!$filter)
		{
			[ $string, $negative ] = Number::numericSign($string,
				$noMinusInAlpha, $noPlusInAlpha);

			if($negative && $string !== '')
			{
				return '-' . $string;
			}

			return $string;
		}

		$len = strlen($string);
		$pos = 0;

		for(; $pos < $len; ++$pos)
		{
			if(Text::contains($alpha, $string[$pos], true))
			{
				break;
			}
			else if($double !== false && $string[$pos] === '.')
			{
				break;
			}
			else if($string[$pos] === '-' || $string[$pos] === '+')
			{
				break;
			}
		}

		if($pos > 0)
		{
			$string = substr($string, $pos);
			$len -= $pos;
		}

		for($pos = $len - 1; $pos >= 0; --$pos)
		{
			if(Text::contains($alpha, $string[$pos], true))
			{
				break;
			}
			else if($string[$pos] === '-' || $string[$pos] === '+')
			{
				break;
			}
		}

		if($pos < ($len - 1))
		{
			$string = substr($string, 0, $pos + 1);
		}

		[ $string, $negative ] = Number::numericSign($string,
			$noMinusInAlpha, $noPlusInAlpha);

		if($string !== '')
		{
			$split = explode('.', $string, 2);
			$len = strlen($split[0]);
			$pos = 0;

			for(; $pos < $len; ++$pos)
			{
				if($split[0][$pos] !== $alpha[0])
				{
					break;
				}
			}

			$split[0] = substr($split[0], $pos);
			$count = count($split);

			if($count === 1)
			{
				$double = false;
			}
			else
			{
				if($double === null)
				{
					$double = true;
				}

				$len = strlen($split[1]);
				$pos = $len - 1;

				for(; $pos >= 0; --$pos)
				{
					if($split[1][$pos] !== $alpha[0])
					{
						break;
					}
				}
				
				if($pos < ($len - 1))
				{
					$split[1] = substr($split[1], 0, $pos + 1);
				}
				
				if(strlen($split[1]) === 0)
				{
					array_pop($split);
				}
			}
			
			$string = implode('.', $split);
			
			if($negative && $string !== '')
			{
				$string = '-' . $string;
			}
		}

		return $string;
	}
	
	public static function isRadix($radix)
	{
		if(is_string($radix))
		{
			$radix = Text::unique($radix);

			if(strlen($radix) >= 2)
			{
				return true;
			}
		}
		else if(is_int($radix))
		{
			if($radix < -257 || $radix > 256)
			{
				return false;
			}
		}
		else
		{
			return false;
		}

		return true;
	}
	
	public static function reverseRadix($radix)
	{
		if(is_string($radix))
		{
			return strrev($radix);
		}
		else if(!is_int($radix))
		{
			return null;
		}
		else if($radix < -257)
		{
			return null;
		}
		else if($radix > 256)
		{
			return null;
		}

		return (-1 - $radix);
	}
	
	public static function positiveRadix($radix)
	{
		if(is_string($radix))
		{
			return false;
		}
		else if(!is_int($radix))
		{
			return null;
		}
		else if($radix >= 0)
		{
			return $radix;
		}

		return self::reverseRadix($radix);
	}
	
	public static function negativeRadix($radix)
	{
		if(is_string($radix))
		{
			return false;
		}
		else if(!is_int($radix))
		{
			return null;
		}
		else if($radix < 0)
		{
			return $radix;
		}

		return self::reverseRadix($radix);
	}
	
	public static function alphabet($radix = KEKSE_NUMERIC_RADIX)
	{
		if(is_int($radix))
		{
			if($radix < -257 || $radix > 256)
			{
				return null;
			}

			$negative = ($radix < 0);
			$radix = self::positiveRadix($radix);
			$result;

			if($radix === 0)
			{
				$result = KEKSE_ALPHABET_ALPHA;
			}
			else if($radix === 1)
			{
				$result = KEKSE_ALPHABET_LOWER;
			}
			else if($radix <= 36)
			{
				$result = substr(KEKSE_ALPHABET_REGULAR, 0, $radix);
			}
			else if($radix <= 62)
			{
				$result = substr(KEKSE_ALPHABET_EXTENDED, 0, $radix);
			}
			else
			{
				$result = '';
				$prefer = 0;
				$cont;

				for($i = 0; $i < $radix; ++$i)
				{
					$cont = false;

					if($radix < 256) switch($i)
					{
						case 46:
							++$prefer;
							$cont = true;
							break;
						case 45:
							if($radix < 255)
							{
								++$prefer;
								$cont = true;
							}
							break;
						case 43:
							if($radix < 254)
							{
								++$prefer;
								$cont = true;
							}
							break;
					}

					if(!$cont)
					{
						$result .= chr($i);
					}
				}

				for($i = 0; $i < $prefer; ++$i)
				{
					$result .= chr(256 - $prefer + $i);
				}
			}

			if($negative)
			{
				return strrev($result);
			}

			return $result;
		}
		else if(is_string($radix))
		{
			$radix = Text::unique($radix);

			if(strlen($radix) < 2)
			{
				return null;
			}

			return $radix;
		}
		else
		{
			return null;
		}
	}

	public static function randomInt($max = null, $min = 0)
	{
		if(!is_int($max))
		{
			$max = mt_getrandmax();
		}

		if(!is_int($min))
		{
			$min = 0;
		}

		return mt_rand((int)$min, (int)$max);
	}

	public static function randomDouble($max = null, $min = 0)
	{
		if(!self::isNumber($max))
		{
			$max = mt_getrandmax();
		}

		if(!self::isNumber($min))
		{
			$min = 0;
		}

		$result = (double)self::randomInt($max, $min);
		$result += self::random();

		return $result;
	}

	public static function randomFloat($max = null, $min = 0)
	{
		return self::randomDouble($max, $min);
	}

	public static function randomNumber($max = null, $min = 0)
	{
		if(!self::isNumber($max))
		{
			$max = mt_getrandmax();
		}

		if(!self::isNumber($min))
		{
			$min = 0;
		}

		$result = self::randomDouble($max, $min);

		if(fmod($result, 1) == 0)
		{
			$result = (int)$result;
		}

		return $result;
	}

	public static function random()
	{
		return ((double)mt_rand() / (double)mt_getrandmax());
	}
}

//
function is_number($value, $radix = null, $double = true)
{
	return Number::isNumber($value, $radix, $double);
}

function is_numeric($value, $radix = KEKSE_NUMERIC_RADIX, $double = null)
{
	return Number::isNumeric($value, $radix, $double);
}

//
?>
