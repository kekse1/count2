<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/security.inc.php');

//
class Path
{
	public static function secure($path)
	{
		return Security::secure($path, 'path');
	}
	
	public static function getRoot($real = KEKSE_PATH_REAL)
	{
		$result;

		if(isset($_SERVER['DOCUMENT_ROOT']))
		{
			$result = $_SERVER['DOCUMENT_ROOT'];
		}
		else
		{
			$result = getcwd();
		}

		if(!$result)
		{
			$result = '.';
		}

		if($real)
		{
			$result = realpath($result);
		}

		return $result;
	}

	public static function resolve(... $args)
	{
		$origin = self::getRoot();
		$len = count($args);
		$rem = 0;

		for($i = $len - 1; $i >= 0; --$i)
		{
			if(!is_string($args[$i]) || $args[$i] === '')
			{
				array_splice($args, $i, 1);
				--$len;
			}
		}

		if($len === 0)
		{
			return $origin;
		}
		else if(is_string($args[0]) && $args[0] !== '' && $args[0] !== $origin)
		{
			$len1 = strlen($args[0]);
			$len2 = strlen($origin);

			$argWith;
			$originWith;

			if($args[0][$len1 - 1] === DIRECTORY_SEPARATOR)
			{
				$argWith = $args[0];
			}
			else
			{
				$argWith = substr($args[0], 0, -1);
			}

			if($origin[$len2 - 1] === DIRECTORY_SEPARATOR)
			{
				$originWith = $origin;
			}
			else
			{
				$originWith = substr($origin, 0, -1);
			}

			if(Text::startsWith($argWith, $originWith, true) || Text::startsWith($originWith, $argWith, true))
			{
				array_unshift($args, $origin);
			}
		}

		return self::join(... $args);
	}
	
	public static function join(... $args)
	{
		$len = count($args);

		for($i = $len - 1; $i >= 0; --$i)
		{
			if(!is_string($args[$i]) || $args[$i] === '')
			{
				array_splice($args, $i, 1);
				--$len;
			}
		}

		if($len === 0)
		{
			return '.';
		}

		return self::normalize(implode(DIRECTORY_SEPARATOR, $args));
	}
	
	public static function normalize($path, $preserve = true)
	{
		if(!is_string($path))
		{
			return null;
		}
		else if(!($path = Security::checkString($path, true)))
		{
			return null;
		}
		
		$len = strlen($path);
		
		if($len === 0 || $path === '.')
		{
			return '.';
		}
		else if($len > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		
		$abs = ($path[0] === DIRECTORY_SEPARATOR);
		$dir = ($path[$len - 1] === DIRECTORY_SEPARATOR);
		$split = explode(DIRECTORY_SEPARATOR, $path);
		$result = [];
		$minus = 0;
		$item = '';
		
		while(count($split) > 0)
		{
			$item = array_shift($split);
			
			if(!$item)
			{
				continue;
			}
			
			switch($item)
			{
				case '.': break;
				case '..':
					if(count($result) === 0)
					{
						++$minus;
					}
					else
					{
						array_pop($result);
					}
					break;
				default:
					array_push($result, $item);
					break;
			}
		}
		
		if($abs)
		{
			array_unshift($result, '');
		}
		else if($preserve) while(--$minus >= 0)
		{
			array_unshift($result, '..');
		}
		
		if($dir)
		{
			array_push($result, '');
		}
		
		return implode(DIRECTORY_SEPARATOR, $result);
	}
	
	public static function extname($path, $count = KEKSE_PATH_EXT)
	{
		if(!is_string($path))
		{
			return null;
		}
		else if(!($path = Security::checkString($path, true)))
		{
			return null;
		}
		else if(!is_int($count))
		{
			$count = 1;
		}

		$rev = ($count < 0);
		$count = abs($count);
		$split = explode(DIRECTORY_SEPARATOR, $path);
		$len = count($split);

		for($i = $len - 1; $i >= 0; --$i)
		{
			if(strlen($split[$i]) === 0)
			{
				array_pop($split);
			}
			else
			{
				break;
			}
		}

		if(($len = count($split)) === 0)
		{
			return '';
		}

		$split = explode('.', array_pop($split));

		if(($len = count($split)) === 0)
		{
			return '';
		}
		else if(strlen($split[0]) === 0)
		{
			array_shift($split);
			--$len;
		}

		array_shift($split);
		
		if(--$len === 0)
		{
			return '';
		}
		else if($count > $len)
		{
			$count = $len;
		}

		$result;

		if($count === 0)
		{
			$result = $split;
		}
		else
		{
			$result = [];
		}

		if($count !== 0)
		{
			if($rev) for($i = 0; $i < $len && $i < $count; ++$i)
			{
				$result[$i] = $split[$i];
			}
			else for($i = $len - $count, $j = 0; $i < $len && $j < $count; ++$i, ++$j)
			{
				$result[$j] = $split[$i];
			}
		}

		if(count($result) === 0)
		{
			return '';
		}

		return ('.' . implode('.', $result));

	}
	
	public static function relative($from, $to)
	{
		if(!(is_string($from) && is_string($to)))
		{
			return null;
		}
		
		if(($from = self::normalize($from))[0] !== DIRECTORY_SEPARATOR)
		{
			$from = self::resolve($from);
		}

		if(($to = self::normalize($to))[0] !== DIRECTORY_SEPARATOR)
		{
			$to = self::resolve($to);
		}

		$from = explode(DIRECTORY_SEPARATOR, $from);
		$fromLen = count($from);
		$to = explode(DIRECTORY_SEPARATOR, $to);
		$toLen = count($to);

		for($i = $fromLen - 1; $i >= 0; --$i)
		{
			if($from[$i] === '')
			{
				array_splice($from, $i, 1);
				--$fromLen;
			}
		}

		for($i = $toLen - 1; $i >= 0; --$i)
		{
			if($to[$i] === '')
			{
				array_splice($to, $i, 1);
				--$toLen;
			}
		}

		$minDepth = min($fromLen, $toLen);
		$same;

		for($same = 0; $same < $minDepth; ++$same)
		{
			if($from[$same] !== $to[$same]) break;
		}

		if($same > 0)
		{
			array_splice($from, 0, $same);
			$fromLen -= $same;
			array_splice($to, 0, $same);
			$toLen -= $same;
		}

		if($fromLen === 0 && $toLen === 0)
		{
			return '';
		}

		$result = [];

		for($i = 0; $i < $fromLen; ++$i)
		{
			$result[$i] = '..';
		}

		for($i = 0, $j = $fromLen; $i < $toLen; ++$i, ++$j)
		{
			$result[$j] = $to[$i];
		}

		return implode(DIRECTORY_SEPARATOR, $result);
	}
}

//
?>
