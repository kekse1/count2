<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
const KEKSE_DEFAULT_META_SCHEME = (__DIR__ . '/../../json/kekse/map.json');
const KEKSE_DEFAULT_META_SCHEME_KEYS = [ 'keys', 'types', 'limits' ];

//
require_once(__DIR__ . '/quant.php');
require_once(__DIR__ . '/text.php');
require_once(__DIR__ . '/security.php');
require_once(__DIR__ . '/filesystem.php');

//
class Map extends Quant
{
	protected $values = null;
	protected $scheme = null;
	
	private static $metaScheme = null;
	
	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, ... $args);
	}

	public function getValues()
	{
		return $this->values;
	}

	public function getScheme()
	{
		return $this->scheme;
	}

	private static function loadMetaScheme($force = false, $path = KEKSE_DEFAULT_META_SCHEME, $check = true, $throw = true)
	{
		if(is_array(self::$metaScheme) && !$force)
		{
			return self::$metaScheme;
		}
		else if(!is_string($path))
		{
			$path = KEKSE_DEFAULT_META_SCHEME;
		}
		else
		{
			$path = realpath($path);
		}
		
		if(!FileSystem::isFile($path, true, false))
		{
			if($throw)
			{
				throw new \Error('The map\'s meta scheme `.json` is not a valid, readable file.');
			}
			
			return null;
		}
		else
		{
			self::$metaScheme = null;
		}
		
		$scheme = FileSystem::readFile($path);
		
		if(!$scheme)
		{
			if($throw)
			{
				throw new \Error('Unable to read map\'s meta scheme `.json` file.');
			}
			
			return null;
		}
		
		$scheme = parseJSON($scheme);
		
		if(!is_array($scheme))
		{
			if($throw)
			{
				throw new \Error('Parsing the read map\'s meta scheme `.json` file failed.');
			}
			
			return null;
		}
		
		$result = [];
		
		if($check)
		{
			foreach($scheme as $key => $value)
			{
				if(!in_array($key, KEKSE_DEFAULT_META_SCHEME_KEYS))
				{
					if($throw)
					{
						throw new \Error('In the meta scheme only the keys in `KEKSE_DEFAULT_META_SCHEME_KEYS[]` are allowed.');
					}
				}
				else
				{
					$result[$key] = $value;
				}
			}
		}
		else
		{
			$result = $scheme;
		}
		
		if(count(array_keys($result)) === 0)
		{
			return null;
		}

		return self::$metaScheme = $result;
	}

	public static function checkScheme($scheme, $throw = true)
	{
		if(!is_array($scheme)) return null;
		else self::loadMetaScheme();

		$result = [];

		foreach($scheme as $key => $value)
		{
			if(is_string($key = Security::checkString($key, true)))
			{
				if(!($key = self::decode(Text::trim($key))))
				{
					continue;
				}
			}
			else
			{
				continue;
			}

			foreach($value as $subKey => $subValue)
			{
				if(!in_array($subKey, self::$metaScheme['keys']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $subKey . '\' is forbidden (under the scheme key \'' . $key . '\')');
					}

					unset($value[$subKey]);
				}
			}

			if(isset($value['default']) && is_string($value['default']))
			{
				if(is_string($value['default'] = Security::checkString($value['default'], false)))
				{
					$value['default'] = self::decode(Text::trim($value['default']));
				}
				else
				{
					continue;
				}
			}
			
			if(isset($value['type']))
			{
				if(!is_string($value['type']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $key . '\' type value is not a string');
					}
					
					continue;
				}
				
				$t = self::getType($value['default']);
				
				if($value['type'] === 'number')
				{
					if($t !== 'integer' && $t !== 'double')
					{
						if($throw)
						{
							throw new \Error('Invalid default value within key \'' . $key . '\' (is no \'number\' type)');
						}
					
						continue;
					}
				}
				else if(isset($value['default']) && $t !== $value['type'])
				{
					if($throw)
					{
						throw new \Error('Invalid default value within key \'' . $key . '\' (is no \'' . $value['type'] . '\' type)');
					}
				
					continue;
				}
			}

			$cont = false;
			
			if(isset($value['min']))
			{
				if(!Number::isNumber($value['min']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $key . '\' defined a non-numeric minimum value');
					}
					
					continue;
				}
				else if(!isset($value['type']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $key . '\' defined a minimum, but no type');
					}
					
					continue;
				}
				else if(!in_array($value['type'], self::$metaScheme['limits']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $key . '\' defined a minimum for the type \'' . $value['type'] . '\', which is not allowed');
					}
					
					continue;
				}
				else if(isset($value['default'])) switch($value['type'])
				{
					case 'string':
						if(strlen($value['default']) < $value['min'])
						{
							if($throw)
							{
								throw new \Error('The key \'' . $key . '\' defines a default string *shorter* than the defined minimum of ' . $value['min']);
							}
							
							$cont = true;
						}
						break;
					case 'integer':
					case 'double':
					case 'number':
						if($value['default'] < $value['min'])
						{
							if($throw)
							{
								throw new \Error('The key \'' . $key . '\' defines a default value *below* the defined minimum of ' . $value['min']);
							}
							
							$cont = true;
						}
						break;
				}
			}
			
			if($cont)
			{
				continue;
			}

			if(isset($value['max']))
			{
				if(!Number::isNumber($value['max']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $key . '\' defined a non-numeric maximum value');
					}
					
					continue;
				}
				else if(!isset($value['type']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $key . '\' defined a maximum, but no type');
					}
					
					continue;
				}
				else if(!in_array($value['type'], self::$metaScheme['limits']))
				{
					if($throw)
					{
						throw new \Error('The key \'' . $key . '\' defined a maximum for the type \'' . $value['type'] . '\', which is not allowed');
					}
					
					continue;
				}
				else if(isset($value['default'])) switch($value['type'])
				{
					case 'string':
						if(strlen($value['default']) > $value['max'])
						{
							if($throw)
							{
								throw new \Error('The key \'' . $key . '\' defines a default string *longer* than the defined maximum of ' . $value['max']);
							}
							
							$cont = true;
						}
						break;
					case 'integer':
					case 'double':
					case 'number':
						if($value['default'] > $value['max'])
						{
							if($throw)
							{
								throw new \Error('The key \'' . $key . '\' defines a default value *above* the defined maximum of ' . $value['max']);
							}
							
							$cont = true;
						}
						break;
				}
			}
			
			if(!$cont)
			{
				$result[$key] = $value;
			}
		}

		if(count(array_keys($result)) === 0)
		{
			return null;
		}

		return $result;
	}
	
	public static function checkValues($values, $scheme = null, $throw = true)
	{
		if(!is_array($values)) return null;
		if(!is_array($scheme)) $scheme = null;
		$result = [];
		$numeric;

		foreach($values as $key => $value)
		{
			$orig = $key;
			
			if($numeric = is_int($key))
			{
				$key = $values[$key];
			}
			
			if(is_string($key = Security::checkString($key, true)))
			{
				if(!($key = self::decode(Text::trim($key))))
				{
					continue;
				}
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
			
			if($numeric)
			{
				$result[$orig] = $value;
				continue;
			}

			if(is_string($value))
			{
				if($value = Security::checkString($value, false))
				{
					$value = self::decode(Text::trim($value));
				}
				else
				{
					continue;
				}
			}

			if($scheme && isset($scheme[$key]['type']))
			{
				$type = self::getType($value);

				if($scheme[$key]['type'] === 'number')
				{
					if($type !== 'integer' && $type !== 'double')
					{
						if($throw)
						{
							throw new \Exception('Value type for key \'' . $key . '\' doesn\'t match scheme (is \'' . $type . '\', but should be \'' . $scheme[$key]['type'] . '\')');
						}
						
						continue;
					}
				}
				else if($scheme[$key]['type'] !== $type)
				{
					if($throw)
					{
						throw new \Exception('Value type for key \'' . $key . '\' doesn\'t match scheme (is \'' . $type . '\', but should be \'' . $scheme[$key]['type'] . '\')');
					}
					
					continue;
				}

				$cont = false;
				
				if(isset($scheme[$key]['min']))
				{
					switch($scheme[$key]['type'])
					{
						case 'string':
							$strLen = strlen($value);
							
							if($strLen < $scheme[$key]['min'])
							{
								if($throw)
								{
									throw new \Exception('String length is below the allowed minimum, for key \'' . $key . '\' (is ' . $strLen . ', but should be at least ' . $scheme[$key]['min'] . ')');
								}
								
								$cont = true;
							}
							break;
						case 'integer':
						case 'double':
						case 'number':
							$v;
							switch($scheme[$key]['type'])
							{
								case 'integer':
									$v = self::castToInteger($value);
									break;
								case 'double':
									$v = self::castToDouble($value);
									break;
								case 'number':
									$v = self::castToNumber($value);
									break;
							}
							if($v < $scheme[$key]['min'])
							{
								if($throw)
								{
									throw new \Exception('Value is below allowed minimum, for key \'' . $key . '\' (is ' . $v . ', but should be at least ' . $scheme[$key]['min'] . ')');
								}
								
								$cont = true;
							}
							break;
					}
				}
				
				if($cont)
				{
					continue;
				}
				
				if(isset($scheme[$key]['max']))
				{
					switch($scheme[$key]['type'])
					{
						case 'string':
							$strLen = strlen($value);
							
							if($strLen > $scheme[$key]['max'])
							{
								if($throw)
								{
									throw new \Exception('String length is above the allowed maximum, for key \'' . $key . '\' (is ' . $strLen . ', but should not be above ' . $scheme[$key]['max'] . ')');
								}
								
								$cont = true;
							}
							break;
						case 'integer':
						case 'double':
						case 'number':
							$v;
							switch($scheme[$key]['type'])
							{
								case 'integer':
									$v = self::castToInteger($value);
									break;
								case 'double':
									$v = self::castToDouble($value);
									break;
								case 'number':
									$v = self::castToNumber($value);
									break;
							}
							if($v > $scheme[$key]['max'])
							{
								if($throw)
								{
									throw new \Exception('Value is above allowed maximum, for key \'' . $key . '\' (is ' . $v . ', but should not be above ' . $scheme[$key]['max'] . ')');
								}
								
								$cont = true;
							}
							break;
					}
				}
				
				if($cont)
				{
					continue;
				}
			}

			$result[$key] = $value;
		}

		if(count(array_keys($result)) === 0)
		{
			return null;
		}

		return $result;
	}
	
	public function __destruct()
	{
		unset($this->values);
		parent::__destruct();
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
			//case 'number':
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
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		$value = (isset($this->values[$key]) ? $this->values[$key] : null);
		if($value !== null && $value !== '') return true;
		else if(!$default) return false;
		else $value = $this->getDefaultValue($key);
		if($value !== null && $value !== '') return true;
		return false;
	}
	
	public function type($key, $default = false)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(isset($this->values[$key])) return self::getType($this->values[$key]);
		if(!$default) return '';
		return self::getType($this->getDefaultValue($key));
	}
	
	public function delete($key)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(!$this->has($key)) return false;
		unset($this->values[$key]);
		return true;
	}
	
	public static function castValue($value)
	{
		if(!is_string($value))
		{
			return $value;
		}

		switch(strtolower($value))
		{
			case '(0)': case '-': case '(-)': case '(n)': case '(no)': case '(false)': return false;
			case '(1)': case '+': case '(+)': case '(y)': case '(yes)': case '(true)': return true;
		}
		
		if(Number::isNumeric($value, 10, null))
		{
			$value = (double)$value;
			
			if(fmod($value, 1) == 0)
			{
				$value = (int)$value;
			}
			
			return $value;
		}
		
		return $value;
	}
	
	public function castValues($values, $scheme = true)
	{
		if(!is_array($values))
		{
			return null;
		}
		else if(!is_array($this->scheme))
		{
			$scheme = false;
		}

		$result = [];
		$target;
		$type;

		foreach($values as $key => $value)
		{
			$type = self::getType($value);
			$target = ($scheme ? $this->getSchemeType($key) : '');

			switch($target)
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
				case 'number':
					$value = self::castToNumber($value);
					break;
				case '':
				default:
					$value = self::castValue($value);
					break;
			}

			$result[$key] = $value;
		}

		return $result;
	}
	
	public function getDefaultValue($key, $type = '')
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;

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
		else if(!is_string($key = Security::checkString($key, true)))
		{
			return null;
		}
		else if(!($key = self::decode(Text::trim($key))))
		{
			return null;
		}
		else if(!isset($this->scheme[$key]))
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
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, '');
		$result = $this->values[$key];
		if($scheme)
		{
			$type = $this->getSchemeType($key);

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
			}
		}
		return $result;
	}
	
	public static function castToString($value)
	{
		$type = self::getType($value);
		
		switch($type)
		{
			case 'string':
				$value = Security::checkString($value, false);
				break;
			case 'boolean':
				$value = ($value ? '1' : '0');
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
					case '0':
					case '(0)':
					case '-':
					case '(-)':
					case 'n':
					case '(n)':
					case 'no':
					case '(no)':
					case 'false':
					case '(false)':
						$value = false;
						break;
					default:
						$value = true;
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
				if(Number::isNumeric($value))
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
				if(Number::isNumeric($value))
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
				if(Number::isNumeric($value))
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
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
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
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'string');
		return self::castToString($this->values[$key]);
	}

	public function setString($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		$value = self::castToString($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getBoolean($key)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'boolean');
		return self::castToBoolean($this->values[$key]);
	}

	public function setBoolean($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		$value = self::castToBoolean($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getInteger($key)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'integer');
		return self::castToInteger($this->values[$key]);
	}

	public function setInteger($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		$value = self::castToInteger($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getDouble($key)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'double');
		return self::castToDouble($this->values[$key]);
	}

	public function setDouble($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		$value = self::castToDouble($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getNumber($key)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		if(!isset($this->values[$key])) return $this->getDefaultValue($key, 'number');
		return self::castToNumber($this->values[$key]);
	}
	
	public function setNumber($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true))) return null;
		else if(!($key = self::decode(Text::trim($key)))) return null;
		$value = self::castToNumber($value);
		if($value === null) return null;
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function importValues($values, $check = true, $throw = true)
	{
		if(!is_array($values))
		{
			return null;
		}
		else if(!$this->scheme)
		{
			$check = false;
		}
		else if(!is_bool($check))
		{
			$check = true;
		}

		if($check && !($values = self::checkValues($values, $this->scheme, $throw)))
		{
			return null;
		}
		else if(!is_array($this->values))
		{
			$this->values = [];
		}

		$this->values = array_merge($this->values, $values);
		return $values;
	}
	
	public function importScheme($scheme, $check = true, $throw = true)
	{
		if(!is_array($scheme))
		{
			return null;
		}

		if($check && !($scheme = self::checkScheme($scheme, $throw)))
		{
			return null;
		}
		else if(!is_array($this->scheme))
		{
			$this->scheme = [];
		}

		$this->scheme = array_merge($this->scheme, $scheme);

		if($check)
		{
			$this->values = self::checkValues($this->values, $this->scheme, $throw);
		}
		
		return $scheme;
	}

	public function importValuesFromJSON($path, $check = true, $throw = true)
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
		
		return $this->importValues($values, $check, $throw);
	}
	
	public function importSchemeFromJSON($path, $check = true, $throw = true)
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
		
		return $this->importScheme($scheme, $check, $throw);
	}
}

//
?>
