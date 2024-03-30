
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
import Fingerprint from './main.js';

const CanvasFingerprint = window.Fingerprint.Canvas = class CanvasFingerprint extends Fingerprint
{
	constructor(... args)
	{
		return super(CanvasFingerprint.type, ... args);
	}

	static get type()
	{
		return 'canvas';
	}
}

export default CanvasFingerprint;

//

