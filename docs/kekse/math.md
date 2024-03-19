<img src="https://kekse.biz/github.php?draw&override=github:count2">

# `math`
//TODO/

## Implementation
* [`renderSize($bytes, $precision = 2, $base = 1024)`](#renderSize)
* [`unit($string, $float = false, $unit = null, $fix = true)`](#unit)
* [`getIndex($index, $length)`](#getIndex)
* `px2pt($value)`
* `pt2px($value)`

### `renderSize($bytes, $precision = 2, $base = 1024)`
Converts an amount of `$bytes` to it's string form with unit suffix;
either base 1000 or 1024 (both got their own unit names).

### `unit($string, $double = null, $unit = null, $fix = true)`
Extracts a value and/or unit from a string, filtering out only the necessary characters (so
also removing spaces and bytecodes, etc.).

If `$double` is `null`, it'll be auto-decided whether to encode `int` or `double`.

If `$unit` is a unit string (currently only `px` and `pt` supported), this will convert the
input value, if necessary (and changes the resulting array a bit). Only if `$double` is the
`null`, an optional type cast is being done, whether it's useful or not.

If the `$fix` parameter is enabled, the `$result['unit']` will be cleared if the `['value']`
is zero.

### `getIndex($index, $length)`
For arrays, strings, etc.. calculates your correct index!

##
//TODO//

