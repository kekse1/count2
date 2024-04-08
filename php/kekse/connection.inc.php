<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//require_once(__DIR__ . '/security.inc.php');

class Connection extends Quant
{
	private $headers = [];
	private $sent = false;

	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function write($data)
	{
		if(!is_string($data))
		{
			if(is_number($data))
			{
				$data = (string)$data;
			}
			else
			{
				throw new \Error('Invalid $data argument');
			}
		}

		$this->sent = true;
		echo $data;
		return strlen($data);
	}

	private function checkState($throw = true)
	{
		if(!$this.sent) return true;
		else if($throw) throw new \Exception('Can\'t send any header after body data began.');
		return false;
	}
	
	public function setTypeHeader($type)
	{
		if(!is_string($type)) throw new \Exception('Invalid $type argument');
		else if(str_starts_with($type, 'Content-Type:')) $type = substr($type, 13);
		if(!($type = \kekse\Security::checkString($type, true))) throw new \Exception('Invalid $type argument');
		else if(!($type = str_trim($type))) throw new \Exception('Invalid $type argument');
		$this->setHeader('Content-Type', $type);
		$this->typeSent = true;
		return $type;
	}

	public function setLengthHeader($length)
	{
		if(is_int($length)) $length = (string)$length;
		else if(!is_string($length)) throw new \Exception('Invalid $length argument');
		else if(str_starts_with($length, 'Content-Length')) $length = substr($length, 14);
		if(!($length = \kekse\Security::checkString($length, true))) throw new \Exception('Invalid $length argument');
		else if(!($length = str_trim($length))) throw new \Exception('Invalid $length argument');
		$this->setHeader('Content-Length', $length);
		$this->lengthSent = true;
		return $type;
	}

	public function setHeader($item, $value = null)
	{
		if(!$this->checkState())
		{
			return null;
		}
		else if(is_array($item))
		{
			$result = 0;
			
			foreach($item as $key => $value)
			{
				if(!($key = \kekse\Security::checkString($key, true)))
				{
					continue;
				}
				else if(!($key = \kekse\str_trim($key)))
				{
					continue;
				}
				
				if(is_number($value))
				{
					$value = (string)$value;
				}
				else if(!is_string($value))
				{
					continue;
				}
				
				$this->header[$key] = $value;
				header($key . ': ' . $value);
				++$result;
			}
			
			return $result;
		}
		else if(!($item = \kekse\Security::checkString($item, true)))
		{
			throw new \Exception('Invalid $item argument');
		}
		else if(!($item = \kekse\str_trim($item)))
		{
			throw new \Exception('Invalid $item argument');
		}

		if(is_number($value))
		{
			$value = (string)$value;
		}
		else if(!($value = \kekse\Security::checkString($value, true)))
		{
			$value = null;
		}
		else if(!($value = \kekse\str_trim($value)))
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
		else if(!($value = \kekse\Security::checkString($value, true)))
		{
			throw new \Exception('Invalid $value argument');
		}
		else if(!($value = \kekse\str_trim($value)))
		{
			throw new \Exception('Invalid $value argument');
		}
		else
		{
			$item = [ $item, $value ];
		}

		$result = $item[0] . ': ' . $item[1];
		$this->header[$item[0]] = $item[1];
		header($result);
		return $result;
	}
}

?>
