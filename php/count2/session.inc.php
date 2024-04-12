<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once(__DIR__ . '/../kekse/configuration.inc.php');
require_once(__DIR__ . '/../kekse/environment.inc.php');
require_once(__DIR__ . '/../kekse/filesystem.inc.php');

class Session extends \kekse\Session
{
	public $directory = null;

	private $path = null;

	public function __construct($controller, ... $args)
	{
		parent::__construct($controller, ... $args);
		$this->init();
	}

	private function init()
	{
		$this->mapImports();
		$this->setPath();
	}

	private function setPath()
	{
		if($this->configuration->has('path', true))
		{
			$this->path = $this->configuration->get('path');
		}
		else
		{
			$this->path = $this->environment->file['base'];
		}

		$this->directory = new \kekse\FileSystem($this, $this->path,
			false, true, $this->configuration->get('dir'));

		return $this->path;
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	private function mapImports()
	{
		$this->configuration->importSchemeFromJSON(KEKSE_COUNT2_JSON_CONFIG, true, true);

		if(isset($this->parameter))
		{
			$this->parameter->importSchemeFromJSON(KEKSE_COUNT2_JSON_PARAM, true, true);
			$this->parameter->import();
		}
	}
}

?>
