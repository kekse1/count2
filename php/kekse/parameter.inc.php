<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//require_once(__DIR__ . '/main.inc.php');
require_once(__DIR__ . '/filesystem.inc.php');
require_once(__DIR__ . '/map.inc.php');

class Parameter extends Map
{
	public function __construct($session = null, $scheme = null, $values = null, ... $args)
	{
		if(! (is_string($values) || is_array($values)))
		{
			if(isset($_SERVER['QUERY_STRING']))
			{
				$values = $_SERVER['QUERY_STRING'];
			}
			else
			{
				$values = null;
				//throw new \Exception('Invalid $params argument, and no `$_SERVER[\'QUERY_STRING\']` available.');
			}
		}

		$values = self::parse($values);
		$this->scheme = $scheme;

		parent::__construct($session, $values, ... $args);
	}
	
	public function __destruct()
	{
		return parent::__destruct();
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
			$key = self::encode($key);

			if(!is_string($value))
			{
				$value = (string)$value;
			}

			$result .= $key . '=' . self::encode($value) . '&';
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
		
		$setCurrent = function() use(&$key, &$value, &$result, &$count)
		{
			if(strlen($key) > KEKSE_LIMIT_STRING)
			{
				return null;
			}
			else if(is_string($value) && strlen($value) > KEKSE_LIMIT_STRING)
			{
				return null;
			}
			
			$exceeding = ($count >= KEKSE_LIMIT_PARAM);
			$real = self::decode($key);
			$key = '';
			
			if($value === null)
			{
				if(isset($result[$real]))
				{
					if(is_int($result[$real]))
					{
						++$result[$real];
					}
					else if(is_bool($result[$real]))
					{
						$result[$real] = 2;
					}
					else
					{
						$result[$real] = 1;
					}
				}
				else
				{
					$result[$real] = true;
					++$count;
				}
				
				return ($exceeding ? null : false);
			}

			$result[$real] = self::decode($value);
			$value = null;

			return ($exceeding ? null : true);
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
				if(strlen($key) > 0)
				{
					if($setCurrent() === null)
					{
						break;
					}
				}
				else
				{
					$value = null;
				}
			}
			else if($string[$i] === '=')
			{
				if(strlen($key) === 0)
				{
					$key = '=';
				}
				else if($value === null)
				{
					$value = '';
				}
				else
				{
					$value .= '=';
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
