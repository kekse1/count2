
/*

	here's the 'radix.js' code from my javascript library part (https://github.com/kekse1/v4/):


radix.features = (_radix, _throw = DEFAULT_THROW) => {
	const alpha = alphabet.getAlphabet(_radix, _throw);
	if(!alpha) return null; const result = Object.create(null);
	result.alphabet = alpha;
	result.radix = _radix;
	result.length = result.alphabet.length;
	result.bytes = (radix.toPositive(_radix) === 256);
	result.bigint = (!result.bytes && !alpha.includes('n'));
	result.float = (!result.bytes && !alpha.includes('.'));
	result.negative = (!result.bytes && !alpha.includes('-'));
	result.positive = (!result.bytes && !alpha.includes('+'));
	result.sign = (!result.bytes && (result.negative || result.positive));
	result.lower = alpha.isLowerCase;
	result.upper = (alpha.isUpperCase && !alpha.isLowerCase);
	result.exp = null;
	return result; };

//
radix.render = (_value, _radix = DEFAULT_RADIX, _bigint_suffix = DEFAULT_BIGINT_SUFFIX, _float = true, _throw = DEFAULT_THROW) => {
	if(typeof _value === 'bigint') { if(typeof _bigint_suffix === 'boolean') _bigint_suffix = (_bigint_suffix ? 'n' : '');
		else if(typeof _bigint_suffix !== 'string') _bigint_suffix = 'n'; }
	if(!numeric(_value)) return (typeof _value === 'string' ? _value : (_throw ? error('Invalid % argument', null, '_value') : null));
	const features = radix.features(_radix, _throw); if(!features) return (_throw ? error('Invalid % argument', null, '_radix') : undefined);
	else if(!features.bigint) _bigint_suffix = ''; const bigint = (typeof _value === 'bigint'); var rest = Math.abs(_value);
	_radix = features.alphabet.length; if(bigint) _radix = BigInt(_radix); if(rest === 0 || rest === 0n) return (features.alphabet[0] + (bigint ? _bigint_suffix : ''));
	var result = ''; var idx; while(rest >= _radix) { result = features.alphabet[Math._floor(Number(rest % _radix))] + result;
	rest /= _radix; } if(rest > (bigint ? 0n : 0)) result = features.alphabet[Math._floor(Number(rest))] + result; if(!bigint && _float) { rest = (Math.abs(_value) % 1);
	if(rest > 0) { var zero = 0, started = false; while((rest % 1) !== 0) { rest *= _radix; if(!started && rest < 1) ++zero; else started = true; }
	result += ('.' + String.repeat(zero, features.alphabet[0]) + radix.render(rest, _radix, null, true, _throw)); }}
	return ((_value < 0 ? '-' : '') + result + ((bigint && features.bigint) ? _bigint_suffix : '')); };


 */
