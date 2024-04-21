<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/format.php');

//
class ANSI extends Format
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

	public static function getSequence($type)
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
	
	public static function text($type, $close = null, $string, ... $params)
	{
		if(!is_string($type) || $type === '')
		{
			throw new \Exception('Invalid $type argument (incorrect sequence)');
		}
		
		$seq = self::getSequence($type);
		
		if(!$seq)
		{
			return (is_string($string) ? $string : '');
			//throw new \Exception('Invalid $type argument (sequence unknown)');
		}
		
		$isString = is_string($string);
		if(!is_bool($close)) $close = $isString;
		
		$result = self::getSequence('escape');
		$result .= sprintf($seq, ... $params);
		$result .= self::getSequence('delim');
		
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
		return (self::getSequence('escape') . self::getSequence('none') . self::getSequence('delim'));
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
	
	public static function color($string, $fg, $bg, $close = null)
	{
		$result = '';
		
		try
		{
			$result .= self::fg(null, $fg[0], $fg[1], $fg[2], false);
			$result .= self::bg(null, $bg[0], $bg[1], $bg[2], false);
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
			if(is_bool($styles[$i]))
			{
				$close = array_splice($styles, $i--, 1)[0];
			}
			else if(is_string($styles[$i]) && $styles[$i] !== '')
			{
				if(!($style = self::getSequence($styles[$i])))
				{
					throw new \Error('Invalid ...$styles[' . (string)$i . '] (no sequence for style \'' . $styles[$i] . '\' found)');
				}
				else
				{
					$styles[$i] = self::getSequence('escape') . $style . self::getSequence('delim');
				}
			}
			else if(is_array($styles[$i]))
			{
				if(count($styles[$i]) === 3)
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
						throw new \Error('Too many arrays (defining fg, then bg)');
					}
					
					--$count;
				}
				else
				{
					throw new \Error('Both (optional) arrays need to have a length of 3');
				}
			}
			else
			{
				throw new \Error('Invalid ...$styles[' . (string)$i . '] argument');
			}
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
			throw new \Error('Unable to apply at least one of your $fg/$bg color(s) (arrays need a length of three [ r, g, b ])');
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