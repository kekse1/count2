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
function parse($string, $radix = 10, $float = true)
{
}

function render($value, $radix = 10, $float = true)
{
}

//

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
//TODO/"strrev()" @ https://www.php.net/strrev
//
function getAlphabet($radix = 10)
{
	if(is_int($radix))
	{
		if($radix < -257 || $radix > 256)
		{
			return null;
		}

		//
		//TODO/
		//
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
