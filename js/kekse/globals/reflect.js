
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
				result.push(proto);
			else
				break;
		} while(true);
	}
	catch(_err) {}
	return result;
}});

Reflect.defineProperty(Reflect, 'is', { value: (object, ... compare) => {
	var className = true; for(var i = 0; i < compare.length; ++i) {
		if(typeof compare[i] === 'boolean') className = compare.splice(i--, 1)[0];
		else if(!String.isString(compare[i], false)) compare.splice(i--, 1); }
	if(compare.length > 0) compare = compare.unique();
	const tryConstructorName = () => {
		try { return object.constructor.name; } catch(_err) { return ''; }};
	const tryClassName = () => {
		try { return object.name; } catch(_err) { return ''; }};
	var result;
	if(typeof object === 'undefined') result = 'undefined';
	else if(object === null) result = 'null';
	else if(Object.isNull(object)) result = 'Object[null]';
	else result = tryConstructorName();
	if(!result && className) result = tryClassName();
	if(!result && compare.length > 0) return false;
	else if(compare.length === 0) return result;
	return compare.includes(result);
}});

Reflect.defineProperty(Reflect, 'was', { value: (object, ... compare) => {
	for(var i = compare.length - 1; i >= 0; --i)
		if(!String.isString(compare[i], false)) compare.splice(i);
	if(compare.length > 0) compare = compare.unique();
	const result = []; var name; const prototypes = Reflect.getPrototypesOf(object);
	if(prototypes.length === 0) return (compare.length === 0 ? [] : false);
	for(var i = 0, j = 0; i < prototypes.length; ++i)
		if(name = Reflect.is(prototypes[i]))
			result[j++] = name;
	if(compare.length === 0) return result;
	for(var i = 0; i < result.length; ++i)
		if(compare.includes(result[i])) return true;
	return false;
}});

//

