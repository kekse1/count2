<img src="https://kekse.biz/github.php?draw&override=github:count2">

[**Back to Index**](../kekse.md)

# `Number`
Some functions are maybe of interest..

## Radix/base conversion
Here I think about the encoding of counting values (@ [`count2`](../count2.md)) in the file system:
instead of storing 20 characters in a file, encoding the values in binary radix (256) we need only
8 full bytes.

Additionally, I really **hope** it's more performant to encode/decode numerical values directly,
when they still are Integer/Double values.. with PHP's default function for radix conversions
[`base_convert()`](https://php.net/base_convert) you need to input a string with numerical
content, which is (at least) one more step (the radix/base conversion only really works with
numbers, not strings..).

I also support a radix/base from **-257** up to **256** (the default is only **2** to **36**),
and also real **alphabet** strings, so you can decide about the real character mappings.

Etc.. pp.

## Class implementation
* **`Number::cast($value)`**
* **`Number::isNumber($value, $radix = null, $double = true)`**
* **`Number::isNumeric($value, $radix = KEKSE_NUMERIC_RADIX, $double = null)`**
* **`Number::parseInt($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`**
* **`Number::parseDouble($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`**
* `Number::parseFloat($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`
* **`Number::parseNumber($string, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`**
* **`Number::parse($string, $radix = KEKSE_NUMERIC_RADIX, $double = null, $throw = KEKSE_NUMERIC_THROW)`**
* **`Number::renderInt($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`**
* **`Number::renderDouble($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`**
* `Number::renderFloat($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`
* **`Number::renderNumber($value, $radix = KEKSE_NUMERIC_RADIX, $throw = KEKSE_NUMERIC_THROW)`**
* **`Number::render($value, $radix = KEKSE_NUMERIC_RADIX, $double = null, $throw = KEKSE_NUMERIC_THROW)`**
* **`Number::isRadix($radix)`**
* **`Number::reverseRadix($radix)`**
* **`Number::positiveRadix($radix)`**
* **`Number::negativeRadix($radix)`**
* **`Number::alphabet($radix = KEKSE_NUMERIC_RADIX)`**

## Functions
These are just aliases to the static class functions (above):

* `is_numeric($value, $radix = KEKSE_NUMERIC_RADIX, $double = null)`
* `is_number($value, $radix = null, $double = true)`

//TODO/

