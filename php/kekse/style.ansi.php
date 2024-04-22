<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/style.php');

//
class ANSI implements Style
{
	private static $SEQ = [
		'delim' => "\0",
		'escape' => "\e",
		
		'none' => '[0m',
		
		'bold' => '[1m',
		'faint' => '[2m',
		'italic' => '[3m',
		'underline' => '[4m',
		'blink' => '[5m',
		'inverse' => '[7m',
		'hidden' => '[8m',
		'strike' => '[9m',
		
		'fg' => '[38;2;%03d;%03d;%03dm',
		'bg' => '[48;2;%03d;%03d;%03dm'
	];

	private static function sequence($type)
	{
		if(!is_string($type))
		{
			throw new \Exception('Invalid $type argument (not a String)');
		}
		else if(!($type = strtolower($type)))
		{
			return '';
		}
		
		if(!isset(self::$SEQ[$type]))
		{
			return '';
		}
		
		return self::$SEQ[$type];
	}
	
	public static function text($type, $close = null, $string = null, ... $params)
	{
		if(!is_string($type) || $type === '')
		{
			throw new \Exception('Invalid $type argument (no valid String)');
		}
		
		$seq = self::sequence($type);
		$isString = is_string($string);
		
		if(!$seq)
		{
			return ($isString ? $string : '');
			//throw new \Exception('Invalid $type argument (sequence unknown)');
		}
		else if(!is_bool($close))
		{
			$close = $isString;
		}
		
		$result = self::sequence('escape');
		$result .= sprintf($seq, ... $params);
		$result .= self::sequence('delim');
		
		if($isString)
		{
			$result .= $string;
		}
		
		if($close)
		{
			$result .= self::none();
		}
		
		return $result;
	}
	
	public static function none()
	{
		return (self::sequence('escape') . self::sequence('none') . self::sequence('delim'));
	}
	
	public static function bold($string, $close = null)
	{
		return self::text('bold', $close, $string);
	}
	
	public static function faint($string, $close = null)
	{
		return self::text('faint', $close, $string);
	}
	
	public static function italic($string, $close = null)
	{
		return self::text('italic', $close, $string);
	}
	
	public static function underline($string, $close = null)
	{
		return self::text('underline', $close, $string);
	}
	
	public static function blink($string, $close = null)
	{
		return self::text('blink', $close, $string);
	}
	
	public static function inverse($string, $close = null)
	{
		return self::text('inverse', $close, $string);
	}

	public static function hidden($string, $close = null)
	{
		return self::text('hidden', $close, $string);
	}
	
	public static function strike($string, $close = null)
	{
		return self::text('strike', $close, $string);
	}
	
	public static function fg($string, $red, $green, $blue, $close = null)
	{
		return self::text('fg', $close, $string, $red, $green, $blue);
	}
	
	public static function bg($string, $red, $green, $blue, $close = null)
	{
		return self::text('bg', $close, $string, $red, $green, $blue);
	}
	
	public static function color($string, $fg = null, $bg = null, $close = null)
	{
		if($fg === null && $bg === null)
		{
			return (is_string($string) ? $string : '');
		}
		
		$result = '';
		
		try
		{
			if($fg !== null) $result .= self::fg(null, $fg[0], $fg[1], $fg[2], false);
			if($bg !== null) $result .= self::bg(null, $bg[0], $bg[1], $bg[2], false);
		}
		catch(\Throwable $err)
		{
			throw new \Error('Couldn\'t apply colors; maybe wrong $fg or/and $bg argument (expecting arrays)?');
		}
		
		if(is_string($string))
		{
		 	$result .= $string;

		 	if(!is_bool($close))
		 	{
		 		$close = true;
		 	}
		}
		else if(!is_bool($close))
		{
			$close = false;
		}
		
		if($close)
		{
			$result .= self::none();
		}
		
		return $result;
	}
	
	public static function style($string, ... $styles)
	{
		$result = '';
		$close = null;
		$fg = null;
		$bg = null;
		$count = count($styles);

		for($i = 0; $i < $count; ++$i)
		{
			if($styles[$i] === null)
			{
				if($fg === null)
				{
					$fg = true;
					array_splice($styles, $i--, 1);
					--$count;
				}
				else
				{
					throw new \Error('Foreground array is already defined, so a (null) parameter doesn\'t make any sense.');
				}
			}
			else if(is_bool($styles[$i]))
			{
				$close = array_splice($styles, $i--, 1)[0];
				--$count;
			}
			else if(is_string($styles[$i]))
			{
				if($styles[$i] === '')
				{
					array_splice($styles, $i--, 1);
					--$count;
				}
				else
				{
					$style;
				
					if(!($style = self::sequence($styles[$i])))
					{
						array_splice($styles, $i--, 1);
						--$count;
					}
					else
					{
						$styles[$i] = self::sequence('escape') . $style . self::sequence('delim');
					}
				}
			}
			else if(is_array($styles[$i]))
			{
				if($fg === null)
				{
					$fg = array_splice($styles, $i--, 1)[0];
				}
				else if($bg === null)
				{
					$bg = array_splice($styles, $i--, 1)[0];
				}
				else
				{
					throw new \Error('Too many arrays given (only need [ fg, bg ])');
				}
					
				--$count;
			}
			else
			{
				throw new \Error('Invalid ...$styles[' . (string)$i . '] argument');
			}
		}
		
		if($fg === true)
		{
			$fg = null;
		}
		
		if($fg !== null || $bg !== null) try
		{
			if($fg !== null)
			{
				$result .= self::fg(null, $fg[0], $fg[1], $fg[2], false);
			}
			
			if($bg !== null)
			{
				$result .= self::bg(null, $bg[0], $bg[1], $bg[2], false);
			}
		}
		catch(\Throwable $err)
		{
			throw new \Error('Unable to apply at least one of your $fg/$bg color parameters');
		}

		foreach($styles as $style)
		{
			$result .= $style;
		}
	
		$isString = is_string($string);
		
		if(!is_bool($close))
		{
			$close = $isString;
		}
		
		if($isString)
		{
			$result .= $string;
		}
		
		if($close)
		{
			$result .= self::none();
		}

		return $result;
	}
}

//
?>
