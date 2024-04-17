<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
class Text
{
	//original: `str_unique()`
	public static function unique($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		$len = strlen($string);

		if($len === 0)
		{
			return $string;
		}
		
		$result = '';

		for($i = 0; $i < $len; ++$i)
		{
			if(!Text::contains($result, $string[$i], true))
			{
				$result .= $string[$i];
			}
		}

		return $result;
	}
	
	//original: `str_trim()`
	public static function trim($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		$result = '';
		$len = strlen($string);
		$start = -1;
		$stop = -1;
		$byte;

		for($i = 0; $i < $len; ++$i)
		{
			$byte = ord($string[$i]);

			if($byte > 32 && $byte !== 127)
			{
				$start = $i;
				break;
			}
		}

		for($i = $len - 1; $i > $start; --$i)
		{
			$byte = ord($string[$i]);

			if($byte > 32 && $byte !== 127)
			{
				$stop = $i;
				break;
			}
		}

		if($start > -1)
		{
			$string = substr($string, $start);
		}

		if($stop > -1)
		{
			$string = substr($string, 0, $stop - $start + 1);
		}

		return $string;
	}
	
	//original: `str_remove_spaces()`
	public static function removeWhiteSpaces($string)
	{
		return self::removeBinary($string, true);
	}
	
	//original: `str_remove_binary()`
	public static function removeBinary($string, $whiteSpaces = false)
	{
		if(!is_string($string))
		{
			return null;
		}
		
		$len = strlen($string);
		
		if($len === 0)
		{
			return $string;
		}
		
		$result = '';
		$byte;
		
		for($i = 0; $i < $len; ++$i)
		{
			$byte = ord($string[$i]);
			
			if($byte === 32 && $whiteSpaces)
			{
				continue;
			}
			else if($byte > 31 && $byte !== 127)
			{
				$result .= $string[$i];
			}
		}
		
		return $result;
	}
	
	//original: `str_contains_binary()`
	public static function containsBinary($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		$len = strlen($string);
		
		if($len === 0)
		{
			return false;
		}
		
		$byte;

		for($i = 0; $i < $len; ++$i)
		{
			$byte = ord($string[$i]);

			if($byte < 32 || $byte === 127)
			{
				return true;
			}
		}

		return false;
	}
	
	//original: `str_is_lower()`
	public static function isLowerCase($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		return (strtolower($string) === $string);
	}
	
	//original: `str_is_upper()`
	public static function isUpperCase($string)
	{
		if(!is_string($string))
		{
			return null;
		}

		return (strtoupper($string) === $string);
	}
	
	public static function endsWith($haystack, $needle, $caseSensitive = true)
	{
		if(! (is_string($haystack) && is_string($needle)))
		{
			throw new \Exception('Invalid arguments (expecting Strings)');
		}
		else if(!$caseSensitive)
		{
			$haystack = strtolower($haystack);
			$needle = strtolower($needle);
		}
		
		return str_ends_with($haystack, $needle);
	}
	
	public static function startsWith($haystack, $needle, $caseSensitive = true)
	{
		if(! (is_string($haystack) && is_string($needle)))
		{
			throw new \Exception('Invalid argument(s) (expecting Strings)');
		}
		else if(!$caseSensitive)
		{
			$haystack = strtolower($haystack);
			$needle = strtolower($needle);
		}
		
		return str_starts_with($haystack, $needle);
	}

	public static function contains($haystack, $needle, $caseSensitive = true)
	{
		if(! (is_string($haystack) && is_string($needle)))
		{
			throw new \Exception('Invalid argument(s) (expecting Strings)');
		}
		else if(!$caseSensitive)
		{
			$haystack = strtolower($haystack);
			$needle = strtolower($needle);
		}
	
		return str_contains($haystack, $needle);
	}
}

//
?>
