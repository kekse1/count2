<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
//THIS IS JUST A SIMPLE "STYLE ENGINE", w/ `style.{html,ansi}.php`;
//NO any real "big thing", and `style.html.php` is merely to complete this a bit..
//'d be better to implement smth. better, *l8rs*! ..
//
interface Style
{
	public static function text($type, $close = null, $string = null, ... $params);
	public static function style($string, ... $styles);
}

//
?>
