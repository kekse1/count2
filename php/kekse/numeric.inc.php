<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//
function is_number($value)
{
	return (is_int($value) || is_double($value));
}

//
function parse($string, $radix = 10, $double = true, $throw = true)
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
	else if(positiveRadix($radix) === 10)
	{
		$string = str_prepare_decimal($string, $double);
	}
	else
	{
		$string = str_prepare_parse($string, $alpha, $double);
	}

	$radix = strlen($alphabet);
		
	//
}

function render($value, $radix = 10, $double = true, $throw = true)
{
	if(!is_number($value))
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

	//
}

//
function str_prepare_parse($string, $alphabet, $double = true)
{
	if(!is_string($string))
	{
		throw new \Error('Invalid $string argument');
	}
	else if($string === '')
	{
		return $alphabet[0];
	}
	else if(!str_contains_binary($alphabet))
	{
		$string = str_trim($string);
	}

	if(str_contains($alphabet, '.'))
	{
		$double = false;
	}

	$result = '';
	$len = strlen($string);
	$hadPoint = false;
	$byte;

	for($i = 0; $i < $len; ++$i)
	{
		$byte = ord($string[$i]);

		if(str_contains($alphabet, $string[$i]))
		{
			$result .= $string[$i];
		}
		else if($byte === 46)
		{
			if($double)
			{
				if($hadPoint)
				{
					break;
				}
				else
				{
					$hadPoint = true;
				}
			}
			else
			{
				break;
			}
		}
		else
		{
			break;
		}
	}

	if($result === '')
	{
		return $alphabet[0];
	}
	else if(!str_contains($alphabet, '.'))
	{
		if($result[0] === '.')
		{
			if(!$double)
			{
				return '0';
			}

			return $alphabet[0] . $result;
		}
	}

	return $result;
}

function str_prepare_decimal($string, $double = true)
{
	if(!is_string($string))
	{
		return null;
	}
	else
	{
		$string = str_trim($string);
	}

	$len = strlen($string);

	if($len === 0)
	{
		return '0';
	}
	else if($len > KEKSE_LIMIT_STRING)
	{
		return null;
	}

	$byte = ord($string[0]);

	if($byte < 48 || $byte > 57)
	{
		return '0';
	}

	$result = '';
	$hadPoint = false;

	for($i = 0; $i < $len; ++$i)
	{
		$byte = ord($string[$i]);

		if($byte === 46)
		{
			if($double)
			{
				if($hadPoint)
				{
					break;
				}
				else
				{
					$hadPoint = true;
				}
			}
			else
			{
				break;
			}
		}
		else if($byte < 48 || $byte > 57)
		{
			break;
		}

		$result .= chr($byte);
	}

	if($result === '')
	{
		return '0';
	}
	else if($result[0] === '.')
	{
		return '0' . $result;
	}
	
	return $result;
}

