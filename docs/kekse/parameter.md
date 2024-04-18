<img src="https://kekse.biz/github.php?draw&override=github:count2">

[**Back to Index**](../kekse.md)

# `Parameter`
Most documentation in this sub directories will be added l8rs..

## Negation (to `false` values)
**BUT** I just wanted to notice, that my `$_GET[]` parameter parser (which
uses - by default - the `$_SERVER['QUERY_STRING']`) supports setting `false`
by using a `!` prefix. That's because parameters without `=` will be set
to `true`, and there's no real counterpart for it (as far as I know).

So using `?param` will set `[param] = true` and `?!param` will set `[param] = false`.

## Deletion
Additionally, my syntax describes, that a parameter with `=` assignment,
but without additional value, will cause a real removal/deletion of this
item/key in the resulting array.

So if `?param=abc&param=`, there'll be **no** key `[param]` in the resulting,
associative array.

