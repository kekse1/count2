
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
export default Array;

//
Reflect.defineProperty(Array.prototype, 'unique', { value: function()
{
	return Array.from(new Set(this.valueOf()));
}});

//

