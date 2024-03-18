<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

namespace kekse\count2;

require_once('ext/quant.inc.php');

class HTTP extends \kekse\Quant
{
	//private $headerSent = false;//TODO/list of all sent headers (keys/values parsed);
	private $typeSent = false;
	private $lengthSent = false;

	public function __construct(... $args)
	{
		parent::__construct('HTTP', ... $args);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function sendTypeHeader($type)
	{
		if(!is_string($type)) throw new \Error('Invalid $type argument');
		else if(str_starts_with($type, 'Content-Type:')) $type = substr($type, 13);
		if(!($type = trim(\kekse\removeBinary($type, false)))) throw new \Error('Invalid $type argument');
		$this->sendHeader('Content-Type', $type);
		$this->typeSent = true;
		return $type;
	}

	public function sendLengthHeader($length)
	{
		if(is_int($length)) $length = (string)$length;
		else if(!is_string($length)) throw new \Error('Invalid $length argument');
		else if(str_starts_with($length, 'Content-Length')) $length = substr($length, 14);
		if(!($length = trim(\kekse\removeBinary($length, false)))) throw new \Error('Invalid $length argument');
		$this->sendHeader('Content-Length', $length);
		$this->lengthSent = true;
		return $type;
	}

	public function sendHeader($string_or_key, $value = null)
	{
		if(!is_string($string_or_key))
		{
			throw new \Error('Invalid $string_or_key argument');
		}
		else
		{
			$string_or_key = trim(\kekse\removeBinary($string_or_key, false));
		}

		if(is_string($value))
		{
			$value = trim(\kekse\removeBinary($value));
			$string_or_key .= ': ' . $value;
		}
		else
		{
			header($string_or_key);
			return $string_or_key;
		}

		header($string_or_key);
		return $string_or_key;
	}
}

?>
