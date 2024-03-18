<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once('ext/security.inc.php');

class Connection extends Quant
{
	public $session;

	private $headers = [];

	public function __construct($session, ... $args)
	{
		$this->session = $session;
		return parent::__construct('Connection', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
	
	function getHeaders()
	{
		return [ ... $this->headers ];
	}

	public function sendTypeHeader($type)
	{
		if(!is_string($type)) throw new \Error('Invalid $type argument');
		else if(str_starts_with($type, 'Content-Type:')) $type = substr($type, 13);
		if(!($type = \kekse\Security::checkString($type, true, true))) throw new \Error('Invalid $type argument');
		$this->sendHeader('Content-Type', $type);
		$this->typeSent = true;
		return $type;
	}

	public function sendLengthHeader($length)
	{
		if(is_int($length)) $length = (string)$length;
		else if(!is_string($length)) throw new \Error('Invalid $length argument');
		else if(str_starts_with($length, 'Content-Length')) $length = substr($length, 14);
		if(!($length = \kekse\Security::checkString($length, true, true))) throw new \Error('Invalid $length argument');
		$this->sendHeader('Content-Length', $length);
		$this->lengthSent = true;
		return $type;
	}

	public function sendHeader($item, $value = null)
	{
		if(is_array($item))
		{
			$result = 0;
			
			foreach($item as $key => $value)
			{
				if(!($key = \kekse\Security::checkString($key, true, true)))
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
		else if(!($item = \kekse\Security::checkString($item, true, true)))
		{
			throw new \Error('Invalid $item argument');
		}

		if(is_number($value))
		{
			$value = (string)$value;
		}
		else if(!($value = \kekse\Security::checkString($value, true, true)))
		{
			$value = null;
		}
		
		if($value === null)
		{
			if(count($item = explode(':', $item, 2)) !== 2)
			{
				throw new \Error('Invalid $item argument');
			}
		}
		else if(!($value = \kekse\Security::checkString($value, true, true)))
		{
			throw new \Error('Invalid $value argument');
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