/*

	here's the 'radix.js' code from my javascript library part (https://github.com/kekse1/v4/):


radix.features = (_radix, _throw = DEFAULT_THROW) => {
	const alpha = alphabet.getAlphabet(_radix, _throw);
	if(!alpha) return null; const result = Object.create(null);
	result.alphabet = alpha;
	result.radix = _radix;
	result.length = result.alphabet.length;
	result.bytes = (radix.toPositive(_radix) === 256);
	result.bigint = (!result.bytes && !alpha.includes('n'));
	result.float = (!result.bytes && !alpha.includes('.'));
	result.negative = (!result.bytes && !alpha.includes('-'));
	result.positive = (!result.bytes && !alpha.includes('+'));
	result.sign = (!result.bytes && (result.negative || result.positive));
	result.lower = alpha.isLowerCase;
	result.upper = (alpha.isUpperCase && !alpha.isLowerCase);
	result.exp = null;
	return result; };

//
radix.checkSigns = (_string, _negative = true, _positive = true) => { var i = 0, negative = false;
	while((_positive && _string[i] === '+') || (_negative && _string[i] === '-')) {
		if(_string[i] === '-') negative = !negative; ++i; }
	if(i > 0) _string = _string.substr(i); return [ negative, _string ]; };

//
radix.parse = (_string, _radix = DEFAULT_RADIX, _bigint = null, _float = true, _throw = DEFAULT_THROW) => {
	if(typeof _string !== 'string') return (numeric(_value) ? _value : (_throw ? error('Invalid % argument', null, '_string') : null));
	const features = radix.features(_radix, _throw); if(!features) return (_throw ? error('Invalid % argument', null, '_radix') : undefined);
	if(!features.float) _float = false; if(features.bigint && _string[_string.length - 1] === 'n') { if(typeof _bigint !== 'boolean') _bigint = true;
		if(_bigint) _string = _string.slice(0, -1); } if(features.lower) _string = _string.toLowerCase(); else if(features.upper) _string = _string.toUpperCase();
	var negative = false; if(features.sign) { negative = radix.checkSigns(_string, features.negative, features.positive); _string = negative.pop();
	negative = negative[0]; } if(_bigint) _float = null; else if(!features.float) _float = false; const split = (!features.float ? [ _string ] : _string.split('.'));
	if(split.length > 2) return (_throw ? error('Too many decimal points') : null); else if(split.length > 1 && !_float) split.length = 1; var rem = 0;
	if(split.length > 1) { while(split[1][split[1].length - 1 - rem] === features.alphabet[0]) ++rem; if(rem > 0) split[1] = split[1].slice(0, -rem);
	if(split[1].length === 0) { split.length = 1; _float = false; }} else if(typeof _bigint !== 'boolean') _bigint = false; var rem = 0;
	while(split[0][rem] === features.alphabet[0]) ++rem; if(rem > 0) split[0] = split[0].substr(rem); if(split.length === 1 && split[0].length === 0)
	return (_bigint ? 0n : 0); else _radix = features.alphabet.length;
	if(_bigint) _radix = BigInt(_radix); var result = (_bigint ? 0n : 0); var mul = (_bigint ? 1n : 1); var idx; for(var i = split[0].length - 1; i >= 0; --i) {
		if((idx = features.alphabet.indexOf(split[0][i])) === -1) return (_throw ? error('Alphabet doesn\'t contain `%` character', null, split[0][i]) : null);
		else if(_bigint) idx = BigInt(idx); result += (idx * mul); mul *= _radix; }
	if(_float && split.length > 1) { var floats = 0; mul = 1 / _radix; for(var i = 0; i < split[1].length; ++i) {
		if((idx = features.alphabet.indexOf(split[1][i])) === -1) return (_throw ? error('Alphabet doesn\'t contain `%` character', null, split[1][i]) : null);
		else floats += (idx * mul); mul /= _radix; } result += floats; } if(negative) return -result; return result; };

radix.render = (_value, _radix = DEFAULT_RADIX, _bigint_suffix = DEFAULT_BIGINT_SUFFIX, _float = true, _throw = DEFAULT_THROW) => {
	if(typeof _value === 'bigint') { if(typeof _bigint_suffix === 'boolean') _bigint_suffix = (_bigint_suffix ? 'n' : '');
		else if(typeof _bigint_suffix !== 'string') _bigint_suffix = 'n'; }
	if(!numeric(_value)) return (typeof _value === 'string' ? _value : (_throw ? error('Invalid % argument', null, '_value') : null));
	const features = radix.features(_radix, _throw); if(!features) return (_throw ? error('Invalid % argument', null, '_radix') : undefined);
	else if(!features.bigint) _bigint_suffix = ''; const bigint = (typeof _value === 'bigint'); var rest = Math.abs(_value);
	_radix = features.alphabet.length; if(bigint) _radix = BigInt(_radix); if(rest === 0 || rest === 0n) return (features.alphabet[0] + (bigint ? _bigint_suffix : ''));
	var result = ''; var idx; while(rest >= _radix) { result = features.alphabet[Math._floor(Number(rest % _radix))] + result;
	rest /= _radix; } if(rest > (bigint ? 0n : 0)) result = features.alphabet[Math._floor(Number(rest))] + result; if(!bigint && _float) { rest = (Math.abs(_value) % 1);
	if(rest > 0) { var zero = 0, started = false; while((rest % 1) !== 0) { rest *= _radix; if(!started && rest < 1) ++zero; else started = true; }
	result += ('.' + String.repeat(zero, features.alphabet[0]) + radix.render(rest, _radix, null, true, _throw)); }}
	return ((_value < 0 ? '-' : '') + result + ((bigint && features.bigint) ? _bigint_suffix : '')); };


 */

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
const KEKSE_ALPHABET_DECIMAL = '0123456789';
const KEKSE_ALPHABET_LOWER = 'abcdefghijklmnopqrstuvwxyz';
const KEKSE_ALPHABET_UPPER = 'ABCDEFGHIJKLMONPQRSTUVWXYZ';
const KEKSE_ALPHABET_REGULAR = KEKSE_ALPHABET_DECIMAL . KEKSE_ALPHABET_LOWER;
const KEKSE_ALPHABET_EXTENDED = KEKSE_ALPHABET_REGULAR . KEKSE_ALPHABET_UPPER;
const KEKSE_ALPHABET_ALPHA = KEKSE_ALPHABET_LOWER . KEKSE_ALPHABET_UPPER;

//
?>
