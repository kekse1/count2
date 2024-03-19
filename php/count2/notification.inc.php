<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//TODO/haengt stark mit 'event.inc.php' zusammen!! vice versa. @ rpc-styles..

namespace kekse\count2;

require_once('kekse/quant.inc.php');

class Notification extends \kekse\Quant
{
	public function __construct(... $args)
	{
		return parent::__construct('Notification', ... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}

	public static function sendMail(... $args)
	{
		//TODO/
	}
}

?>
