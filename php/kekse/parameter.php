<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/map.php');
require_once(__DIR__ . '/text.php');
require_once(__DIR__ . '/security.php');

//
class Parameter extends Map
{
	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, ... $args);
	}
	
	public static function hasQuery()
	{
		return (isset($_SERVER) && isset($_SERVER['QUERY_STRING']));// && !empty($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '?');
	}
	
	public function import($string = null, $check = true)
	{
		$result = self::parseQuery($string, false);

		if(!$result)
		{
			return null;
		}

		if(!($result = $this->castValues($result, false)))
		{
			return null;
		}
		
		if($check) try
		{
			$result = self::checkValues($result, $this->scheme, true);
		}
		catch(\Throwable $err)
		{
			$this->writeError($err->getMessage(), null, KEKSE_CONTENT_TEXT, true, false);
			exit(1);
		}

		return $this->importValues($result, false);
	}
	
	public static function parseQuery($string = null, $cast = KEKSE_CAST)
	{
		$result;
		
		if(! (is_string($string) && !empty($string) && $string !== '?'))
		{
			if(self::hasQuery())
			{
				$string = $_SERVER['QUERY_STRING'];
			}
			else
			{
				$string = '';
			}
		}

		return self::parse($string, $cast);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function __toString()
	{
		return $this->render($this->values);
	}
	
	public static function render($array)
	{
		if(is_string($array))
		{
			$array = self::parse($array, false);
		}
		else if(!is_array($array))
		{
			return '';
		}

		if(count($array) === 0)
		{
			return '';
		}

		$result = '?';

		foreach($array as $key => $value)
		{
			if(!($key = Security::checkString($key, true)))
			{
				continue;
			}
			else if(!($key = self::decode(Text::trim($key))))
			{
				continue;
			}
		
			$value = self::castToString($value);

			$key = self::encode($key);
			$value = self::encode($value);
			
			$result .= $key . '=' . $value . '&';
		}

		return substr($result, 0, -1);
	}

	public static function parse($string, $cast = KEKSE_CAST)
	{
		if(is_array($string))
		{
			$string = self::render($string);
		}
		else if(!is_string($string) || $string === '')
		{
			return null;
		}
		
		if($string[0] === '?')
		{
			$string = substr($string, 1);
		}
		
		$clear = function($ret) use(&$key, &$value, &$keyLen, &$valueLen)
		{
			$key = '';
			$keyLen = 0;
			$value = null;
			$valueLen = -1;
			return $ret;
		};

		$setCurrent = function() use(&$clear, &$result, &$count, &$key, &$value, &$cast)
		{
			if(!($key = Security::checkString($key, true)))
			{
				return $clear(true);
			}
			else if(!($key = self::decode(Text::trim($key))))
			{
				return $clear(true);
			}
			
			$false;
			
			if($key[0] === '!')
			{
				$key = substr($key, 1);

				if($key === '')
				{
					return $clear(true);
				}
				
				$false = true;
			}
			else
			{
				$false = false;
			}
			
			if($value === null)
			{
				$value = !$false;
			}
			else if(is_string($value))
			{
				$value = Security::checkString($value, false);
				$value = self::decode($value);
				
				if($cast)
				{
					$value = parent::castValue($value);
				}
			}
			
			if(!isset($result[$key]))
			{
				if($exceeding = (++$count >= KEKSE_LIMIT_PARAM))
				{
					throw new \Error('Too many parameters');
					return $clear(false);
				}
			}

			$result[$key] = $value;
			return $clear(true);
		};

		$result = [];
		$count = 0;
		$len = strlen($string);
		$key = '';
		$value = null;
		$byte;
		$keyLen = 0;
		$valueLen = 0;

		for($i = 0; $i < $len; ++$i)
		{
			if(($byte = ord($string[$i])) < 32 || $byte === 127)
			{
				continue;
			}
			else if($string[$i] === '&')
			{
				if($keyLen > 0 && !$setCurrent())
				{
					return null;
				}
			}
			else if($string[$i] === '=')
			{
				if($keyLen > 0)
				{
					$value = '';
					$valueLen = 0;
				}
			}
			else if($value === null)
			{
				$key .= $string[$i];

				if(++$keyLen > KEKSE_LIMIT_STRING)
				{
					throw new \Error('Key string was too long');
					return null;
				}
			}
			else
			{
				$value .= $string[$i];

				if(++$valueLen > KEKSE_LIMIT_STRING)
				{
					throw new \Error('Value length was too long');
					return null;
				}
			}
		}

		if(strlen($key) > 0)
		{
			$setCurrent();
		}

		return $result;
	}
}

?>
