<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//TODO/old "getParam()"!
//TODO/old "secure{Host,Path}()"
//

namespace kekse;

require_once('quant.inc.php');
require_once('string.inc.php');

define('KEKSE_LIMIT_PARAM', 32);

class Parameter extends Quant
{
	public $query;

	public function __construct($params, ... $args)
	{
		parent::__construct('Parameter', ... $args);

		if(is_string($params) || is_array($params))
		{
			$this->query = self::parse($params);
		}
		else
		{
			$this->query = [];
		}
	}
	
	public static function checkString($string)
	{
		if(!is_string($string))
		{
			return null;
		}
		
		$len = strlen($string);
		
		if($len === 0)
		{
			return null;
		}
		else if($len > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		else
		{
			$string = trim($string);
			$string = removeBinary($string, true);
		}
		
		return $string;
	}
	
	public function __destruct()
	{
		unset($this->query);
		return parent::__destruct();
	}

	public function __toString()
	{
		return '(Parameter[' . $this->getSize() . '])';
	}
	
	public function getSize()
	{
		return count($this->query);
	}

	public function has($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
		return (isset($this->$query[$key]));
	}
	
	public function delete($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
		if(!$this->has($key)) return false; unset($this->$query[$key]); return true;
	}
	
	public function get($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function getString($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	//convert to 0/1 bzw. alles >0 ist (true)!!
	public function getBool($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function getInt($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function getFloat($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function getNumber($key)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function set($key, $value)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function setString($key, $value)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	//convert to/from 0/1 bzw. alles >0 ist (true);
	public function setBool($key, $value)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function setInt($key, $value)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function setFloat($key, $value)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public function setNumber($key, $value)
	{
		if(!is_string($key = self::checkString($key))) throw new Error('Invalid $key argument');
	}
	
	public static function encode($value)
	{
		if(is_string($value))
		{
			return rawurlencode($value);
		}
		else if(!is_array($value))
		{
			throw new Error('Invalid $value argument (neither String nor Array)');
		}

		$result = [];

		foreach($value as $key => $value)
		{
			$key = self::encode($key);

			if(!is_string($value))
			{
				$value = (string)$value;
			}

			$result[$key] = self::encode($value);
		}

		return $result;
	}

	public static function decode($value)
	{
		if(is_string($value))
		{
			return rawurldecode($value);
		}
		else if(!is_array($value))
		{
			throw new Error('Invalid $value argument (neither String nor Array)');
		}

		$result = [];

		foreach($value as $key => $value)
		{
			$key = self::decode($key);

			if(!is_string($value))
			{
				$value = (string)$value;
			}

			$result[$key] = self::decode($value);
		}

		return $result;
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
		else if(!is_string($string))
		{
			return [];
		}
		
		if($string[0] === '?')
		{
			$string = substr($string, 1);
		}
		
		$setCurrent = function() use(&$key, &$value, &$result)
		{
			$key = self::decode($key);
			
			if($value === null)
			{
				if(isset($result[$key]))
				{
					if(is_int($result[$key]))
					{
						++$result[$key];
					}
					else
					{
						$result[$key] = 1;
					}
				}
				else
				{
					$result[$key] = 1;
				}
			}
			else
			{
				$result[$key] = self::decode($value);
				$value = null;
			}
			
			$key = '';
		};

		$result = [];
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
					$setCurrent();
				}
				else
				{
					$value = null;
				}
			}
			else if($string[$i] === '=')
			{
				if($value === null)
				{
					$value = '';
				}
				else if(strlen($key) === 0)
				{
					$key = '=';
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
