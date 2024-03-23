<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//TODO/haengt stark mit 'event.inc.php' zusammen!! vice versa. @ rpc-styles..

namespace kekse\count2;

class Notification extends \kekse\Quant
{
	public function __construct($session, ... $args)
	{
		$this->session = $session;
		return parent::__construct(... $args);
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
