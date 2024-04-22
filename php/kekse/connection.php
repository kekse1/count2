<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/quant.php');
require_once(__DIR__ . '/text.php');
require_once(__DIR__ . '/security.php');
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
	
	public static $EOL = "\r\n";

	public function __construct($session = null, $buffer = KEKSE_CONNECTION_BUFFER, ... $args)
	{
		parent::__construct($session, ... $args);
		
		if($buffer === true)
		{
			$this->flushed = 0;
			$this->buffer = '';
			$this->bufferLength = 0;

			register_shutdown_function(\Closure::fromCallable([ $this, 'shutdownHandler' ]));
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

	public function buffered()
	{
		return ($this->flushed !== null);
	}

	public function shutdownHandler()
	{
		return $this->flush(true);
	}

	public function flush($reset = KEKSE_CONNECTION_RESET, $throw = KEKSE_THROW_CONNECTION)
	{
		if(!$this->buffered())
		{
			if($throw)
			{
				throw new \Error('Can\'t flush, since no buffer was used (as defined)');
			}

			return false;
		}

		$result = 0;

		if(! $this->dataSent)
		{
			foreach($this->headers as $key => $value)
			{
				$hdr = $key . ': ' . $value;
				header($hdr);
				$result += strlen($hdr . self::$EOL);
			}
		}

		if($this->bufferLength > 0)
		{
			$result += $this->realSend($this->buffer, $this->bufferLength);
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

		return $result;
	}
	
	public static function requestHeaders()
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

	public function clear()
	{
		return [	'headers' => $this->clearHeaders(),
				'buffer' => $this->clearBuffer() ];
	}
	
	public function clearHeaders()
	{
		if(!$this->buffered() || $this->dataSent)
		{
			return null;
		}
		
		$result = count($this->headers);
		
		if($result === 0)
		{
			return 0;
		}
		
		$this->headers = [];
		return $result;
	}
	
	public function clearBuffer()
	{
		if(!$this->buffered())
		{
			return null;
		}

		$result = $this->bufferLength;

		if($result === 0)
		{
			return 0;
		}

		$this->buffer = '';
		$this->bufferLength = 0;
		
		return $result;
	}
	
	public function writeError($data, $length = null, $type = null, $force = false, $throw = KEKSE_THROW_CONNECTION, ... $args)
	{
		return $this->send($data, $length, $type, $force, $throw, ... $args);
	}
	
	public function write($data, $length = null, $type = null, $force = false, $throw = KEKSE_THROW_CONNECTION, ... $args)
	{
		return $this->send($data, $length, $type, $force, $throw, ... $args);
	}
	
	protected function send($data, $length = null, $type = null, $force = false, $throw = KEKSE_THROW_CONNECTION, ... $args)
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
			$this->setType($type, $force, $throw);
		}

		if($this->buffered())
		{
			$this->buffer .= $data;
			$this->bufferLength += strlen($data);

			if($force)
			{
				return $this->flush();
			}

			return true;
		}
		
		return $this->realSend($data, $length);
	}
	
	protected function realSend($data, $length = null, $force = null)
	{
		$result = $this->swrite(1, $data, $length, $this);
		
		if($result !== false)
		{
			$this->dataSent = true;
		}
		
		return $result;
	}

	public function setType($type, $force = false, $throw = KEKSE_THROW_CONNECTION, ... $args)
	{
		if($this->has('type')) return false;
		else if(!is_string($type)) { if($throw) throw new \Exception('Invalid $type argument'); return null; }
		else if(Text::startsWith($type, 'Content-Type:', false)) $type = substr($type, 13);
		if(!($type = Security::checkString($type, true))) { if($throw) throw new \Exception('Invalid $type argument'); return null; }
		else if(!($type = Text::trim($type))) { if($throw) throw new \Exception('Invalid $type argument'); return null; }
		return $this->set('Content-Type', $type, $force, $throw);
	}

	public function setLength($length, $force = false, $throw = KEKSE_THROW_CONNECTION, ... $args)
	{
		if($this->has('length')) return false;
		else if(is_int($length)) $length = (string)$length;
		else if(!is_string($length)) { if($throw) throw new \Exception('Invalid $length argument'); return null; }
		else if(Text::startsWith($length, 'Content-Length', false)) $length = substr($length, 14);
		if(!($length = Security::checkString($length, true))) { if($throw) throw new \Exception('Invalid $length argument'); return null; }
		else if(!($length = Text::trim($length))) { if($throw) throw new \Exception('Invalid $length argument'); return null; }
		return $this->set('Content-Length', $length, $force, $throw);
	}

	public function set($item, $value = null, $force = false, $throw = KEKSE_THROW_CONNECTION, ... $args)
	{
		if($this->dataSent)
		{
			if($throw)
			{
				throw new \Error('Can\'t set new header variables, some data has already been sent');
			}

			return null;
		}
		else if(is_array($item))
		{
			$result = 0;

			foreach($item as $key => $value)
			{
				if($this->set($key, $value, $force, $throw))
				{
					++$result;
				}
			}

			return $result;
		}
		else if(!($item = Security::checkString($item, true)))
		{
			if($throw)
			{
				throw new \Exception('Invalid $item argument');
			}

			return null;
		}
		else if(!($item = Text::trim($item)))
		{
			if($throw)
			{
				throw new \Exception('Invalid $item argument');
			}

			return null;
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
				if($throw)
				{
					throw new \Exception('Invalid $item argument');
				}

				return null;
			}
		}
		else if(!($value = Security::checkString($value, true)))
		{
			if($throw)
			{
				throw new \Exception('Invalid $value argument');
			}

			return null;
		}
		else if(!($value = Text::trim($value)))
		{
			if($throw)
			{
				throw new \Exception('Invalid $value argument');
			}

			return null;
		}
		else
		{
			$item = [ $item, $value ];
		}

		$item[0] = self::fixHeaderKey($item[0]);
		$this->headers[$item[0]] = $item[1];

		if($this->buffered())
		{
			return false;
		}
		else if($this->dataSent)
		{
			if($throw)
			{
				throw new \Error('Data was already sent, so you can\'t send new headers');
			}

			return false;
		}

		$string = ($item[0] . ': ' . $item[1]);
		header($string);
		$this->session->OUTPUT += strlen($string . self::$EOL);
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
	
	public function unset($key)
	{
		if(!$this->buffered())
		{
			//
			//TODO/$throw argument?!??
			//
			return false;
		}
		else if(!($key = Security::checkString($key, true)))
		{
			return false;
		}
		else if(!($key = Text::trim($key)))
		{
			return false;
		}
		else if(!isset($this->headers[$key]))
		{
			return null;
		}
		
		$result = $this->headers[$key];
		unset($this->headers[$key]);
		return $result;
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

	public static function fixHeaderKey($key, $sep = '-')
	{
		if(!is_string($key))
		{
			return null;
		}
		else if($key === '')
		{
			return '';
		}

		$result = strtoupper($key[0]);
		$len = strlen($key);
		$hadSep = false;

		for($i = 1; $i < $len; ++$i)
		{
			if($key[$i] === $sep)
			{
				$hadSep = true;
				$result .= $sep;
			}
			else
			{
				if($hadSep)
				{
					$result .= strtoupper($key[$i]);
				}
				else
				{
					$result .= $key[$i];
				}

				$hadSep = false;
			}
		}

		return $result;
	}
}

//
?>
