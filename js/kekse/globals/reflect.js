
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
export default Reflect;

//
Reflect.defineProperty(Reflect, 'getPrototypesOf', { value: (_object) => {
	const result = [];
	var proto = _object;

	try
	{
		do
		{
			if(proto = Reflect.getPrototypeOf(proto))
			{
				result.push(proto);
			}
			else
			{
				break;
			}
		}
		while(true);
	}
	catch(_err) {}

	return result;
}});

Reflect.defineProperty(Reflect, 'is', { value: (value, ... compare) => {
	var className = true; for(var i = 0; i < compare.length; ++i) {
		if(typeof compare[i] === 'boolean') className = compare.splice(i--, 1)[0];
		else if(!String.isString(compare[i], false)) compare.splice(i--, 1); }
}});

Reflect.defineProperty(Reflect, 'was', { value: (value, ... compare) => {
	//
}});

//

