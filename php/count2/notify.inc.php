<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//TODO/haengt stark mit 'event.inc.php' zusammen!! vice versa. @ rpc-styles..

namespace kekse\count2;

class Notify extends \kekse\Quant
{
	public function __construct($session, ... $args)
	{
		parent::__construct($session, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

?>
