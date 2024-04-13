
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */
//
export default Date;

//
Reflect.defineProperty(window, 'timestamp', { value: (diff) => {
	var result = Date.now();

	if(Number.isInt(diff))
	{
		result -= diff;
	}
	
	return result;
}});

