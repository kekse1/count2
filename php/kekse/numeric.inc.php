<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
const DEFAULT_NUMERIC_THROW = true;

//
const KEKSE_ALPHABET_DECIMAL = '0123456789';
const KEKSE_ALPHABET_LOWER = 'abcdefghijklmnopqrstuvwxyz';
const KEKSE_ALPHABET_UPPER = 'ABCDEFGHIJKLMONPQRSTUVWXYZ';
const KEKSE_ALPHABET_REGULAR = KEKSE_ALPHABET_DECIMAL . KEKSE_ALPHABET_LOWER;
const KEKSE_ALPHABET_EXTENDED = KEKSE_ALPHABET_REGULAR . KEKSE_ALPHABET_UPPER;
const KEKSE_ALPHABET_ALPHA = KEKSE_ALPHABET_LOWER . KEKSE_ALPHABET_UPPER;

//
function parseInt($string, $radix = 10, $throw = DEFAULT_NUMERIC_THROW)
{
	return parse($string, $radix, false, $throw);
}

function parseDouble($string, $radix = 10, $throw = DEFAULT_NUMERIC_THROW)
{
	return parse($string, $radix, true, $throw);
}

function parseFloat($string, $radix = 10, $throw = DEFAULT_NUMERIC_THROW)
{
	return parseDouble($string, $radix, $throw);
}

