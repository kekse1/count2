<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
const KEKSE_CAMEL = '-';
const KEKSE_CAMEL_FIX = true;

//
require_once(__DIR__ . '/text.php');

//
class CamelCase
{
	public static function isCamelCase($string, $camel = KEKSE_CAMEL)
	{
		if(!is_string($string))
		{
			return null;
		}
		else if($string === '')
		{
			return null;
		}
		else if(str_contains($string, $camel . $camel))
		{
			return null;
		}
		else if(str_contains($string, $camel))
		{
			return false;
		}
		else if(!Text::isLowerCase($string))
		{
			return true;
		}

		return null;
	}

	public static function enable($string, $camel = KEKSE_CAMEL, $fix = KEKSE_CAMEL_FIX)
	{
		if(self::isCamelCase($string, $camel) !== false)
		{
			return $string;
		}

		$split = explode($camel, $string);
		$result = array_shift($split);

		foreach($split as $sub)
		{
			if($sub !== '')
			{
				if($fix)
				{
					$result .= strtoupper($sub[0]) . strtolower(substr($sub, 1));
				}
				else
				{
					$result .= strtoupper($sub[0]) . substr($sub, 1);
				}
			}
		}

		return $result;
	}

	public static function disable($string, $camel = KEKSE_CAMEL, $fix = KEKSE_CAMEL_FIX)
	{
		if(self::isCamelCase($string, $camel) !== true)
		{
			return $string;
		}

		$result = '';
		$lastWasUpper = null;
		$len = strlen($string);

		for($i = 0; $i < $len; ++$i)
		{
			if(Text::isUpperCase($string[$i]))
			{
				if($lastWasUpper)
				{
					$result .= $string[$i];
				}
				else if($fix && $i < ($len - 1))
				{
					if(Text::isUpperCase($string[$i + 1]))
					{
						$result .= $camel . $string[$i];
					}
					else
					{
						$result .= $camel . strtolower($string[$i]);
					}
				}
				else
				{
					$result .= $camel . strtolower($string[$i]);
				}

				$lastWasUpper = true;
			}
			else
			{
				$lastWasUpper = false;
				$result .= $string[$i];
			}
		}

		return $result;
	}
}

//
?>
