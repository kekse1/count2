<?php

	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
namespace kekse;

//
require_once(__DIR__ . '/numeric.php');

//
class UUID
{
	private static $separator = '-';
	private static $scheme = [ 8, 4, 4, 4, 12 ];
	private static $radix = 16;

	protected static function getLength($separator = null)
	{
		if(!is_string($separator) || $separator === '')
		{
			$separator = self::$separator;
		}
		
		$result = 0;
		$scheme = self::$scheme;
		$sepLen = strlen($separator);
		
		foreach($scheme as $sub)
		{
			$result += $sub + $sepLen;
		}
		
		return ($result - $sepLen);
	}
	
	public static function random($separator = null)
	{
throw new \Error('TODO');
		if(!is_string($separator) || $separator === '')
		{
			$separator = self::$separator;
		}
		
		$scheme = self::$scheme;
		$result = '';
	}
	
	public static function isValid($uuid)
	{
throw new \Error('TODO');
		if(!is_string($uuid) || $uuid === '')
		{
			return false;
		}
		
		$len = strlen($uuid);
	}
}

class ID extends UUID
{
	private static $radix = 36;
	private static $separator = '/';
	
	public static function random(... $args)
	{
		return (parent::random() . self::getRest(true));
	}
	
	public static function isValid($id)
	{
		if(!is_string($id) || $id === '')
		{
			return false;
		}
		
		$uuidLength = parent::getLength();
		$separator = self::$separator;
		
		if(strlen($id) <= ($uuidLength + strlen($separator)))
		{
			return false;
		}
		
		return parent::isValid(substr($id, 0, $uuidLength));
	}
	
	private static function getRest($sep = true)
	{
		$result = Number::renderInt(timestamp(), self::$radix);
		
		if($sep)
		{
			$result = self::$separator . $result;
		}
		
		return $result;
	}
}

//
//debug/test/!
var_dump(UUID::random());
exit(123);
//









//original from the lib.js/v4/:

/*

//
const DEFAULT_CRYPTO = false;
const DEFAULT_SEP = '/';

//
const uuid = global.uuid = (... _args) => uuid.random(... _args);
const id = global.id = (... _args) => id.random(... _args);
id.uuid = uuid;

export default id;
export { uuid, id };

//
id.random = id.create = (_crypto = DEFAULT_CRYPTO, _radix = 36, _sep = DEFAULT_SEP) => {
	//
	if(typeof _crypto !== 'boolean')
	{
		_crypto = DEFAULT_CRYPTO;
	}

	if(! Number.isInt(_radix))
	{
		_radix = 36;
	}

	if(! String.isString(_sep, false))
	{
		_sep = DEFAULT_SEP;
	}

	//
	var result = uuid.random(_crypto);
	return (result + _sep + Date.now().toString(_radix));
};

id.isID = (_string) => {
	if(! String.isString(_string, 37))
	{
		return false;
	}

	return uuid.isUUID(_string.substr(0, 36));
};

uuid.isUUID = (_string) => {
	if(! String.isString(_string, 36))
	{
		return false;
	}
	else if(_string.length > 36)
	{
		return false;
	}

	const scheme = uuid.scheme;
	const symbol = _string[scheme[0]];

	if(typeof symbol !== 'string')
	{
		return false;
	}
	else
	{
		const p = parseInt(symbol, uuid.radix);

		if(! isNaN(p))
		{
			p = false;
		}
	}

	for(var i = 1, mul = scheme[0]; i < scheme.length - 1; ++i)
	{
		if(_string[(mul += scheme[i]) + i] !== symbol)
		{
			return false;
		}
	}

	var string = '';

	for(var i = 0, mul = 0; i < scheme.length; ++i)
	{
		string += _string.substr(mul + i, scheme[i]);
		mul += scheme[i];
	}

	const isValidRadixChar = (_char, _radix = uuid.radix) => {
		return !isNaN(parseInt(_char, _radix));
	};

	for(var i = 0; i < string.length; ++i)
	{
		if(! isValidRadixChar(string[i]))
		{
			return false;
		}
	}

	return true;
};

//
uuid.random = uuid.create = (_crypto = DEFAULT_CRYPTO, ... _args) => {
	if(typeof _crypto !== 'boolean')
	{
		_crypto = DEFAULT_CRYPTO;
	}

	if(_crypto && typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function')
	{
		return crypto.randomUUID(... _args);
	}

	var result = '';
	const scheme = uuid.scheme;
	const alpha = uuid.alphabet;
	const sep = uuid.sep;

	for(var i = 0; i < scheme.length; ++i)
	{
		for(var j = 0; j < scheme[i]; ++j)
		{
			result += alpha[Math.random.int(alpha.length, 0, false)];
		}

		result += sep;
	}

	return result.slice(0, -1);
};

//
Reflect.defineProperty(uuid, 'alphabet', { get: () => {
	return '0123456789abcdef';
}});

Reflect.defineProperty(uuid, 'scheme', { get: () => {
	return [ 8, 4, 4, 4, 12 ];
}});

Reflect.defineProperty(uuid, 'radix', { get: () => { return 16; }});
Reflect.defineProperty(uuid, 'sep', { get: () => { return '-'; }});
 */

//



//
?>
