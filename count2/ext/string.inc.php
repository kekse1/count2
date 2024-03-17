<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

define('KEKSE_LIMIT_STRING', 255);

//
function isValidString($string)
{
	if(!is_string($string)) return null;
	return (strlen($string) <= KEKSE_LIMIT_STRING);
}

function checkString($string)
{
	if(!is_string($string)) return null;
	else if(strlen($string) > KEKSE_LIMIT_STRING) return null;
	return $string;
}

function removeBinary($string, $null = false)
{
	$len = strlen($string);
	if($len > KEKSE_LIMIT_STRING) return null;

	$result = '';
	
	if($len > 0)
	{
		$byte;

		for($i = 0; $i < $len; ++$i)
		{
			if(($byte = ord($string[$i])) > 31 && $byte !== 127)
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

?>
