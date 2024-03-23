
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
import kekse from '../kekse/main.js';
import Controller from './controller.js';

//
const count2 = window.count2 = class Count2
{
	/*constructor(... args)
	{
		//
	}*/

	static onDOMContentLoaded(_event)
	{
		window.count2.controller = new Controller();
	}
}

//
Object.assign(count2, {
	Controller,
	kekse
});

//
export default count2;

//
window.addEventListener('DOMContentLoaded',
	count2.onDOMContentLoaded, {
		passive: true,
		once: true });

//

