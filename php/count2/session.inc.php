<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/configuration.inc.php');
require_once(__DIR__ . '/../kekse/environment.inc.php');

class Session extends \kekse\Session
{
//	public $fingerprint = null;
//	public $count = null;

	public function __construct($controller, ... $args)
	{
		parent::__construct($this->controller = $controller, ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	protected function create()
	{
		parent::create();
		$this->configuration->importSchemeFromJSON(KEKSE_COUNT2_JSON_CONFIG);
	}
}

?>
