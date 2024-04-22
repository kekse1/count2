<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
define('KEKSE_LIMIT_STRING', 256);
define('KEKSE_LIMIT_JSON', 8);
define('KEKSE_LIMIT_PARAM', 64);

//connection.php
define('KEKSE_CONNECTION_BUFFER', true);
define('KEKSE_CONNECTION_RESET', true);

//"all"
define('KEKSE_THROW_CONNECTION', false);
define('KEKSE_THROW_MATH', true);
define('KEKSE_THROW_NUMERIC', false);

//numeric.php
define('KEKSE_NUMERIC_PARSE', 'break');//['continue','break','null']//TODO @ render()!???

//filesystem.php
define('KEKSE_KEEP', true);
define('KEKSE_KEEP_HIDDEN', true);
define('KEKSE_KEEP_GIT', true);
define('KEKSE_KEEP_HTACCESS', true);

define('KEKSE_MODE_FILE', 384);//0600
define('KEKSE_MODE_DIR', 448);//0700

define('KEKSE_FILE_CHUNK', 4096);

define('KEKSE_STDIO_BINARY', true);

define('KEKSE_CONTENT_TEXT', 'text/plain;charset=UTF-8');
define('KEKSE_CONTENT_HTML', 'text/html;charset=UTF-8');
define('KEKSE_CONTENT_TYPE', KEKSE_CONTENT_TEXT);

// math.php
define('KEKSE_UNIT_STRING', true);
define('KEKSE_UNIT_BASE', 1024);
define('KEKSE_UNIT_PRECISION', 2);
define('KEKSE_UNIT_1024', [ 'Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB' ]);
define('KEKSE_UNIT_1000', [ 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ]);

// path.php
define('KEKSE_PATH_EXT', 1);
// path.php && filesystem.php
define('KEKSE_PATH_REAL', false);

// parameter.php
// environment.php
define('KEKSE_CAST', true);

?>
