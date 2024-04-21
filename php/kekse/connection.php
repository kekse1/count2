<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
const KEKSE_RESET = true;

//
require_once(__DIR__ . '/main.php');
//require_once(__DIR__ . '/text.php');
//require_once(__DIR__ . '/security.php');
require_once(__DIR__ . '/parameter.php');

//
class Connection extends Quant
{
	public $parameter = null;

	private $headers = [];
	private $dataSent = false;

	private $flushed;
	private $buffer;
	private $bufferLength;
	
	public static $EOL = '\r\n';

	public function __construct($session = null, $buffer = KEKSE_CONNECTION_BUFFER, ... $args)
	{
		parent::__construct($session, ... $args);
		
		if($buffer === true)
		{
			$this->flushed = 0;
			$this->buffer = '';
			$this->bufferLength = 0;
		}
		else
		{
			$this->flushed = null;
			$this->buffer = null;
			$this->bufferLength = null;
		}
		
		$this->parameter = new Parameter($this->session);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function flush($reset = KEKSE_RESET)
	{
		if($this->flushed === null)
		{
			throw new \Error('Can\'t flush, since no buffer was used (as defined)');
			//return false;
		}

		$result = '';
		
		if(! $this->dataSent)
		{
			foreach($this->headers as $key => $value)
			{
				$hdr = $key . ': ' . $value;
				header($hdr);
				$result .= $hdr . self::$EOL;
			}
			
			if($result !== '')
			{
				$result .= self::$EOL;
			}
		}
		
		if($this->bufferLength > 0)
		{
			$this->realSend($this->buffer, $this->bufferLength);
			$result .= $this->buffer;
		}

		if($reset)
		{
			$this->flushed = null;
			$this->buffer = null;
			$this->bufferLength = null;
		}
		else
		{
			++$this->flushed;
			$this->buffer = '';
			$this->bufferLength = 0;
		}
		
		return strlen($result);
	}
	
	public static function responseHeaders()
	{
		$result = [];
		$orig;

		foreach($_SERVER as $key => $value)
		{
			$orig = $key;

			if(!Text::startsWith($key, 'HTTP_', true))
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

	public function writeError($data, $length = null, $type = null)
	{
		return $this->write($data, $length, $type);
	}
	
	public function write($data, $length = null, $type = null)
	{
		return $this->send($data, $length, $type);
	}
	
	protected function send($data, $length = null, $type = null)
	{
		if(!is_string($data))
		{
			if(Number::isNumber($data))
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
		else if(!is_string($type) && is_string($length) && $length !== '')
		{
			$type = $length;
		}
		
		if(!is_int($length) || $length < 0)
		{
			$length = null;
		}

		if(is_string($type) && $type !== '')
		{
			$this->setType($type);
		}

		if($this->flushed !== null)
		{
			$this->buffer .= $data;
			$this->bufferLength += strlen($data);
			return true;
		}
		
		return $this->realSend($data, $length);
	}
	
	protected function realSend($data, $length = null)
	{
		$result = parent::writeError($data, $length);
		
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
		else if(Text::startsWith($type, 'Content-Type:', false)) $type = substr($type, 13);
		if(!($type = Security::checkString($type, true))) throw new \Exception('Invalid $type argument');
		else if(!($type = Text::trim($type))) throw new \Exception('Invalid $type argument');
		return $this->set('Content-Type', $type);
	}

	public function setLength($length)
	{
		if($this->has('length')) return false;
		else if(is_int($length)) $length = (string)$length;
		else if(!is_string($length)) throw new \Exception('Invalid $length argument');
		else if(Text::startsWith($length, 'Content-Length', false)) $length = substr($length, 14);
		if(!($length = Security::checkString($length, true))) throw new \Exception('Invalid $length argument');
		else if(!($length = Text::trim($length))) throw new \Exception('Invalid $length argument');
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
		else if(!($item = Text::trim($item)))
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
		else if(Number::isNumber($value))
		{
			$value = (string)$value;
		}
		else if(!($value = Security::checkString($value, true)))
		{
			$value = null;
		}
		else if(!($value = Text::trim($value)))
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
		else if(!($value = Text::trim($value)))
		{
			throw new \Exception('Invalid $value argument');
		}
		else
		{
			$item = [ $item, $value ];
		}

		$this->headers[$item[0]] = $item[1];

		if($this->flushed !== null)
		{
			return null;
		}
		else if($this->dataSent)
		{
			throw new \Error('Data was already sent, so you can\'t send new headers');
		}

		header($item[0] . ': ' . $item[1]);
		return true;
	}
	
	public function has($key)
	{
		if(!($key = Security::checkString($key, true)))
		{
			return null;
		}
		else if(!($key = Text::trim($key)))
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
		else if(!($key = Text::trim($key)))
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
