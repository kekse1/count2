
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
//TODO/@toString @ reflect
//TODO/timestamp(diff) like php.. w/ here .getRuntime();
//TODO/Reflect.is/was() @ here '.class{Name,Path}()'!
//TODO/'.classPath()' w/ FIRST '\'???
//TODO/import ... @ lib-styles [NEW, AGAIN];
//

const Quant = window.Quant = class Quant
{
	constructor(... args)
	{
		this.session = null;

		this.TIME = Date.now();
		this.ARGS = args;
	}
	
	className()
	{
		return Reflect.is(this);
	}

	classPath()
	{
		return '\\' + Reflect.was(this).join('\\');
	}

	runtime()
	{
		return timestamp(this.TIME);
	}
}

export default Quant;

//
import * as library from './library.js';
window.library = library;

//

