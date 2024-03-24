<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//
class Map extends Quant
{
	protected $values = null;
	
	public function __construct($session = null, $values = null, ... $args)
	{
		$this->values = $values;
		$this->castValues();
		return parent::__construct($session, ... $args);
	}

	private function castValues()
	{
		$result = [];
		if(!$this->values) return $this->values = $result;
		
		foreach($this->values as $key => $value)
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

		return $this->values = $result;
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
	
	public function get($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return null;
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
		if(!isset($this->values[$key])) return null;
		$result = $this->values[$key];
		$type = self::getType($result);
		switch($type)
		{
			case '':
				$result = null;
				break;
			case 'string':
				$result = Security::checkString($result, true, true);
				break;
			case 'boolean':
				$result = ($result ? '1' : '0');
				break;
			case 'double':
			case 'integer':
				$result = (string)$result;
				break;
		}
		return $result;
	}

	public function setString($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$type = self::getType($value);
		switch($type)
		{
			case '':
				return null;
			case 'string':
				$value = Security::checkString($value, true, true);
				break;
			case 'boolean':
				$value = ($value ? '1' : '0');
				break;
			case 'double':
				$value = (($value === 0.0) ? '0' : '1');
				break;
			case 'integer':
				$value = (($value === 0) ? '0' : '1');
				break;
		}
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getBoolean($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return null;
		$result = $this->values[$key];
		$type = self::getType($result);
		switch($type)
		{
			case '':
				$result = null;
				break;
			case 'string':
				switch(strtolower($result))
				{
					case '0': case '0.0': $result = false; break;
					case '1': case '1.0': $result = true; break;
					default:
						$result = (strlen($result) > 0);
						break;
				}
				break;
			case 'boolean':
				break;
			case 'double':
				$result = ($result !== 0.0);
				break;
			case 'integer':
				$result = ($result !== 0);
				break;
		}
		return $result;
	}

	public function setBoolean($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$type = self::getType($value);
		switch($type)
		{
			case '':
				return null;
			case 'string':
				switch($value)
				{
					case '0': case '0.0': $value = false; break;
					case '1': case '1.0': $value = true; break;
					default: $value = (strlen($value) !== 0 ? true : false); break;
				}
				break;
			case 'boolean':
				break;
			case 'double':
				$value = ($value !== 0.0);
				break;
			case 'integer':
				$value = ($value !== 0);
				break;
		}
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getInteger($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return null;
		$result = $this->values[$key];
		$type = self::getType($result);
		switch($type)
		{
			case '':
				$result = null;
				break;
			case 'string':
				$result = (int)$result;
				break;
			case 'boolean':
				$result = ($result ? 1 : 0);
				break;
			case 'double':
				$result = (int)$result;
				break;
			case 'integer':
				break;
		}
		return $result;
	}

	public function setInteger($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$type = self::getType($value);
		switch($type)
		{
			case '':
				return null;
			case 'string':
				$value = (int)$value;
				break;
			case 'boolean':
				$value = ($value ? 1 : 0);
				break;
			case 'double':
				$value = (int)$value;
				break;
			case 'integer':
				break;
		}
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getDouble($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return null;
		$result = $this->values[$key];
		$type = self::getType($result);
		switch($type)
		{
			case '':
				$result = null;
				break;
			case 'string':
				$result = (double)$result;
				break;
			case 'boolean':
				$result = ($result ? 1.0 : 0.0);
				break;
			case 'double':
				break;
			case 'integer':
				$result = (double)$result;
				break;
		}
		return $result;
	}

	public function setDouble($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$type = self::getType($value);
		switch($type)
		{
			case '':
				return null;
			case 'string':
				$value = (double)$value;
				break;
			case 'boolean':
				$value = ($value ? 1.0 : 0.0);
				break;
			case 'double':
				break;
			case 'integer':
				$value = (double)$value;
				break;
		}
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	
	public function getNumber($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		if(!isset($this->values[$key])) return null;
		$result = $this->values[$key];
		$type = self::getType($result);
		switch($type)
		{
			case '':
				$result = null;
				break;
			case 'string':
				$result = (double)$result;
				if(fmod($result, 1) == 0) $result = (int)$result;
				break;
			case 'boolean':
				$result = ($result ? 1 : 0);
				break;
			case 'double':
				if(fmod($result, 1) == 0) $result = (int)$result;
				break;
			case 'integer':
				break;
		}
		return $result;
	}
	
	public function setNumber($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return null;
		$type = self::getType($value);
		switch($type)
		{
			case '':
				return null;
			case 'string':
				$value = (double)$value;
				if(fmod($value, 1) == 0) $value = (int)$value;
				break;
			case 'boolean':
				$value = ($value ? 1 : 0);
				break;
			case 'double':
				if(fmod($value, 1) == 0) $value = (int)$value;
				break;
			case 'integer':
				break;
		}
		$result = (isset($this->values[$key]) ? $this->values[$key] : null);
		$this->values[$key] = $value;
		return $result;
	}
	

	//
	/*public function __get($key)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return new \Error('Invalid argument');
		else if(property_exists($this, $key)) return $this->{$key};
		else $key = self::decode($key);
		if(!$this->has($key)) return null;
		return $this->values[$key];
	}
	
	public function __set($key, $value)
	{
		if(!is_string($key = Security::checkString($key, true, true))) return new \Error('Invalid argument');
		else if(property_exists($this, $key)) return $this->{$key} = $value;
		else $key = self::decode($key);
		$result = $this->has($key);
		$this->set($key, $value);
		return $result;
	}*/
}

?>
