
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
const globals = {};

//
import date from './date.js';
import number from './number.js';
import reflect from './reflect.js';
import string from './string.js';

//
Object.assign(globals, {
	date,
	number,
	reflect,
	string
});

//
export default globals;

//