function parse($string, $radix = 10, $double = null, $throw = DEFAULT_NUMERIC_THROW)
{
	if(!is_string($string))
	{
		if($throw)
		{
			throw new \Error('Invalid $string argument');
		}

		return null;
	}

	$alpha;

	if(($alpha = alphabet($radix)) === null)
	{
		if($throw)
		{
			throw new \Error('Invalid $radix argument');
		}

		return null;
	}
	else if(str_contains($alpha, '.'))
	{
		$double = false;
	}

	$string = str_prepare_numeric($string, $radix, $double);
	$radix = strlen($alpha);

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

	//
	$negative = (($split[0] !== '' && $split[0][0]) === '-');
	
	if($negative)
	{
		$split[0] = substr($split[0], 1);
		--$len;
	}

	$lenInt = strlen($split[0]);
	$lenDouble = ($len > 1 ? strlen($split[1]) : 0);
	$result = 0;
	$pos;
	
	//
	for($i = $lenInt - 1, $mul = 1; $i >= 0; --$i)
	{
		$pos = strpos($alpha, $split[0][$i]);
		
		if($pos === false)
		{
			if($throw)
			{
				throw new \Exception('Character \'' . $split[0][$i] . '\' not found in alphabet for radix with len = ' . $radix);
			}
			
			return null;
		}
		
		$result += ($mul * $pos);
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
				
				return null;
			}
			
			$result += ($mul * $pos);
			$mul /= $radix;
		}
	}
	
	if($double === null)
	{
		if(fmod($result, 1) == 0)
		{
			$result = (int)$result;
		}
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

function render($value, $radix = 10, $double = null, $throw = DEFAULT_NUMERIC_THROW)
{
	$alpha;

	if(($alpha = alphabet($radix)) === null)
	{
		if($throw)
		{
			throw new \Error('Invalid $radix argument');
		}

		return null;
	}
	else
	{
		$radix = strlen($alpha);
	}

	if(is_string($value))
	{
		if(is_numeric($value, $radix))
		{
			return $value;
		}
		else if($throw)
		{
			throw new \Error('Invalid $value argument');
		}
		
		return null;
	}
	else if(!is_number($value))
	{
		if($throw)
		{
			throw new \Error('Invalid $value argument');
		}

		return null;
	}
	else if(is_int($value))
	{
		$double = false;
	}

	//
}

//
function is_number($value, $radix = null)
{
	if(is_int($value) || is_double($value))
	{
		return true;
	}
	else if($radix === null)
	{
		return false;
	}
	
	return is_numeric($value, $radix);
}

function is_numeric($value, $radix = 10)
{
	if(!is_string($value))
	{
		return is_number($value);
	}
	else if(positiveRadix($radix) === 256)
	{
		return true;
	}

	$alpha;

	if(($alpha = alphabet($radix)) === null || $radix === 10 || $alpha === KEKSE_ALPHABET_DECIMAL)
	{
		return \is_numeric($value);
	}
	else if(!str_contains_binary($alpha))
	{
		$value = str_trim($value);
	}

	$radix = strlen($alpha);
	$len = strlen($value);
	$hadPoint;
	$byte;
	
	if(str_contains($alpha, '.'))
	{
		$hadPoint = null;
	}
	else
	{
		$hadPoint = false;
	}
	
	for($i = 0; $i < $len; ++$i)
	{
		if(!str_contains($alpha, $value[$i]))
		{
			if($value[$i] === '.' && $hadPoint === false)
			{
				$hadPoint = true;
			}
			else
			{
				return false;
			}
		}
	}
	
	return true;
}

function str_numeric_sign($string, $noMinusInAlpha = true, $noPlusInAlpha = true)
{
	if(!is_string($string))
	{
		throw new \Error('Invalid $string argument');
	}
	else if(!($noMinusInAlpha || $noPlusInAlpha))
	{
		return [ $string, false ];
	}
	
	$len = strlen($string);
	$negative = false;
	$pos = 0;
	
	for(; $pos < $len; ++$pos)
	{
		if($noPlusInAlpha && $string[$pos] === '+')
		{
			continue;
		}
		else if($noMinusInAlpha && $string[$pos] === '-')
		{
			$negative = !$negative;
		}
		else
		{
			break;
		}
	}

	if($pos > 0)	
	{
		$string = substr($string, $pos);
	}
	
	return [ $string, $negative ];
}

function str_prepare_numeric($string, $radix, $double = null)
{
	if(!is_string($string))
	{
		throw new \Error('Invalid $string argument');
	}

	$alpha;
	$dec;
	
	if(($alpha = alphabet($radix)) === null)
	{
		throw new \Error('Invalid $radix argument');
	}
	else if(($radix = strlen($alpha)) === 10 && $alpha === KEKSE_ALPHABET_DECIMAL)
	{
		$dec = true;
	}
	else
	{
		$dec = false;
	}
	
	$noMinusInAlpha = !str_contains($alpha, '-');
	$noPlusInAlpha = !str_contains($alpha, '+');

	$r = str_numeric_sign($string, $noMinusInAlpha, $noPlusInAlpha);
	$string = $r[0];
	$negative = $r[1];

	if($string === '')
	{
		return '';
	}
	else if(strlen($string) > KEKSE_LIMIT_STRING)
	{
		return '';
	}
	else if(!str_contains_binary($alpha))
	{
		$string = str_trim($string);
	}
	
	if(str_is_lower($alpha))
	{
		$string = strtolower($string);
	}
	else if(str_is_upper($alpha))
	{
		$string = strtoupper($string);
	}
	
	$withDouble;
	
	if(str_contains($alpha, '.'))
	{
		$withDouble = !($double = false);
	}
	else
	{
		$withDouble = false;
	}

	$hadPoint = ($double !== false ? false : null);
	$len = strlen($string);
	$str = $string;
	$string = '';
	$l = 0;
	
	for($i = 0; $i < $len; ++$i)
	{
		if($str[$i] === '.')
		{
			if($hadPoint === false)
			{
				$hadPoint = true;
			}
			else
			{
				break;
			}
		}
		
		$string .= $str[$i];
		++$l;
	}

	$str = $string;
	$string = '';
	$len = $l;
	$l = 0;
	
	for($i = 0; $i < $len; ++$i)
	{
		if($str[$i] === '.')
		{
			$string .= '.';
			++$l;
		}
		else if($dec)
		{
			$byte = ord($str[$i]);
			
			if($byte >= 48 && $byte <= 57)
			{
				$string .= chr($byte);
				++$l;
			}
			else if($l > 0)
			{
				break;
			}
		}
		else if(str_contains($alpha, $str[$i]))
		{
			$string .= $str[$i];
			++$l;
		}
		else if($l > 0)
		{
			break;
		}
	}

	if($l === 0)
	{
		$string = '';
	}
	else
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
		
		if($negative)
		{
			$string = '-' . $string;
		}
	}

	return $string;
}

//
function isRadix($radix)
{
	if(is_string($radix))
	{
		$radix = str_unique($radix);

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

function reverseRadix($radix)
{
	if(!is_int($radix))
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

function positiveRadix($radix)
{
	if(!is_int($radix))
	{
		return null;
	}
	else if($radix >= 0)
	{
		return $radix;
	}

	return reverseRadix($radix);
}

function negativeRadix($radix)
{
	if(!is_int($radix))
	{
		return null;
	}
	else if($radix < 0)
	{
		return $radix;
	}

	return reverseRadix($radix);
}

//
function alphabet($radix = 10)
{
	if(is_int($radix))
	{
		if($radix < -257 || $radix > 256)
		{
			return null;
		}

		$negative = ($radix < 0);
		$radix = positiveRadix($radix);
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
		$radix = str_unique($radix);

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

//
?>
