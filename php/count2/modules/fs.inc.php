<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

	//glob()
	//FilesystemIterator
	//fopen(), fclose(); f{read,write,seek}();
	//file_{get,put}_contents();
	//flock()
	//ftruncate(), unlink()

	// https://www.php.net/manual/de/function.flock.php
	//$fh = fopen($path, 'w');
	//flock($fh, LOCK_EX);
	//fwrite($fh, $data);
	//flock($fh, LOCK_UN);
	//fclose(fh);

	class FileSystem
	{
		static public function list($path)
		{
			//scandir();
		}
	}

?>
