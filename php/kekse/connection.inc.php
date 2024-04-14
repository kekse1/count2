<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/main.inc.php');
require_once(__DIR__ . '/security.inc.php');
require_once(__DIR__ . '/parameter.inc.php');

class Connection extends Quant
{
	public $parameter = null;

	private $headers = [];
	private $dataSent = false;

	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, ... $args);
		$this->parameter = new Parameter($this->session);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public static function headers()
	{
		$result = [];
		$orig;

		foreach($_SERVER as $key => $value)
		{
			$orig = $key;

			if(!str_starts_with($key, 'HTTP_'))
			{
				continue;
			}

			$key = strtolower(substr($key, 5));
			$key = str_replace('_', '-', $key);
			$key = Security::checkString($key, true);
			$value = Security::checkString($_SERVER[$orig], false);

			$result[$key] = $value;
		}

		return $result;
	}

	public function writeError($data, $length = null, $type = null, ... $args)
	{
		return $this->send($data, $length, $type, true);
	}
	
	public function write($data, $length = null, $type = null, ... $args)
	{
		return $this->send($data, $length, $type, false);
	}
	
	protected function send($data, $length = null, $type = null, $error = false)
	{
		if(!is_string($data))
		{
			if(is_number($data))
			{
				$data = (string)$data;
			}
			else
			{
				$data = print_r($data, true);
			}
		}

		if($type === true)
		{
			$type = KEKSE_CONTENT_TYPE;
		}

		if(is_string($type) && $type !== '')
		{
			$this->setType($type);
		}

		$result;
		if($error) $result = parent::writeError($data, $length);
		else $result = parent::write($data, $length);

		if($result !== false)
		{
			$this->dataSent = true;
		}
		
		return $result;
	}

	private function checkState($throw = true)
	{
		if(!$this->dataSent) return true;
		else if($throw) throw new \Exception('Can\'t send any header after body data began.');
		return false;
	}
	
	public function setType($type)
	{
		if($this->has('type')) return false;
		else if(!is_string($type)) throw new \Exception('Invalid $type argument');
		else if(str_starts_with($type, 'Content-Type:')) $type = substr($type, 13);
		if(!($type = Security::checkString($type, true))) throw new \Exception('Invalid $type argument');
		else if(!($type = str_trim($type))) throw new \Exception('Invalid $type argument');
		return $this->set('Content-Type', $type);
	}

	public function setLength($length)
	{
		if($this->has('length')) return false;
		else if(is_int($length)) $length = (string)$length;
		else if(!is_string($length)) throw new \Exception('Invalid $length argument');
		else if(str_starts_with($length, 'Content-Length')) $length = substr($length, 14);
		if(!($length = Security::checkString($length, true))) throw new \Exception('Invalid $length argument');
		else if(!($length = str_trim($length))) throw new \Exception('Invalid $length argument');
		return $this->set('Content-Length', $length);
	}

	public function set($item, $value = null, $throw = false)
	{
		if(!$this->checkState($throw))
		{
			return null;
		}
		else if(is_array($item))
		{
			$result = 0;

			foreach($item as $key => $value)
			{
				if($this->set($key, $value, $throw))
				{
					++$result;
				}
			}

			return $result;
		}
		else if(!($item = Security::checkString($item, true)))
		{
			throw new \Exception('Invalid $item argument');
		}
		else if(!($item = str_trim($item)))
		{
			throw new \Exception('Invalid $item argument');
		}
		else
		{
			$item = strtolower($item);
		}

		if($this->has($item))
		{
			return false;
		}
		else if(is_number($value))
		{
			$value = (string)$value;
		}
		else if(!($value = Security::checkString($value, true)))
		{
			$value = null;
		}
		else if(!($value = str_trim($value)))
		{
			$value = null;
		}
		
		if($value === null)
		{
			if(count($item = explode(':', $item, 2)) !== 2)
			{
				throw new \Exception('Invalid $item argument');
			}
		}
		else if(!($value = Security::checkString($value, true)))
		{
			throw new \Exception('Invalid $value argument');
		}
		else if(!($value = str_trim($value)))
		{
			throw new \Exception('Invalid $value argument');
		}
		else
		{
			$item = [ $item, $value ];
		}

		$result = $item[0] . ': ' . $item[1];
		$this->headers[$item[0]] = $item[1];

		header($result);
		return true;
	}
	
	public function has($key)
	{
		if(!($key = Security::checkString($key, true)))
		{
			return null;
		}
		else if(!($key = str_trim($key)))
		{
			return null;
		}

		return isset($this->headers[$key]);
	}

	public function get($key)
	{
		if(!($key = Security::checkString($key, true)))
		{
			return false;
		}
		else if(!($key = str_trim($key)))
		{
			return false;
		}
		else if(isset($this->headers[$key]))
		{
			return $this->headers[$key];
		}

		return null;
	}
}

?>
