<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

class Fingerprint extends \kekse\Quant
{
	public $fingerprint;

	public function __construct($session, $fingerprint, ... $args)
	{
		parent::__construct($session, ... $args);
		$this->fingerprint = $fingerprint;
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}

?>
