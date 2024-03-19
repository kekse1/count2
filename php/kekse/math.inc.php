<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

const SIZE_UNITS_1024 = [ 'Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB' ];
const SIZE_UNITS_1000 = [ 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ];

function renderSize($bytes, $precision = 2, $base = 1024)
{
	$max = count(SIZE_UNITS_1024);
	$rest = $bytes;
	$index = 0;

	while($rest >= $base)
	{
		$rest /= $base;
		if(++$index >= ($max - 1)) break;
	}

	$result = round($rest, $precision);
	return ($result . ' ' . ($base === 1024 ? SIZE_UNITS_1024[$index] : SIZE_UNITS_1000[$index]));
}

function unit($string, $float = false)
{
	throw new Error('TODO (old: `function unit()` @ count.php)');
}

?>
