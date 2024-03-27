<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//
class Map extends Quant
{
	protected $values = null;
	protected $scheme = null;
	
	public function __construct($session = null, $values = null, ... $args)
	{
		if(is_array($values))
		{
			$this->values = self::check($values, null, true);
		}

		return parent::__construct($session, ... $args);
	}

	public static function check($values, $scheme = null, $throw = true)
	{
		if(!is_array($values)) return null;
		if(!is_array($scheme)) $scheme = null;
		$result = [];
		
		foreach($values as $key => $value)
		{
			if(is_string($key = Security::checkString($key = self::decode($key), true, true)))
			{
				$key = self::decode($key);
			}
			else
			{
				continue;
			}
			
			if($scheme)
			{
				if(!array_key_exists($key, $scheme))
				{
					if($throw)
					{
						throw new \Exception('Array key \'' . $key . '\' is not specified within scheme');
					}
					
					continue;
				}
			}
			
			if(is_string($value))
			{
				if(is_string($value = Security::checkString($value, true, true)))
				{
					$value = self::decode($value);
				}
				else
				{
					continue;
				}
				
				if(is_numeric($value))
				{
					$value = (double)$value;
					
					if(fmod($value, 1) == 0)
					{
						$value = (int)$value;
					}
				}
				else switch(strtolower($value))
				{
					case '1':
					case 'yes':
					case 'true':
						$value = true;
						break;
					case '0':
					case 'no':
					case 'false':
						$value = false;
						break;
				}
			}
			
			if($scheme && isset($scheme[$key]['type']))
			{
				if($scheme[$key]['type'] !== self::getType($value))
				{
					if($throw)
					{
						throw new \Exception('Value type doesn\'t match scheme at key \'' . $key . '\'');
					}
					
					continue;
				}
			}

			$result[$key] = $value;
		}

		return $result;
	}
	
	public function __destruct()
	{
		unset($this->values);
		return parent::__destruct();
	}

	public function keys()
	{
		return array_keys($this->values);
	}

	public static function encode($value)
	{
		if(is_string($value))
		{
			return rawurlencode($value);
		}
		else if(!is_array($value))
		{
			throw new \Exception('Invalid $value argument (neither String nor Array)');
		}

		$result = [];

		foreach($value as $key => $value)
		{
			$key = self::encode($key);

			if(is_string($value))
			{
				$value = self::encode($value);
			}

			$result[$key] = $value;
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
			throw new \Exception('Invalid $value argument (neither String nor Array)');
		}

		$result = [];

		foreach($value as $key => $value)
		{
			$key = self::decode($key);
			
			if(is_string($value))
			{
				$value = self::decode($value);
			}

			$result[$key] = $value;
		}

		return $result;
	}

	public static function getType($value)
	{
		$type = gettype($value);
		
		switch($type)
		{
			case 'string':
			case 'boolean':
			case 'double':
			case 'integer':
				return $type;
		}
		
		return '';
	}
	
	public function getSize()
	{
		return count($this->values);
	}
	
	public function contains($key, $default = false)
	{
		return $this->has($key, $default);
	}
	
