<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
interface Style
{
	public static function text($type, $close = null, $string = null, ... $params);
	public static function style($string, ... $styles);
}

//
?>
