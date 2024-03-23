
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
import Session from './session.js';
import Fingerprint from './fingerprint/main.js';

//
class Controller
{
	constructor(... args)
	{
		this.session = new Session(this, ... args);
	}
}

export default Controller;

//

