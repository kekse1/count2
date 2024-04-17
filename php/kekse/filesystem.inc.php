<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/main.inc.php');
require_once(__DIR__ . '/path.inc.php');
//require_once(__DIR__ . '/constants.inc.php');
//require_once(__DIR__ . '/security.inc.php');

//
class FileSystem extends Quant
{
	public $root = null;

	public function __construct($session = null, $root = true, $real = KEKSE_PATH_REAL, $writable = false, $mode = null, ... $args)
	{
		parent::__construct($session, ... $args);

		if($root === true)
		{
			$root = Path::getRoot();
		}

		if(is_string($root))
		{
			$this->setRoot($root, $real, $writable, $mode);
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

	public function setRoot($path, $real = KEKSE_PATH_REAL, $writable = false, $mode = null)
	{
		if(!is_string($path))
		{
			return null;
		}
		else
		{
			$path = Path::resolve($path);
		}

		if($real)
		{
			$path = realpath($path);
		}

		if(!self::isDirectory($path, true, true, $writable))
		{
			if(file_exists($path))
			{
				throw new \Exception('Invalid root directory: path exists, but ain\'t a directory, or you can\'t access it.');
			}
			else if(!is_int($mode))
			{
				throw new \Exception('Root directory doesn\'t exist (try setting $mode argument to create it)');
			}

			self::makeDirectory($path, $mode, true);
		}
		else if(is_int($mode))
		{
			self::changeDirectoryMode($path, $mode);
		}

		if($path[strlen($path) - 1] !== DIRECTORY_SEPARATOR)
		{
			$path .= DIRECTORY_SEPARATOR;
		}

		$this->root = $path;
		return true;
	}

	public function path($path, $real = KEKSE_PATH_REAL, $exists = false)
	{
		if(!is_string($path))
		{
			return null;
		}
		else
		{
			$path = Path::resolve($path);
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

	public function check($path, $real = KEKSE_PATH_REAL, $exists = false)
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

		return Text::startsWith($path, $this->root, true);
	}

	public static function appendFile($path, $data_or_callback, $mode = 'a', $chunk = KEKSE_FILE_CHUNK)
	{
		if(!is_string($mode))
		{
			$mode = 'a';
		}
		
		return self::writeFile($path, $data_or_callback, $mode, $chunk);
	}
	
	public static function writeFile($path, $data_or_callback, $mode = 'c', $chunk = KEKSE_FILE_CHUNK)
	{
		if(!is_string($path))
		{
			return null;
		}
		
		$data; $callback;
		
		if(is_callable($data_or_callback))
		{
			$data = null;
			$callback = $data_or_callback;
		}
		else if(is_string($data_or_callback))
		{
			$data = $data_or_callback;
			$callback = null;
		}
		else
		{
			return false;
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
		
		if(!is_string($mode))
		{
			$mode = 'c';
		}

		$size = ($data === null ? 0 : strlen($data));
		
		if($data !== null && $chunk === 0)
		{
			$chunk = $size;
		}
		
		$fh = fopen($path, $mode);
		
		if($fh === false)
		{
			return false;
		}
		else
		{
			flock($fh, LOCK_EX);
		}
		
		if($mode !== 'a')
		{
			ftruncate($fh, 0);
		}

		$written = 0;
		$chunks = 0;
		
		$write = function($data) use($chunk, &$chunks, &$written, &$fh)
		{
			$len = strlen($data);
			$rest = $len;
			$pos = 0;
			$c = min($rest, $chunk);
			$w = 0;

			while($rest > 0)
			{
				$c = min($c, $rest);
				$d = substr($data, $pos, $c);
				$r = fwrite($fh, $d);
				
				if($r === false)
				{
					fclose($fh);
					return false;
				}
				
				$pos += $r;
				$rest -= $r;
				++$chunks;
			}
			
			$written += $len;
			return true;
		};
		
		if($data === null)
		{
			do
			{
				$data = $callback();
				
				if(is_string($data) && $data !== '')
				{
					if(!$write($data))
					{
						break;
					}
				}
				else
				{
					break;
				}
			}
			while(true);
		}
		else
		{
			$write($data);
		}
		
		if($callback)
		{
			return $written;
		}
		
		return $chunks;
	}

	public static function readFile($path, $callback = null, $chunk = KEKSE_FILE_CHUNK)
	{
		if(!is_string($path))
		{
			return null;
		}
		else if(!self::isFile($path))
		{
			return null;
		}
		
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
			return false;
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
		return @chmod($path, $mode);
	}

	public static function changeFileMode($path, $mode = KEKSE_MODE_FILE)
	{
		return self::changeMode($path, $mode);
	}
	
	public static function changeDirectoryMode($path, $mode = KEKSE_MODE_DIR)
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
					$res = delete(Path::join($path, $sub), $depth, ($extended === null ? null : true), $currentDepth + 1);
					
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
}

?>