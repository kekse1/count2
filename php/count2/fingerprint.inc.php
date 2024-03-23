<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/main.inc.php');

class Fingerprint extends \kekse\Quant
{
	public $fingerprint;

	public function __construct($session, $fingerprint, ... $args)
	{
		$this->session = $session;
		$this->fingerprint = $fingerprint;
		return parent::__construct(... $args);
	}

	public function __destruct()
	{
		return parent::__destruct();
	}
}

?>
