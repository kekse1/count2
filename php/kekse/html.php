<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/style.php');
require_once(__DIR__ . '/numeric.php');

//
class HTML implements Style
{
	private static $SEQ = [
		'bold' => 'font-weight: bold;',
		'italic' => 'font-style: italic;',
		'underline' => 'text-decoration: underline;',
		
		'fg' => 'color: %s;',//rgb(%d, %d, %d);',
		'bg' => 'background-color: %s;' //rgb(%d, %d, %d);'
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
	
	private static function renderParam($param)
	{
		if(is_string($param))
		{
			return $param;
		}
		else if(is_number($param))
		{
			return (string)$param;
		}
		else if(is_array($param))
		{
			return self::renderArray($param);
		}
		else if(is_bool($param))
		{
			return ($param ? 'auto' : 'none');//@css..
		}
		
		return '';
	}
	
	private static function renderArray($array)
	{
		if(!is_array($array))
		{
			throw new \Error('Invalid $array argument');
		}
		
		$count = count($array);
		
		switch($count)
		{
			case 0:
				return '';
			case 1:
				return self::renderParam($array[0]);
			case 3:
				return sprintf('rgb(%d, %d, %d)', ... $array);
			case 4:
				return sprintf('rgba(%d, %d, %d)', ... $array);
		}
		
		return '';
	}

	public static function text($type, $string = null, ... $params)
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

		$result = '<span style="' . sprintf($seq, self::renderParam($params)) . '">';
		
		if($isString)
		{
			$result .= $string . '</span>';
		}
		
		return $result;
	}

	public static function color($string, $fg = null, $bg = null)
	{
		if($fg === null && $bg === null)
		{
			return (is_string($string) ? $string : '');
		}
		
		$result = '<span style="';
		
		if($fg !== null)
		{
			$result .= sprintf(self::sequence('fg'), self::renderParam($fg)) . ' ';
		}

		if($bg !== null)
		{
			$result .= sprintf(self::sequence('bg'), self::renderParam($bg)) . ' ';
		}
		
		$result = rtrim($result) . '">';
		
		if(is_string($string))
		{
			$result .= $string . '</span>';
		}
		
		return $result;
	}
	
	public static function style($string, ... $styles)
	{
		$result = '<span style="';
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
						$styles[$i] = $style;
					}
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
						throw new \Error('Too many color arrays given (only need [ fg, bg ])');
					}
					
					--$count;
				}
				else
				{
					throw new \Error('Both (optional) color arrays need to have a length of 3');
				}
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
		
		if($fg !== null || $bg !== null)
		{
			if($fg !== null)
			{
				$result .= sprintf(self::sequence('fg'), self::renderParam($fg)) . ' ';
			}
			
			if($bg !== null)
			{
				$result .= sprintf(self::sequence('bg'), self::renderParam($bg)) . ' ';
			}
		}
		
		foreach($styles as $style)
		{
			$result .= $style . ' ';
		}
		
		$result = rtrim($result) . '">';
		
		if(is_string($string))
		{
			$result .= $string . '</span>';
		}
		
		return $result;
	}

	public static function bold($string)
	{
		return self::text('bold', $string);
	}

	public static function italic($string)
	{
		return self::text('italic', $string);
	}

	public static function underline($string)
	{
		return self::text('underline', $string);
	}

	public static function fg($string, ... $params)
	{
		return self::text('fg', $string, ... $params);
	}

	public static function bg($string, ... $params)
	{
		return self::text('bg', $string, ... $params);
	}
}

//
?>
