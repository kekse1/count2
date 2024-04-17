<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
define('KEKSE_LIMIT_STRING', 255);
define('KEKSE_LIMIT_JSON', 8);
define('KEKSE_LIMIT_PARAM', 32);

//filesystem.inc.php
define('KEKSE_KEEP', true);
define('KEKSE_KEEP_HIDDEN', true);
define('KEKSE_KEEP_GIT', true);
define('KEKSE_KEEP_HTACCESS', true);

define('KEKSE_MODE_FILE', 384);//0600
define('KEKSE_MODE_DIR', 448);//0700

define('KEKSE_FILE_CHUNK', 4096);

define('KEKSE_STDIO_BINARY', true);

define('KEKSE_INSTANT_HEADER', false);
define('KEKSE_CONTENT_TEXT', 'text/plain; charset=UTF-8');
define('KEKSE_CONTENT_HTML', 'text/html; charset=UTF-8');
define('KEKSE_CONTENT_TYPE', KEKSE_CONTENT_TEXT);

// math.inc.php
define('KEKSE_UNIT_STRING', true);
define('KEKSE_UNIT_BASE', 1024);
define('KEKSE_UNIT_PRECISION', 2);
define('KEKSE_UNIT_1024', [ 'Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB' ]);
define('KEKSE_UNIT_1000', [ 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ]);

// path.inc.php
define('KEKSE_PATH_EXT', 1);
// path.inc.php && filesystem.inc.php
define('KEKSE_PATH_REAL', false);

?>
