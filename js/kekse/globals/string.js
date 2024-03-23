
	/* Copyright (c) Sebastian Kucharczyk <kuchen@kekse.biz>
	 * https://kekse.biz/ https://github.com/kekse1/count2/ */
//
export default String;

//
Reflect.defineProperty(String, 'isString', { value: (item, empty = true) => {
	if(typeof item !== 'string') return false;
	else if(typeof empty === 'boolean') empty = (empty || (item.length > 0));
	else if(!Number.isInt(empty)) return true;
	else if(empty < 1) return true;
	return (item.length >= empty);
}});

//
const _at = String.prototype.at;

Reflect.defineProperty(String.prototype, 'at', { value: function(index, comparison, caseSensitive = true)
{
	if(arguments.length <= 1)
	{
		return _at.call(this, index);
	}
	else if(typeof caseSensitive !== 'boolean')
	{
		caseSensitive = true;
	}

	if(!String.isString(comparison, false))
	{
		throw new Error('Invalid comparison argument');
	}
	else if(this.length < comparison.length)
	{
		return false;
	}

	var needle = this.substr(index, comparison.length);

	if(!caseSensitive)
	{
		needle = needle.toLowerCase();
		comparison = comparison.toLowerCase();
	}

	return (needle === comparison);
}});

//

