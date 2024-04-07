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

?>
