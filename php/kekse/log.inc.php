<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

require_once(__DIR__ . '/main.inc.php');
require_once(__DIR__ . '/filesystem.inc.php');

class Log extends Quant
{
	public static $ext = '.log';

	private $env = null;

	protected $source = '';
	protected $targets = [];
	protected $relative;

	public function __construct($session = null, $env = null, ... $args)
	{
		parent::__construct($session, ... $args);

		$this->env = $env;
		$this->init();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public static function errorHandler($no, ... $args)
	{
		if(!($no & E_WARNING))
		{
			return self::handler('Error', $no, ... $args);
		}
	}

	public static function exceptionHandler(... $args)
	{
		return self::handler('Exception', ... $args);
	}

	public static function handler($type, $object, ... $args)
	{
		$text = '';
		
		if(is_object($object))
		{
			$text = $object->getFile() . ':' . $object->getLine() . ' <' . $type . '/' . $object->getCode() . '> ' . $object->getMessage();
		}
		else if(is_array($args[0]))
		{
			$args = $args[0];
			$text = $args[1] . ':' . $args[2] . ' <' . $type . '> ' . $args[0];
		}
		else
		{
			$text = print_r($args, true);
		}

		if(!isset($GLOBALS['log']))
		{
			if(php_sapi_name() === 'cli')
			{
				fprintf(STDERR, $text . PHP_EOL);
			}
			else
			{
				@header('Content-Type: text/plain; charset=UTF-8');
				echo $text;
			}
		}
		else
		{
			$GLOBALS['log']->log($text);
		}

		//
		switch($type)
		{
			case 'Error':
				exit(255);
			case 'Exception':
				break;
		}
	}

	private static function makeLogFilePath($path)
	{
		if(!is_string($path))
		{
			return null;
		}

		$ext = FileSystem::extname($path, 1);

		if($ext === '.php')
		{
			$path = substr($path, 0, -strlen($ext));
		}

		if($ext !== self::$ext)
		{
			$path .= self::$ext;
		}

		return $path;
	}

	private function init()
	{
		if(!$this->env)
		{
			$this->source = '';
			$this->targets = [];
			return false;
		}
		
		if(isset($this->env->file['file']))
		{
			$this->source = $this->env->file['file'];
		}
		else if(isset($this->env->real['file']))
		{
			$this->source = $this->env->real['file'];
		}
		else if(isset($this->env->file['base']))
		{
			$this->source = $this->env->file['base'];
		}
		else if(isset($this->env->real['base']))
		{
			$this->source = $this->env->real['base'];
		}
		else
		{
			$this->source = '';
		}

		if(isset($this->env->real['dir']))
		{
			array_push($this->targets, self::makeLogFilePath($this->env->real['file']));
		}

		if(isset($this->env->file['dir']))
		{
			array_push($this->targets, self::makeLogFilePath($this->env->file['file']));
		}

		$this->relative = FileSystem::relative($this->env->real['dir'], $this->source);
	}

	//
	public function make($relative = true, ... $args)
	{
		$result = timestamp();
		if($relative) $result .= ' (' . $this->relative . ')';
		if($args = sprintf(... $args)) $result .= ' ' . $args;
		return $result;
	}

	public function log(... $args)
	{
		$this->log2stdout(... $args);
		$this->log2file(... $args);
	}

	public function log2stdout(... $args)
	{
		$result = $this->make(false, ... $args);

		if(php_sapi_name() === 'cli')
		{
			fprintf(STDERR, $result . PHP_EOL);
		}
		else
		{
			@header('Content-Type: text/plain; charset=UTF-8');
			echo $result . PHP_EOL;
		}

		return $this->session->console->log($result);
	}

	public function log2file(... $args)
	{
		if(!$this->targets) return false;
		if(isset($this->targets[0])) self::appendToFile($this->targets[0], $this->make(true, ... $args) . PHP_EOL);
		if(isset($this->targets[1])) self::appendToFile($this->targets[1], $this->make(false, ... $args) . PHP_EOL);
		return true;
	}

	public static function appendToFile($path, $string)
	{
		return FileSystem::appendFile($path, $string);
	}
}

set_error_handler('\kekse\Log::errorHandler');
set_exception_handler('\kekse\Log::exceptionHandler');

?>
