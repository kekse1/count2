<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//
function str_unique($string)
{
	if(!is_string($string))
	{
		return null;
	}

	$result = '';
	$len = strlen($string);

	for($i = 0; $i < $len; ++$i)
	{
		if(!str_contains($result, $string[$i]))
		{
			$result .= $string[$i];
		}
	}

	return $result;
}

function str_trim($string)
{
	if(!is_string($string))
	{
		return null;
	}

	$result = '';
	$len = strlen($string);
	$start = -1;
	$end = -1;
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
	
	for($i = $len - 1; $i >= 0; --$i)
	{
		$byte = ord($string[$i]);
		
		if($byte > 32 && $byte !== 127)
		{
			$end = $i;
			break;
		}
	}
	
	if($start === -1 || $end === -1)
	{
		return '';
	}
	
	return substr($string, $start, $end - $start + 1);
}

function str_remove_binary($string, $whiteSpaces = false)
{
	if(!is_string($string))
	{
		return null;
	}
	
	$len = strlen($string);
	$result = '';
	
	$byte; for($i = 0; $i < $len; ++$i)
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

function str_remove_spaces($string)
{
	return str_remove_binary($string, true);
}

function str_contains_binary($string)
{
	if(!is_string($string))
	{
		return null;
	}

	$len = strlen($string);
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

?>
