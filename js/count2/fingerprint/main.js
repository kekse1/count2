
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
//import Quant from '../../kekse/main.js';

//
const Fingerprint = window.Fingerprint = class Fingerprint //extends Quant
{
	constructor(type, ... args)
	{
		if(type = Fingerprint.checkType(type))
		{
			this.type = type;
		}
		else
		{
			throw new Error('Invalid fingerprint type [ ' + Fingerprint.type.join(', ') + ' ]');
		}

		return super(... args);
	}

	static get type()
	{
		return [
			'canvas',
			'cookie',
			'webgl'
		];
	}

	static checkType(type)
	{
		if(!String.isString(type, false)) return null;
		if(Fingerprint.type.includes(type = type.toLowerCase()))
			return type;
		return null;
	}
}

export default Fingerprint;

//

