
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
//TODO/@toString @ reflect
//TODO/in php's Quant, does .classPath starts with '\'??
//

const Quant = window.Quant = class Quant
{
	constructor(... args)
	{
		this.session = null;

		this.TIME = timestamp();
		this.ARGS = args;
	}
	
	className()
	{
		return Reflect.is(this);
	}

	classPath()
	{
		return '\\' + Reflect.was(this).reverse().join('\\');
	}

	runtime()
	{
		return timestamp(this.TIME);
	}
}

export default Quant;

//
import library from './library.js';
window.library = library;

//

