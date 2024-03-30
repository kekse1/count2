
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
import Fingerprint from './main.js';

const HeaderFingerprint = window.Fingerprint.Header = class HeaderFingerprint extends Fingerprint
{
	constructor(... args)
	{
		super(HeaderFingerprint.type, ... args);
	}

	static get type()
	{
		return 'header';
	}
}

export default HeaderFingerprint;

//

