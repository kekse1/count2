<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//
function parse($string, $radix, $float = true)
{
}

function render($value, $radix, $float = true)
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
function getAlphabet($radix)
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
?>