	public function has($key, $default = false)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$key = self::decode($key);
		if(isset($this->values[$key])) return true;
		else if($default && $this->getDefaultValue($key) !== null) return true;
		return false;
	}
	
	public function type($key, $default = false)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$key = self::decode($key);
		if(isset($this->values[$key])) return self::getType($this->values[$key]);
		if(!$default) return '';
		return self::getType($this->getDefaultValue($key));
	}
	
	public function delete($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$key = self::decode($key);
		if(!$this->has($key)) return false;
		unset($this->values[$key]);
		return true;
	}
	
	public function getDefaultValue($key, $type = '')
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$key = self::decode($key);

		$result;

		if(isset($this->values) && isset($this->values[$key]) && is_array($this->values[$key]))
		{
			$result = $this->values[$key];
		}
		else if(isset($this->scheme) && isset($this->scheme[$key]) && is_array($this->scheme[$key]))
		{
			$result = $this->scheme[$key];
		}
		else
		{
			return null;
		}
		
		if(!isset($result['default']))
		{
			return null;
		}
		
		$result = $result['default'];

		if(!is_string($type))
		{
			$type = '';
		}
		
		if(!$type)
		{
			if(($type = $this->getSchemeType($key)) === null)
			{
				$type = '';
			}
		}

		switch($type)
		{
			case 'string':
				$result = self::castToString($result);
				break;
			case 'boolean':
				$result = self::castToBoolean($result);
				break;
			case 'integer':
				$result = self::castToInteger($result);
				break;
			case 'double':
				$result = self::castToDouble($result);
				break;
			case 'number':
				$result = self::castToNumber($result);
				break;
			case '':
				break;
		}
		
		return $result;
	}
	
	public function getSchemeType($key)
	{
		if(!is_array($this->scheme))
		{
			return null;
		}
		else if(!is_string($key = Security::checkString($key, true, true)))
		{
			return null;
		}
		else if(!isset($this->scheme[$key = self::decode($key)]))
		{
			return null;
		}
		
		$result = $this->scheme[$key];
		
		if(!isset($result['type']))
		{
			return null;
		}

		return $result['type'];
	}
	
	public function get($key, $scheme = '')
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, '');
		$result = $this->values[$key = self::decode($key)];
		$type = ($scheme ? $this->getSchemeType($key) : null);
		if($type === null) $type = self::getType($result);
		
		switch($type)
		{
			case '':
				$result = null;
				break;
			case 'string':
				$result = Security::checkString($result, true, true);

				if(is_numeric($result))
				{
					$result = (double)$result;
					if(fmod($result, 1) == 0) $result = (int)$result;
				}
				else switch(strtolower($result))
				{
					case '1':
					case 'yes':
					case 'true':
						$result = true;
						break;
					case '0':
					case 'no':
					case 'false':
						$result = false;
						break;
				}
				break;
		}

		return $result;
	}
	
	public static function castToString($value)
	{
		$type = self::getType($value);
		
		switch($type)
		{
			case 'string':
				$value = Security::checkString($value, true, true);
				break;
			case 'boolean':
				$value = ($value ? 'yes' : 'no');
				break;
			case 'integer':
			case 'double':
			case 'number':
				$value = (string)$value;
				break;
			case '':
			default:
				$value = null;
				break;
		}
		
		return $value;
	}

	public static function castToBoolean($value)
	{
		$type = self::getType($value);
		
		switch($type)
		{
			case 'string':
				switch(strtolower($value))
				{
					case '0': case 'no': case 'false':
						$value = false;
						break;
					case '1': case 'yes': case 'true':
						$value = false;
						break;
					default:
						$value = (strlen($value) > 0);
						break;
				}
				break;
			case 'boolean':
				break;
			case 'integer':
				$value = ($value !== 0);
				break;
			case 'double':
				$value = ($value !== 0.0);
				break;
			case 'number':
				$value = ($value != 0);
				break;
			case '':
			default:
				$value = null;
				break;
		}
		
		return $value;
	}

	public static function castToInteger($value)
	{
		$type = self::getType($value);
		
		switch($type)
		{
		case 'string':
				if(is_numeric($value))
				{
					$value = (int)$value;
				}
				else
				{
					$value = strlen($value);
				}
				break;
			case 'boolean':
				$value = ($value ? 1 : 0);
				break;
			case 'integer':
				break;
			case 'double':
			case 'number':
				$value = (int)$value;
				break;
			case '':
			default:
				$value = null;
				break;
		}
		
		return $value;
	}

	public static function castToDouble($value)
	{
		$type = self::getType($value);
		
		switch($type)
		{
			case 'string':
				if(is_numeric($value))
				{
					$value = (double)$value;
				}
				else
				{
					$value = (double)strlen($value);
				}
				break;
			case 'boolean':
				$value = ($value ? 1.0 : 0.0);
				break;
			case 'double':
				break;
			case 'integer':
			case 'number':
				$value = (double)$value;
				break;
			case '':
			default:
				$value = null;
				break;
		}
		
		return $value;
	}

	public static function castToNumber($value)
	{
		$type = self::getType($value);
		
		switch($type)
		{
			case 'string':
				if(is_numeric($value))
				{
					$value = (double)$value;
					if(fmod($value, 1) == 0) $value = (int)$value;
				}
				else
				{
					$value = strlen($value);
				}
				break;
			case 'boolean':
				$value = ($value ? 1 : 0);
				break;
			case 'integer':
				break;
			case 'double':
			case 'number':
				if(fmod($value, 1) == 0) $value = (int)$value;
				break;
			case '':
			default:
				$value = null;
				break;
		}
		
		return $value;
	}

	//
	//maybe w/ .scheme!?? aber der in parameter, eh? :-/
	//
	public function set($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$type = self::getType($value);
		switch($type)
		{
			case 'string':
				$value = self::castToString($value);
				break;
			case 'boolean':
				$value = self::castToBoolean($value);
				break;
			case 'integer':
				$value = self::castToInteger($value);
				break;
			case 'double':
				$value = self::castToDouble($value);
				break;
			/*case 'number':
				$value = self::castToNumber($value);
				break;*/
			case '':
			default:
				return null;
		}
		$result = $this->get($key = self::decode($key));
		$this->values[$key] = $value;
		return $result;
	}

	public function getString($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'string');
		return self::castToString($this->values[$key]);
	}

	public function setString($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$value = self::castToString($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getBoolean($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'boolean');
		return self::castToBoolean($this->values[$key]);
	}

	public function setBoolean($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$value = self::castToBoolean($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getInteger($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'integer');
		return self::castToInteger($this->values[$key]);
	}

	public function setInteger($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$value = self::castToInteger($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getDouble($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'double');
		return self::castToDouble($this->values[$key]);
	}

	public function setDouble($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$value = self::castToDouble($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getNumber($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'number');
		return self::castToNumber($this->values[$key]);
	}
	
	public function setNumber($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$value = self::castToNumber($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	//
	//TODO/check for existence [oder so]..
	//
	public function importValues($values)
	{
		if(!is_array($values))
		{
			return null;
		}
		else if(!is_array($this->values))
		{
			$this->values = [];
		}
		
		$result = self::check($values, $this->scheme);
		$this->values = array_merge($this->values, $result);
		
		return $result;
	}
	
	public function importScheme($scheme)
	{
		if(!is_array($scheme))
		{
			return null;
		}
		else if(!is_array($this->scheme))
		{
			$this->scheme = [];
		}

		$result = self::check($scheme, null);
		$this->scheme = array_merge($this->scheme, $result);
		
		return $result;
	}

	public function importValuesFromJSON($path)
	{
		$values = FileSystem::readFile($path);
		
		if(!$values)
		{
			return null;
		}
		
		$values = parseJSON($values);
		
		if(!is_array($values))
		{
			return null;
		}
		
		return $this->importValues($values);
	}
	
	public function importSchemeFromJSON($path)
	{
		$scheme = FileSystem::readFile($path);
		
		if(!$scheme)
		{
			return null;
		}
		
		$scheme = parseJSON($scheme);
		
		if(!is_array($scheme))
		{
			return null;
		}
		
		return $this->importScheme($scheme);
	}
}

?>
