<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('security.inc.php');

define('KEKSE_KEEP_HIDDEN', true);
define('KEKSE_KEEP_HTACCESS', true);
define('KEKSE_KEEP', true);

class FileSystem extends \kekse\Quant
{
	public function __construct(... $args)
	{
		return parent::__construct('FileSystem', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
	
	public static function is_file($path, $read = true, $write = false)
	{
		if(!is_file($path)) return false;
		else if($read && !is_readable($path)) return false;
		else if($write && !is_writable($path)) return false;
		return true;
	}
	
	public static function is_dir($path, $read = true, $write = false)
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
	
	public static function joinPath(... $args)
	{
		return implode(PATH_SEPARATOR, $args);
	}
}

?>
