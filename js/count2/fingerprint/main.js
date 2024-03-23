
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
import Quant from '../../kekse/main.js';

//
const Fingerprint = window.Fingerprint = class Fingerprint extends Quant
{
	constructor(... args)
	{
		super(... args);
	}

	static get type()
	{
		return [
			'canvas'
		];
	}

	static create(type = null, ... args)
	{
	}
}

export default Fingerprint;

//

