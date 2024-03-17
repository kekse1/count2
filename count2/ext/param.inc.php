<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//TODO/old "getParam()"!
//TODO/old "secure{Host,Path}()"
//

namespace kekse;

require_once('quant.inc.php');

class Parameter extends Quant
{
	public $params;

	public function __construct($params, ... $args)
	{
		parent::__construct('Parameter', ... $args);

		if(is_string($params))
		{
			$this->params = self::parse($params);
		}
		else if(is_array($params))
		{
			$this->params = [ ... $params ];
		}
		else
		{
			$this->params = [];
		}
	}

	public function __destruct()
	{
		parent::__destruct();
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
		if(is_string($string))
		{
			if($string[0] === '?')
			{
				$string = substr($string, 1);
			}
		}
		else if(is_array($string))
		{
			return [ ... $string ];
		}
		else
		{
			return [];
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
