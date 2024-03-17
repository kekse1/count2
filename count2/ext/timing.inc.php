<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

function timestamp()
{
	if(PHP_INT_SIZE < 8) return time();
	$result = explode(' ', microtime());
	return (intval($result[1] * 1E3) + intval(round($result[0] * 1E3)));
}

?>
