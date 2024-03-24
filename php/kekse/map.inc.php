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
		$this->values = self::castValues($values);
		return parent::__construct($session, ... $args);
	}

	public static function castValues($values)
	{
		if(!is_array($values)) return null;
		$result = [];
		
		foreach($values as $key => $value)
		{
			$key = Security::checkString($key = self::decode($key), true, true);

			if(is_string($value))
			{
				$value = Security::checkString($value = self::decode($value), true, true);
			}
			
			if($key && $value)
			{
				if(is_string($value))
				{
					if(is_numeric($value))
					{
						$value = (double)$value;
						if(fmod($value, 1) == 0) $value = (int)$value;
					}
					else switch(strtolower($value))
					{
						case 'yes':
						case 'true':
							$value = true;
							break;
						case 'no':
						case 'false':
							$value = false;
							break;
					}
				}
				
				$result[$key] = $value;
			}
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
	
	public function has($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		else $key = self::decode($key); return isset($this->values[$key]);
	}
	
	public function delete($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		else $key = self::decode($key);
		if(!$this->has($key)) return false;
		unset($this->values[$key]);
		return true;
	}
	
	public function getDefaultValue($key, $type = '')
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;

		$result;

		if(isset($this->values) && isset($this->values[$key = self::decode($key)]) && is_array($this->values[$key]))
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
			default:
				$result = null;
				break;
		}
		
		return $result;
	}
	
	public function get($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, '');
		$result = $this->values[$key = self::decode($key)];
		$type = self::getType($result);
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
					case 'yes':
					case 'true':
						$result = true;
						break;
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
					case '0': case '0.0':
					case 'no': case 'false':
						$value = false;
						break;
					case '1': case '1.0':
					case 'yes': case 'true':
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
		if(!$type) return null;
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
}

?>
