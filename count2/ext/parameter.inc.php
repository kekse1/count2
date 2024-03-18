<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//TODO/old "getParam()"!
//TODO/old "secure{Host,Path}()"
//

namespace kekse;

define('KEKSE_LIMIT_PARAM', 32);

require_once('security.inc.php');
require_once('number.inc.php');

class Parameter extends Quant
{
	private $query;

	public function __construct($params, ... $args)
	{
		if(! (is_string($params) || is_array($params)))
		{
			if(isset($_SERVER) && isset($_SERVER['QUERY_STRING']))
			{
				$params = $_SERVER['QUERY_STRING'];
			}
			else
			{
				throw new \Exception('Invalid $params argument, and no `$_SERVER[\'QUERY_STRING\']` available.');
			}
		}

		if(is_string($params) || is_array($params))
		{
			$this->query = self::parse($params);
		}
		else
		{
			$this->query = [];
		}

		if(!isset($this->query['time']))
		{
			$this->query['time'] = timestamp();
		}
		
		return parent::__construct('Parameter', ... $args);
	}
	
	public function __get($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return new \Error('Invalid argument');
		else if(property_exists($this, $key)) return $this->{$key};
		else $key = self::decode($key);
		if(!$this->has($key)) return null;
		return $this->query[$key];
	}
	
	public function __set($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return new \Error('Invalid argument');
		else if(property_exists($this, $key)) return $this->{$key} = $value;
		else $key = self::decode($key);
		$result = $this->has($key);
		$this->set($key, $value);
		return $result;
	}
	
	public function __destruct()
	{
		unset($this->query);
		return parent::__destruct();
	}

	public function __toString()
	{
		return $this->render($this->query);
	}
	
	public function getSize()
	{
		return count($this->query);
	}
	
	public function getLength()
	{
		return $this->getSize();
	}

	public function has($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key); return (isset($this->query[$key]));
	}
	
	public function delete($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		if(!$this->has($key)) return false;
		else unset($this->query[$key]);
		return true;
	}
	
	public function get($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		$result = $this->query[$key];
		$type = self::getType($result);
		
		if($type === '') return null;
		return $result;
	}
	
	public function getString($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!$this->has($key)) return null;
		$result = $this->query[$key];
		
		if(!is_string($result))
		{
			if(is_bool($result)) $result = ($result ? 'yes' : 'no');
			if(is_number($result)) $result = (string)$result;
		}
		
		return $result;
	}
	
	public function getBoolean($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);

		if(!$this->has($key)) return null;
		$result = $this->query[$key];

		if(!is_bool($result))
		{
			if(is_int($result)) $result = ($result !== 0);
			else if(is_double($result)) $result = ($result !== 0.0);
			else if(is_string($result))
			{
				switch(strtolower($result))
				{
					case '1': case '1.0': case 'yes': $result = true; break;
					case '0': case '0.0': case 'no': $result = false; break;
					default: $result = (strlen($result) > 0); break;
				}
			}
			else $result = (strlen((string)$result) > 0);
		}

		return $result;
	}
	
	public function getInteger($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!$this->has($key)) return null;
		$result = $this->query[$key];
		
		if(!is_int($result))
		{
			if(is_bool($result)) $result = ($result ? 1 : 0);
			else if(is_double($result)) $result = (int)$result;
			else $result = (int)(string)$result;
		}
		
		return $result;
	}
	
	public function getDouble($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!$this->has($key)) return null;
		$result = $this->query[$key];
		
		if(!is_double($result))
		{
			if(is_bool($result)) $result = ($result ? 1.0 : 0.0);
			else if(is_int($result)) $result = (double)$result;
			else $result = (double)(string)$result;
		}
		
		return $result;
	}
	
	public function getNumber($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!$this->has($key)) return null;
		$result = $this->query[$key];
		
		if(!is_number($result))
		{
			if(is_bool($result)) $result = ($result ? 1 : 0);
			else
			{
				$result = (string)$result;
				
				if(str_contains($result, '.'))
				{
					$result = (double)$result;
				}
				else
				{
					$result = (int)$result;
				}
			}
		}
		
		return $result;
	}
	
	public static function getType($value)
	{
		$type = gettype($value);
		
		switch($type)
		{
			case 'string': return 'string';
			case 'boolean': return 'boolean';
			case 'double': return 'double';
			case 'integer': return 'integer';
		}
		
		return '';
	}
	
	public function set($key, $value)
	{
		$type = self::getType($value);
		
		switch($type)
		{
			case 'string': return $this->setString($key, $value);
			case 'boolean': return $this->setBoolean($key, $value);
			case 'double': return $this->setDouble($key, $value);
			case 'integer': return $this->setInteger($key, $value);
		}
		
		return $this->set($key, (string)$value);
	}
	
	//TODO/empty string VALUES!???
	public function setString($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!is_string($value))
		{
			if(is_bool($value)) $value = ($value ? 'yes' : 'no');
			else $value = (string)$value;
		}
		
		$value = self::decode(Security::checkString($value, true, true));

		$result = ($this->has($key));
		$this->query[$key] = $value;
		return $result;
	}
	
	public function setBoolean($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!is_bool($value))
		{
			if(is_int($value)) $value = ($value !== 0);
			else if(is_double($value)) $value = ($value !== 0.0);
			else if(is_string($value))
			{
				switch(strtolower($value))
				{
					case '1': case '1.0': case 'yes': $result = true; break;
					case '0': case '0.0': case 'no': $result = false; break;
					default: $result = (strlen($result) > 0); break;
				}
			}
			else $value = (bool)(string)$value;
		}
		
		$result = ($this->has($key));
		$this->query[$key] = $value;
		return $result;
	}
	
	public function setInteger($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!is_int($value))
		{
			if(is_double($value)) $value = (int)$value;
			else if(is_bool($value)) $value = ($value ? 1 : 0);
			else $value = (int)(string)$value;
		}

		$result = ($this->has($key));
		$this->query[$key] = $value;
		return $result;
	}
	
	public function setDouble($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!is_double($value))
		{
			if(is_int($value)) $value = (double)$value;
			else if(is_bool($value)) $value = ($value ? 1.0 : 0.0);
			else $value = (double)(string)$value;
		}
		
		$result = ($this->has($key));
		$this->query[$key] = $value;
		return $result;
	}

	public function setNumber($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) throw new \Error('Invalid $key argument');
		else $key = self::decode($key);
		
		if(!is_number($value))
		{
			if(is_bool($value)) $value = ($value ? 1 : 0);
			else
			{
				$value = Security::checkString(self::decode((string)$value), true, true);
				if(str_contains($value, '.')) $value = (double)$value;
				else $value = (int)$value;
			}
		}
		
		if(is_double($value)) return $this->setDouble($key, $value);
		else if(is_int($value)) return $this->setInteger($key, $value);
	}
	
	public static function encode($value)
	{
		if(is_string($value))
		{
			return rawurlencode($value);
		}
		else if(!is_array($value))
		{
			throw new \Error('Invalid $value argument (neither String nor Array)');
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
			throw new \Error('Invalid $value argument (neither String nor Array)');
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
