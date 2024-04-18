<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

require_once(__DIR__ . '/map.inc.php');
require_once(__DIR__ . '/text.inc.php');
require_once(__DIR__ . '/security.inc.php');

class Parameter extends Map
{
	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, ... $args);
	}
	
	public static function hasQuery()
	{
		return (isset($_SERVER) && isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '?');
	}
	
	public function import($string = null, $check = true)
	{
		$result = self::parseQuery($string);

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
			$this->writeError($err->getMessage());
			exit(1);
		}

		return $this->importValues($result, false);
	}
	
	public static function parseQuery($string = null)
	{
		$result;
		
		if(is_string($string) && !empty($string) && $string !== '?')
		{
			$result = self::parse($string);
		}
		else if(self::hasQuery())
		{
			$result = self::parse($_SERVER['QUERY_STRING']);
		}
		else
		{
			return null;
		}

		if(!is_array($result))
		{
			return null;
		}
		
		return $result;
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
			$array = self::parse($array);
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

	public static function parse($string)
	{
		if(is_array($string))
		{
			$string = self::render($string);
		}
		else if(!is_string($string) || $string === '')
		{
			return [];
		}
		
		if($string[0] === '?')
		{
			$string = substr($string, 1);
		}
		
		$clear = function($ret) use(&$key, &$value)
		{
			$key = '';
			$value = null;
			return $ret;
		};

		$setCurrent = function() use(&$clear, &$result, &$count, &$key, &$value)
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
				
				if($value === '')
				{
					if(isset($result[$key]))
					{
						unset($result[$key]);
						--$count;
					}
					
					return $clear(true);
				}
			}
			
			if(!isset($result[$key]))
			{
				if($exceeding = (++$count >= KEKSE_LIMIT_PARAM))
				{
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

		for($i = 0; $i < $len; ++$i)
		{
			if(($byte = ord($string[$i])) < 32 || $byte === 127)
			{
				continue;
			}
			else if($string[$i] === '&')
			{
				if(strlen($key) > 0 && !$setCurrent())
				{
					return null;
				}
			}
			else if($string[$i] === '=')
			{
				if(strlen($key) > 0)
				{
					$value = '';
				}
			}
			else if($value === null)
			{
				$key .= $string[$i];
			}
			else
			{
				$value .= $string[$i];
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
