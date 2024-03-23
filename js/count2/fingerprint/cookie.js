
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
import Fingerprint from './main.js';

const CookieFingerprint = window.Fingerprint.Cookie = class CookieFingerprint extends Fingerprint
{
	constructor(... args)
	{
		return super(CookieFingerprint.type, ... args);
	}

	static get type()
	{
		return 'cookie';
	}
}

export default CookieFingerprint;

//

