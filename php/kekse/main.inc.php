<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

class Quant
{
	public $session = null;

	public $time;
	public $args;

	public function kekseDebug()
	{
		$error = null;

		if(isset($this->session->error))
		{
			$error = $this->session->error;
		}
		else if(isset($GLOBALS['ERROR']))
		{
			$error = $GLOBALS['ERROR'];
		}
		else if(defined('KEKSE_DEBUG') && is_bool(KEKSE_DEBUG))
		{
			return !!KEKSE_DEBUG;
		}

		return $error->kekseDebug();
	}

	public function __construct($session = null, ... $args)
	{
		$this->session = $session;
		$this->time = timestamp();
		$this->args = $args;
	}

	public function __destruct()
	{
		//
	}

	public function __toString()
	{
		return $this->classPath();// . ';' . $this->runtime();
	}

	public static function isCLI()
	{
		return (php_sapi_name() === 'cli');
	}

	public function className()
	{
		$result = explode('\\',  get_class($this));
		return $result[count($result) - 1];
	}

	public function classPath()
	{
		return get_class($this);
	}

	public function runtime()
	{
		return timestamp($this->time);
	}

	// "If post data is malformed, $_POST will not contain anything. Yet, php://input will have the malformed string."
	// "php://input is not available in POST requests with enctype="multipart/form-data" if enable_post_data_reading option is enabled."
	public function read($length = null)
	{
throw new \Exception('TODO');
		//file_get_contents('php://input');
		//BETTER: 'fread()', etc.!! ^_^
	}

	public function readByte()
	{
		$result = fgetc(IN);
		
		if($result === false)
		{
			return false;
		}
		
		return ord($result);
	}

	public function readBytes($count = 1)
	{
		//https://www.php.net/manual/en/function.unpack.php
	  	//https://www.php.net/manual/en/function.pack.php
	}
	
	public function readChars($count = 1)
	{
		if(is_int($count))
		{
			if($count <= 0)
			{
				return '';
			}

			$result = '';

			for($i = 0; $i < $count; ++$i)
			{
				$result .= $this->readChar();
			}

			return $result;
		}

		return $this->readChar();
	}

	public function readChar()
	{
		return fgetc(IN);
	}

	public function readLines($count = 1)
	{
		if(is_int($count))
		{
			if($count <= 0)
			{
				return null;
			}

			$result = array($count);

			for($i = 0; $i < $count; ++$i)
			{
				$result[$i] = $this->readLine();
			}

			return $result;
		}

		return $this->readLine();
	}

	public function readLine()
	{
		return fgets(IN);
	}

	private function tryType($type)
	{
		if(!is_string($type) || $type === '')
		{
			return false;
		}
		else if(! (isset($this->session) && isset($this->session->connection)))
		{
			return false;
		}

		return $this->session->connection->setType($type);
	}

	public function text($data, $length = null, ... $args)
	{
		return $this->write($data, $length, KEKSE_CONTENT_TEXT, ... $args);
	}

	public function html($data, $length = null, ... $args)
	{
		return $this->write($data, $length, KEKSE_CONTENT_HTML, ... $args);
	}

	public function write($data, $length = null, $type = KEKSE_CONTENT_TYPE, ... $args)
	{
		$this->tryType($type);
		return self::swrite(1, $data, $length);
	}

	public function writeError($data, $length = null, $type = KEKSE_CONTENT_TYPE, ... $args)
	{
		$this->tryType($type);
		return self::swrite(2, $data, $length);
	}
	
	public static function swrite($stream, $data, $length = null, $throw = true)
	{
		switch($stream)
		{
			case 0:
			case 'stdin':
			case 'input':
			case 'in':
				return false;
			case 1:
			case 'stdout':
			case 'output':
			case 'out':
				return fwrite(OUTPUT, $data, $length);
			case 2:
			case 'stderr':
			case 'error':
			case 'err':
				return fwrite(ERROR, $data, $length);
		}

		if($throw)
		{
			throw new \Exception('Invalid $stream chosen');
		}

		return null;
	}

	public static function defineStreams($binary = KEKSE_STDIO_BINARY)
	{
		$input; $output; $error;
		
		if(self::isCLI())
		{
			$input = 'php://stdin';
			$output = 'php://stdout';
		}
		else
		{
			$input = 'php://input';
			$output = 'php://output';
		}

		$input = fopen($input, 'r' . ($binary ? 'b' : ''));
		$output = fopen($output, 'w' . ($binary ? 'b' : ''));
		$error = $output;
		
		define('INPUT', $input);
		define('OUTPUT', $output);
		define('ERROR', $error);
		
		if(!defined('STDIN')) define('STDIN', $input);
		if(!defined('STDOUT')) define('STDOUT', $output);
		if(!defined('STDERR')) define('STDERR', $error);
		
		return [ $input, $output, $error ];
	}
}

//
function parseJSON($value)
{
	return json_decode($value, true, KEKSE_LIMIT_JSON);
}

function renderJSON($string)
{
	return json_encode($string, 0, KEKSE_LIMIT_JSON);
}

function timestamp($diff = null)
{
	if(PHP_INT_SIZE < 8)
	{
		if(is_int($diff))
		{
			return (time() - $diff);
		}
		
		return time();
	}
	
	$result = explode(' ', microtime());
	$result = (intval($result[1] * 1E3) + intval(round($result[0] * 1E3)));
	
	if(is_int($diff))
	{
		return ($result - $diff);
	}
	
	return $result;
}

//
require_once(__DIR__ . '/constants.inc.php');
require_once(__DIR__ . '/debug.inc.php');

//
Quant::defineStreams();

//
require_once(__DIR__ . '/error.inc.php');
require_once(__DIR__ . '/session.inc.php');
require_once(__DIR__ . '/math.inc.php');
require_once(__DIR__ . '/numeric.inc.php');
require_once(__DIR__ . '/text.inc.php');
require_once(__DIR__ . '/environment.inc.php');
require_once(__DIR__ . '/security.inc.php');

//
if(!Quant::isCLI() && KEKSE_INSTANT_HEADER) try
{
	@header('Content-Type: ' . KEKSE_CONTENT_TYPE);
}
catch(\Throwable $err)
{
}

//
?>
