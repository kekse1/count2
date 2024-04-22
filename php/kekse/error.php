<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

const KEKSE_DEFAULT_EXIT_CODE = 255;

require_once(__DIR__ . '/main.php');
require_once(__DIR__ . '/filesystem.php');

class ERROR extends Quant
{
	public static $ext = '.log';

	protected $source = '';
	protected $targets = [];

	public function __construct($session = null, ... $args)
	{
		parent::__construct($session, ... $args);
		$this->initialize();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function kekseDebug()
	{
		if(defined('KEKSE_DEBUG') && is_bool(KEKSE_DEBUG))
		{
			return KEKSE_DEBUG;
		}

		if(isset($this->session) && isset($this->session->configuration))
		{
			return $this->session->configuration->get('debug');
		}

		return false;
	}

	public static function errorHandler($no, ... $args)
	{
		$error = (isset($GLOBALS['ERROR']) ? $GLOBALS['ERROR'] : null);

		if($error && $error->kekseDebug())
		{
			array_unshift($args, $no);
			$error->writeError(print_r($args, true), null, KEKSE_CONTENT_TEXT, true, false);
			exit(KEKSE_DEFAULT_EXIT_CODE);
		}
		
		if(!($no & E_WARNING))
		{
			return self::handler('Error', $no, ... $args);
		}

		return self::handler('Warning', $no, ... $args);
	}

	public static function exceptionHandler($throw, ... $args)
	{
		$error = (isset($GLOBALS['ERROR']) ? $GLOBALS['ERROR'] : null);

		if($error && $error->kekseDebug())
		{
			$result;

			if(count($args) > 0)
			{
				array_unshift($args, $throw);
				$result = args;
			}
			else
			{
				$result = $throw;
			}

			$error->writeError(print_r($result, true), null, KEKSE_CONTENT_TEXT, true, false);
			exit(KEKSE_DEFAULT_EXIT_CODE);
		}

		return self::handler('Exception',
			$throw->getCode(),
			$throw->getMessage(),
			$throw->getFile(),
			$throw->getLine());
	}

	public static function handler($type, $no, $msg, $file, $line)
	{
		$ret = function($r) use(&$type)
		{
			switch($type)
			{
				case 'Error':
				case 'Exception':
					exit(KEKSE_DEFAULT_EXIT_CODE);
				case 'Warning':
				default:
					break;
			}
			
			return $r;
		};

		$obj = null;
		$string = null;

		if(is_int($no))
		{
			$string = $file . ':' . $line . ' <' . $type . '/' . $no . '> ' . $msg;
		}
		else
		{
			$obj = [ $type, $no, $msg, $file, $line,
				'type' => $type,
				'no' => $no,
				'msg' => $msg,
				'file' => $file,
				'line' => $line ];
			$string = print_r($obj, true);
		}

		if(isset($GLOBALS['ERROR']))
		{
			$GLOBALS['ERROR']->put($string);
			return $ret(KEKSE_DEFAULT_EXIT_CODE);
		}
		
		if(!parent::isCLI())
		{
			@header('Content-Type: ' . (defined('KEKSE_CONTENT_TEXT') ? KEKSE_CONTENT_TEXT : 'text/plain;charset=UTF-8'));
		}
		else
		{
			$string .= PHP_EOL;
		}

		parent::swrite(2, $string);
		return $ret(KEKSE_DEFAULT_EXIT_CODE);
	}

	private static function makeLogFilePath($path)
	{
		if(!is_string($path))
		{
			return null;
		}

		$ext = Path::extname($path, 1);

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

	private function initialize()
	{
		if(!isset($this->session) && isset($this->session->environment))
		{
			$this->source = '';
			$this->targets = [];
			return false;
		}
		
		if(isset($this->session->environment->file['path']))
		{
			$this->source = $this->session->environment->file['path'];
		}
		else if(isset($this->session->environment->real['path']))
		{
			$this->source = $this->session->environment->real['path'];
		}
		else if(isset($this->session->environment->file['base']))
		{
			$this->source = $this->session->environment->file['base'];
		}
		else if(isset($this->session->environment->real['base']))
		{
			$this->source = $this->session->environment->real['base'];
		}
		else
		{
			$this->source = '';
		}

		if(isset($this->session->environment->real['dir']))
		{
			array_push($this->targets, self::makeLogFilePath($this->session->environment->real['path']));
		}

		if(isset($this->session->environment->file['dir']) && $this->session->environment->file['path'] !== $this->session->environment->real['path'])
		{
			array_push($this->targets, self::makeLogFilePath($this->session->environment->file['path']));
		}
	}

	//
	public function make($symlink = true, ... $args)
	{
		$result = timestamp();
		if($symlink) $result .= ' (' . $this->source . ')';
		if($args = sprintf(... $args)) $result .= ' ' . $args;
		return $result;
	}

	public function put(... $args)
	{
		$this->put2user(... $args);
		$this->put2file(... $args);
	}

	public function put2user($data)
	{
		return $this->writeError($this->make(false, $data), null, KEKSE_CONTENT_TEXT, true, false);
	}

	public function put2file($data)
	{
		if(!$this->targets) return false;
		
		if(isset($this->targets[0]))
		{
			self::appendToFile($this->targets[0], $this->make(true, $data) . PHP_EOL);
		}
		
		if(isset($this->targets[1]))
		{
		       	self::appendToFile($this->targets[1], $this->make(false, $data) . PHP_EOL);
		}
		
		return true;
	}

	public static function appendToFile($path, $string)
	{
		return FileSystem::appendFile($path, $string);
	}
}

set_error_handler('\kekse\ERROR::errorHandler');
set_exception_handler('\kekse\ERROR::exceptionHandler');

?>
