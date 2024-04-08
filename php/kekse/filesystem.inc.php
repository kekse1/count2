<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse;

//
const DEFAULT_FILESYSTEM_REAL_PATH = false;

//
class FileSystem extends Quant
{
	public $root = null;

	public function __construct($session = null, $root = true, $real = DEFAULT_FILESYSTEM_REAL_PATH, $writable = false, ... $args)
	{
		parent::__construct($session, ... $args);

		if($root === true)
		{
			$root = self::getRoot();
		}

		if(is_string($root))
		{
			$this->setRoot($root, $real, $writable);
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function __toString()
	{
		if(is_string($this->root))
		{
			return '(' . $this->root . ')';
		}

		return parent::__toString();
	}

	public function setRoot($path, $real = DEFAULT_FILESYSTEM_REAL_PATH, $writable = false, $mode = null)
	{
		if(!is_string($path))
		{
			return null;
		}
		else
		{
			$path = self::resolve($path);
		}

		if($real)
		{
			$path = realpath($path);
		}

		if(!self::isDirectory($path, true, true, $writable))
		{
			if(file_exists($path))
			{
				throw new \Exception('Invalid root directory: path exists, but ain\'t a directory.');
			}
			else if(!is_int($mode))
			{
				throw new \Exception('Root directory doesn\'t exist (try setting $mode argument to create it)');
			}

			self::makeDirectory($path, $mode, true);
		}

		if($path[strlen($path) - 1] !== DIRECTORY_SEPARATOR)
		{
			$path .= DIRECTORY_SEPARATOR;
		}

		$this->root = $path;
		return true;
	}

	public function path($path, $real = DEFAULT_FILESYSTEM_REAL_PATH, $exists = false)
	{
		if(!is_string($path))
		{
			return null;
		}
		else
		{
			$path = self::resolve($path);
		}

		if($real)
		{
			$path = realpath($path);
		}

		if($exists && !file_exists($path))
		{
			return null;
		}
		else if(is_string($this->root) && !$this->check($path))
		{
			return null;
		}

		return $path;
	}

	public function check($path, $real = DEFAULT_FILESYSTEM_REAL_PATH, $exists = false)
	{
		$path = $this->path($path, $real, $exists);

		if($path === null)
		{
			return false;
		}
		else if(!is_string($this->root))
		{
			return true;
		}
		else if($path === $this->root)
		{
			return true;
		}

		return str_starts_with($path, $this->root);
	}

	public static function secure($path)
	{
		return Security::secure($path, 'path');
	}

	public static function appendToFile($path, $data_or_callback, $chunk = KEKSE_FILE_CHUNK)
	{
throw new \Error('TODO');
	}

	public static function readFile($path, $callback = null, $chunk = KEKSE_FILE_CHUNK)
	{
		if(!FileSystem::isFile($path))
		{
			return false;
		}
		/*else
		{
			$path = self::secure($path);
		}*/

		if(!is_callable($callback))
		{
			$callback = null;
		}

		if(!is_int($chunk) || $chunk < 1)
		{
			if($chunk === true)
			{
				$chunk = KEKSE_FILE_CHUNK;
			}
			else
			{
				$chunk = 0;
			}
		}

		$fh = fopen($path, 'r');

		if($fh === false)
		{
			return null;
		}

		$size = fstat($fh)['size'];

		if($size === 0)
		{
			if($callback !== null)
			{
				$callback('', true, 0);
				return 0;
			}

			return '';
		}
		else if($chunk === 0)
		{
			$chunk = $size;
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

		fclose($fh);

		if($callback)
		{
			return $chunks;
		}

		return $data;
	}
	
	public static function exists($path)
	{
		if(!is_string($path) || !file_exists($path)) return false;
		return true;
	}

	public static function isFile($path, $readable = true, $writable = false)
	{
		if(!is_string($path) || !is_file($path)) return false;
		else if($readable && !is_readable($path)) return false;
		else if($writable && !is_writable($path)) return false;
		return true;
	}
	
	public static function isDirectory($path, $executable = true, $readable = true, $writable = false)
	{
		if(!is_string($path) || !is_dir($path)) return false;
		else if($executable && !is_executable($path)) return false;
		else if($readable && !is_readable($path)) return false;
		else if($writable && !is_writable($path)) return false;
		return true;
	}

	public static function makeDirectory($path, $mode = KEKSE_MODE_DIR, $recursive = true)
	{
		if(!is_string($path) || file_exists($path)) return false;
		return mkdir($path, $mode, $recursive);
	}

	public static function changeMode($path, $mode)
	{
		if(!is_string($path) || !file_exists($path)) return false;
		return chmod($path, $mode);
	}

	public static function changeFileMode($path, $mode = KEKSE_MODE_FILE)
	{
		return self::changeMode($path, $mode);
	}
	
	public static function changeDirMode($path, $mode = KEKSE_MODE_DIR)
	{
		return self::changeMode($path, $mode);
	}
	
	public static function delete($path, $depth = 0, $extended = false, $currentDepth = 0)
	{
		if(Security::checkString($path, true))
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
					$res = delete(FileSystem.join($path, $sub), $depth, ($extended === null ? null : true), $currentDepth + 1);
					
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

	public static function getRoot($real = true)
	{
		$result;

		if(isset($_SERVER['DOCUMENT_ROOT']))
		{
			$result = $_SERVER['DOCUMENT_ROOT'];
		}
		else
		{
			$result = getcwd();
		}

		if(!$result)
		{
			$result = '.';
		}

		if($real)
		{
			$result = realpath($result);
		}

		return $result;
	}

	public static function resolve(... $args)
	{
		$origin = self::getRoot();
		$len = count($args);
		$rem = 0;

		for($i = $len - 1; $i >= 0; --$i)
		{
			if(!is_string($args[$i]) || $args[$i] === '')
			{
				array_splice($args, $i, 1);
				--$len;
			}
		}

		if($len === 0)
		{
			return $origin;
		}
		else if(is_string($args[0]) && $args[0] !== '' && $args[0] !== $origin)
		{
			$len1 = strlen($args[0]);
			$len2 = strlen($origin);

			$argWith;
			$originWith;

			if($args[0][$len1 - 1] === DIRECTORY_SEPARATOR)
			{
				$argWith = $args[0];
			}
			else
			{
				$argWith = substr($args[0], 0, -1);
			}

			if($origin[$len2 - 1] === DIRECTORY_SEPARATOR)
			{
				$originWith = $origin;
			}
			else
			{
				$originWith = substr($origin, 0, -1);
			}

			if(str_starts_with($argWith, $originWith) || str_starts_with($originWith, $argWith))
			{
				array_unshift($args, $origin);
			}
		}

		return self::join(... $args);
	}
	
	public static function join(... $args)
	{
		$len = count($args);

		for($i = $len - 1; $i >= 0; --$i)
		{
			if(!is_string($args[$i]) || $args[$i] === '')
			{
				array_splice($args, $i, 1);
				--$len;
			}
		}

		if($len === 0)
		{
			return '.';
		}

		return self::normalize(implode(DIRECTORY_SEPARATOR, $args));
	}
	
	public static function normalize($path)
	{
		if(!is_string($path))
		{
			return null;
		}
		else if(!($path = Security::checkString($path, true)))
		{
			return null;
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

	public static function extname($path, $count = 1)
	{
		if(!is_string($path))
		{
			return null;
		}
		else if(!($path = Security::checkString($path, true)))
		{
			return null;
		}
		else if(!is_int($count))
		{
			$count = 1;
		}

		$rev = ($count < 0);
		$count = abs($count);
		$split = explode(DIRECTORY_SEPARATOR, $path);
		$len = count($split);

		for($i = $len - 1; $i >= 0; --$i)
		{
			if(strlen($split[$i]) === 0)
			{
				array_pop($split);
			}
			else
			{
				break;
			}
		}

		if(($len = count($split)) === 0)
		{
			return '';
		}

		$split = explode('.', array_pop($split));

		if(($len = count($split)) === 0)
		{
			return '';
		}
		else if(strlen($split[0]) === 0)
		{
			array_shift($split);
			--$len;
		}

		array_shift($split);
		
		if(--$len === 0)
		{
			return '';
		}
		else if($count > $len)
		{
			$count = $len;
		}

		$result;

		if($count === 0)
		{
			$result = $split;
		}
		else
		{
			$result = [];
		}

		if($count !== 0)
		{
			if($rev) for($i = 0; $i < $len && $i < $count; ++$i)
			{
				$result[$i] = $split[$i];
			}
			else for($i = $len - $count, $j = 0; $i < $len && $j < $count; ++$i, ++$j)
			{
				$result[$j] = $split[$i];
			}
		}

		if(count($result) === 0)
		{
			return '';
		}

		return ('.' . implode('.', $result));
	}
}

?>
