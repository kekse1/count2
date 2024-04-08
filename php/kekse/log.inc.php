<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

class Log extends Quant
{
	public static $ext = '.log';

	private $env = null;

	protected $source = '';
	protected $targets = [];

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

	public static function errorHandler(... $args)
	{
		return self::handler('error', ... $args);
	}

	public static function exceptionHandler(... $args)
	{
		return self::handler('exception', ... $args);
	}

	public static function handler($type, ... $args)
	{
		var_dump($args);
		//\kekse\Terminal::error(... $args);
		
		switch($type)
		{
			case 'error':
				exit(255);
			case 'exception':
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
	}

	//
	public function create(... $args)
	{
		$result = '[' . timestamp() . ']';

		if($this->source)
		{
			$result .= '(' . $this->source . ')';
		}

		return ($result . ' ' . sprintf(... $args));
	}

	public function log(... $args)
	{
		$this->log2console(... $args);
		return $this->log2file(... $args);
	}

	public function log2console(... $args)
	{
		if(! ($this->session && isset($this->session->console)))
		{
			return fprintf(STDERR, $this->create(... $args) . PHP_EOL);
		}

		return $this->session->console->log(... $args);
	}

	public function log2file(... $args)
	{
		$string = $this->create(... $args);
		$result = 0;

		foreach($this->targets as &$path)
		{
			self::appendToFile($path, $string);
		}

		return $result;
	}

	public static function appendToFile($path, $string)
	{
throw new \Error('TODO @ filesystem.inc.php, w/ callback & chunks, etc.. and FILE LOCK & APPEND!!!!');
	}
}

set_error_handler('\kekse\Log::errorHandler');
set_exception_handler('\kekse\Log::exceptionHandler');

?>
