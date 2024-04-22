<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
function parseJSON($value)
{
	return json_decode($value, true, KEKSE_LIMIT_JSON);
}

function renderJSON($string)
{
	return json_encode($string, 0, KEKSE_LIMIT_JSON);
}

function timestamp($diff = null)
{
	if(PHP_INT_SIZE < 8)
	{
		if(is_int($diff))
		{
			return (time() - $diff);
		}
		
		return time();
	}
	
	$result = explode(' ', microtime());
	$result = (intval($result[1] * 1E3) + intval(round($result[0] * 1E3)));
	
	if(is_int($diff))
	{
		return ($result - $diff);
	}
	
	return $result;
}

//
?>