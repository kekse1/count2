
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */

//
export default Object;

//
Reflect.defineProperty(Object, 'isNull', { value: (... args) => {
	if(args.length === 0) return null;
	for(var i = 0; i < args.length; ++i)
		if(typeof args[i] !== 'object' || args[i] === null) return false;
		else if(Reflect.getPrototypeOf(args[i]) !== null) return false;
	return true;
}});

Reflect.defineProperty(Object, 'isObject', { value: (... args) => {
	if(args.length === 0) return null;
	for(var i = 0; i < args.length; ++i)
		if(typeof args[i] !== 'object' || args[i] === null) return false;
	return true;
}});

//

