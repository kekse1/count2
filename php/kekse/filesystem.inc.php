<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

define('KEKSE_KEEP', true);
define('KEKSE_KEEP_HIDDEN', true);
define('KEKSE_KEEP_GIT', true);
define('KEKSE_KEEP_HTACCESS', true);

define('KEKSE_MODE_FILE', 0600);
define('KEKSE_MODE_DIR', 0700);

define('KEKSE_FILE_CHUNK', 4096);
define('KEKSE_JSON_DEPTH', 8);

class FileSystem extends Quant
{
	public function __construct(... $args)
	{
		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function readFile($path, $callback = null, $chunk = KEKSE_FILE_CHUNK)
	{
		if(!FileSystem::isFile($path))
		{
			return false;
		}
		else if(!is_callable($callback))
		{
			$callback = null;
		}

		$size = filesize($path);

		if($size === false)
		{
			return null;
		}
		else if($size === 0)
		{
			if($callback !== null)
			{
				$callback('', true, 0);
				return 0;
			}

			return '';
		}
		
		if(!is_int($chunk) || $chunk < 1)
		{
			if($chunk === true)
			{
				$chunk = KEKSE_FILE_CHUNK;
			}
			else
			{
				$chunk = $size;
			}
		}

		$fh = fopen($path, 'r');

		if($fh === false)
		{
			return null;
		}

		$data = ($callback === null ? '' : null);
		$read = 0;
		$chunks = 0;
		$fin = false;

		while($read < $size)
		{
			$d = fread($fh, $chunk);

			if($d === false)
			{
				return null;
			}

			++$chunks;
			$len = strlen($d);
			$read += $len;

			if($read >= $size)
			{
				$fin = true;
			}

			if($data === null)
			{
				$callback($d, $fin, $size);
			}
			else
			{
				$data .= $d;
			}
		}

		if($callback)
		{
			return $chunks;
		}

		return $data;
	}
	
	public static function isFile($path, $read = true, $write = false)
	{
		if(!is_file($path)) return false;
		else if($read && !is_readable($path)) return false;
		else if($write && !is_writable($path)) return false;
		return true;
	}
	
	public static function isDirectory($path, $read = true, $write = false)
	{
		if(!is_dir($path)) return false;
		else if($read && !is_readable($path)) return false;
		else if($write && !is_writable($path)) return false;
		return true;
	}
	
	public static function delete($path, $depth = 0, $extended = false, $currentDepth = 0)
	{
		if(Security::checkString($path, true, true))
		{
			if(!is_link($path))
			{
				$real = realpath($path);
				
				if($real === false)
				{
					if($extended === true)
					{
						return [ 0, 0, 0, 0 ];
					}
					else if($extended === null)
					{
						return [ [], [], [], [] ];
					}
					
					return false;
				}
				else if($real === DIRECTORY_SEPARATOR)
				{
					if($extended === true)
					{
						return [ 0, 0, 0, 0 ];
					}
					else if($extended === null)
					{
						return [ [ $path ], [], [], [ $path ] ];
					}
					
					return null;
				}
			}
		}
		else if($extended === true)
		{
			return [ 0, 0, 0, 0 ];
		}
		else if($extended === null)
		{
			return [ [], [], [], [] ];
		}
		else
		{
			return false;
		}
		
		if($depth === true)
		{
			$depth = null;
		}
		else if($depth === false)
		{
			$depth = 0;
		}
		else if($depth !== null && !(is_int($depth) && $depth >= 0))
		{
			$depth = 0;
		}
		
		$basename = basename($path);
		
		if(KEKSE_KEEP_HIDDEN && $basename[0] === '.')
		{
			if($extended === true)
			{
				return [ 1, 0, 0, 1 ];
			}
			else if($extended === null)
			{
				return [ [ $path ], [], [], [ $path ] ];
			}
			
			return null;
		}
		else if(KEKSE_KEEP_GIT && $basename === '.git')
		{
			if($extended === true)
			{
				return [ 1, 0, 0, 1 ];
			}
			else if($extended === null)
			{
				return [ [ $path ], [], [], [ $path ] ];
			}

			return null;
		}
		else if(KEKSE_KEEP_HTACCESS && $basename === '.htaccess')
		{
			if($extended === true)
			{
				return [ 1, 0, 0, 1 ];
			}
			else if($extended === null)
			{
				return [ [ $path ], [], [], [ $path ] ];
			}
			
			return null;
		}
		else if(KEKSE_KEEP && $basename === '.keep')
		{
			if($extended === true)
			{
				return [ 1, 0, 0, 1 ];
			}
			else if($extended === null)
			{
				return [ [ $path ], [], [], [ $path ] ];
			}
			
			return null;
		}
		else if($depth !== null && $currentDepth > $depth)
		{
			if($extended === true)
			{
				return [ 1, 0, 0, 1 ];
			}
			else if($extended === null)
			{
				return [ [ $path ], [], [], [ $path ] ];
			}
			
			return null;
		}
		
		if(is_link($path))
		{
			$d = 0;
			$f = 0;
			
			if(unlink($path))
			{
				++$d;
			}
			else
			{
				++$f;
			}
			
			if($extended === true)
			{
				return [ 1, $d, $f, 0 ];
			}
			else if($extended === null)
			{
				return [ [ $path ], ($d === 0 ? [] : [ $path ]), ($f === 0 ? [] : [ $path ]), [] ];
			}
			
			return ($f === 0);
		}
		else if(is_dir($path))
		{
			if($depth !== null && $currentDepth >= $depth)
			{
				$d = 0;
				$f = 0;
				
				if(rmdir($path))
				{
					++$d;
				}
				else
				{
					++$f;
				}
				
				if($extended === true)
				{
					return [ 1, $d, $f, 0 ];
				}
				else if($extended === null)
				{
					return [ [ $path ], ($d === 0 ? [] : [ $path ]), ($f === 0 ? [] : [ $path ]), [] ];
				}
				
				return ($f === 0);
			}
			
			$handle = opendir($path);
			
			if($handle === false)
			{
				if($extended === true)
				{
					return [ 1, 0, 1, 0 ];
				}
				else if($extended === null)
				{
					return [ [ $path ], [], [ $path ], [] ];
				}
				
				return false;
			}
			
			$total;
			$deleted;
			$failed;
			$ignored;
			
			if($extended === null)
			{
				$total = [];
				$deleted = [];
				$failed = [];
				$ignored = [];
			}
			else
			{
				$total = 0;
				$deleted = 0;
				$failed = 0;
				$ignored = 0;
			}
			
			while($sub = readdir($handle))
			{
				if($sub !== '.' && $sub !== '..')
				{
					$res = delete(FileSystem.joinPath($path, $sub), $depth, ($extended === null ? null : true), $currentDepth + 1);
					
					if($extended === null)
					{
						array_push($total, $res[0]);
						array_push($deleted, $res[1]);
						array_push($failed, $res[2]);
						array_push($ignored, $res[3]);
					}
					else
					{
						$total += $res[0];
						$deleted += $res[1];
						$failed += $res[2];
						$ignored += $res[3];
					}
				}
			}
			
			closedir($handle);
			
			if($extended === null)
			{
				array_push($total, $path);
			}
			else
			{
				++$total;
			}
			
			$f = ($extended === null ? count($failed) : $failed);
			$i = ($extended === null ? count($ignored) : $ignored);
			
			if($f === 0 && $i === 0)
			{
				if(rmdir($path))
				{
					if($extended === false)
					{
						return true;
					}
					else if($extended === null)
					{
						array_push($deleted, $path);
					}
					else
					{
						++$deleted;
					}
				}
				else
				{
					if($extended === false)
					{
						return false;
					}
					else if($extended === null)
					{
						array_push($failed, $path);
					}
					else
					{
						++$failed;
					}
				}
			}
			else if($extended === false)
			{
				return ($ignored > 0 ? null : false);
			}
			else if($i > 0)
			{
				if($extended === null)
				{
					array_push($ignored, $path);
				}
				else
				{
					++$ignored;
				}
			}
			else if($extended === null)
			{
				array_push($failed, $path);
			}
			else
			{
				++$failed;
			}
			
			return [ $total, $deleted, $failed, $ignored ];
		}
		
		$d = 0;
		$f = 0;
		
		if(unlink($path))
		{
			++$d;
		}
		else
		{
			++$f;
		}
		
		if($extended === true)
		{
			return [ 1, $d, $f, 0 ];
		}
		else if($extended === null)
		{
			return [ [ $path ], ($d === 0 ? [] : [ $path ]), ($f === 0 ? [] : [ $path ]), [] ];
		}
		
		return ($f === 0);
	}

	public static function resolvePath(... $args)
	{
		$origin;

		if(!!$_SERVER['DOCUMENT_ROOT'])
		{
			$origin = $_SERVER['DOCUMENT_ROOT'];
		}
		else
		{
			$origin = getcwd();
		}

		return self::joinPath($origin, ... $args);
	}
	
	public static function joinPath(... $args)
	{
		$result = implode(DIRECTORY_SEPARATOR, $args);
		return self::normalizePath($result);
	}
	
	public static function normalizePath($path)
	{
		if(!is_string($path))
		{
			return null;
		}
		else
		{
			$path = Security::checkString($path, true, true);
		}
		
		$len = strlen($path);
		
		if($len === 0 || $path === '.')
		{
			return '.';
		}
		else if($len > KEKSE_LIMIT_STRING)
		{
			return null;
		}
		
		$abs = ($path[0] === DIRECTORY_SEPARATOR);
		$dir = ($path[$len - 1] === DIRECTORY_SEPARATOR);
		$split = explode(DIRECTORY_SEPARATOR, $path);
		$result = [];
		$minus = 0;
		$item = '';
		
		while(count($split) > 0)
		{
			$item = array_shift($split);
			
			if(!$item)
			{
				continue;
			}
			
			switch($item)
			{
				case '.': break;
				case '..':
					if(count($result) === 0)
					{
						++$minus;
					}
					else
					{
						array_pop($result);
					}
					break;
				default:
					array_push($result, $item);
					break;
			}
		}
		
		if($abs)
		{
			array_unshift($result, '');
		}
		else while(--$minus >= 0)
		{
			array_unshift($result, '..');
		}
		
		if($dir)
		{
			array_push($result, '');
		}
		
		return implode(DIRECTORY_SEPARATOR, $result);
	}
}

?>
