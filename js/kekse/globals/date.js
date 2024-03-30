
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */
//
export default Date;

//
Reflect.defineProperty(window, 'timestamp', { value: (_diff) => {
	var result = Date.now();

	if(Number.isInt(_diff))
	{
		result -= _diff;
	}
	
	return result;
}});

