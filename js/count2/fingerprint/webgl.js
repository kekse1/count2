
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
import Fingerprint from './main.js';

const WebGLFingerprint = window.Fingerprint.WebGL = class WebGLFingerprint extends Fingerprint
{
	constructor(... args)
	{
		super(WebGLFingerprint.type, ... args);
	}

	static get type()
	{
		return 'webgl';
	}
}

export default WebGLFingerprint;

//

