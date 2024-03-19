<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('ext/quant.inc.php');//TODO/
require_once('ext/terminal.inc.php');

class ANSI extends Quant
{
	public function __construct(... $args)
	{
		if(!self::isTTY())
		{
			throw new \Error('Not allowed since PHP doesn\'t run in TTY mode!');
		}

		return parent::__construct('ANSI', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function reset()
	{
		return self::getSequence('reset');
	}

	public static function fg($red, $green, $blue)
	{
	}

	public static function bg($red, $green, $blue)
	{
	}

	public static function bold()
	{
		return self::getSequence('bold');
	}

	public static function underline()
	{
		return self::getSequence('underline');
	}

	public static function getSequence($type)
	{
		if(is_string($type)) switch($type)
		{
			case 'escape': return "\e";
			case 'reset': return '[0m';
			case 'bold': return '[1m';
			case 'reset_bold': return '[22m';
			case 'faint': return '[2m';
			case 'reset_faint': return '[22m';
			case 'italic': return '[3m';
			case 'reset_italic': return '[23m';
			case 'underline': return '[4m';
			case 'reset_underline': return '[24m';
			case 'reset_color': return '[39m';
			case 'red': return '[31m';
			case 'green': return '[32m';
			case 'yellow': return '[33m';
			case 'blue': return '[34m';
			case 'magenta': return '[35m';
		}

		return '';
	}

	public static function strlen($string)
	{
throw new \Error('TODO');
	}

	public static function less($string)
	{
throw new \Error('TODO');
	}
}

?>
