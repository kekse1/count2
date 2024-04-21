<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse\count2;

//
class Fingerprint extends \kekse\Quant
{
	public $fingerprint;
	private $headers;

	public function __construct($session, $fingerprint, ... $args)
	{
		parent::__construct($session, ... $args);
		$this->fingerprint = $fingerprint;
		$this->extractHeaderInformation();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	private function extractHeaderInformation()
	{
		var_dump($this->session->environment->headers);
die("TODO: HDR @ fingerprint.php");
	}
}

?>
