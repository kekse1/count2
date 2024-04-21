<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;
	 
//
require_once(__DIR__ . '/constants.php');
require_once(__DIR__ . '/debug.php');

//
class IO
{
	private $input = 0;//TODO/!
	private $output = 0;
	private $error = 0;

	public function getBytes()
	{
		return [
			'in' => $this->input,
			'out' => ($this->output + $this->error),
			'input' => $this->input,
			'output' => $this->output,
			'error' => $this->error
		];
	}

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

	public static function isCLI()
	{
		return (php_sapi_name() === 'cli');
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

	public function text($data, $length = null)
	{
		return $this->write($data, $length, KEKSE_CONTENT_TEXT);
	}

	public function html($data, $length = null)
	{
		return $this->write($data, $length, KEKSE_CONTENT_HTML);
	}

	public function hasConnection()
	{
		return (isset($this->session) && isset($this->session->connection));
	}

	public function write($data, $length = null, $type = KEKSE_CONTENT_TYPE)
	{
		if($this->hasConnection())
		{
			return $this->session->connection->write($data, $length, $type);
		}

		return self::swrite(1, $data, $length, $this);
	}

	public function writeError($data, $length = null, $type = KEKSE_CONTENT_TYPE)
	{
		if($this->hasConnection())
		{
			return $this->session->connection->write($data, $length, $type);
		}

		return self::swrite(2, $data, $length, $this);
	}
	
	public static function swrite($stream, $data, $length = null, $quant = null)
	{
		if(!is_int($length) || $length < 0)
		{
			$length = strlen($data);
		}

		$result = null;

		switch($stream)
		{
			case 0:
			case 'stdin':
			case 'input':
			case 'in':
				$stream = 'input';
				$result = false;
				break;
			case 1:
			case 'stdout':
			case 'output':
			case 'out':
				$stream = 'output';
				$result = fwrite(OUTPUT, $data, $length);
				if($result !== false && $quant !== null)
				{
					$quant->output += $result;
				}
				break;
			case 2:
			case 'stderr':
			case 'error':
			case 'err':
				$stream = 'error';
				$result = fwrite(ERROR, $data, $length);
				if($result !== false && $quant !== null)
				{
					$quant->error += $result;
				}
				break;
			default:
				$result = false;
				break;
		}

		return $result;
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
IO::defineStreams();

//
?>
