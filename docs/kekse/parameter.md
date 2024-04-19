<img src="https://kekse.biz/github.php?draw&override=github:count2">

[**Back to Index**](../kekse.md)

# `Parameter`
Most documentation in this sub directories will be added l8rs..

## Boolean negation
**BUT** I just wanted to notice, that my `$_GET[]` parameter parser (which
uses - by default - the `$_SERVER['QUERY_STRING']`) supports setting `false`
by using a `!` prefix. That's because parameters without `=` will be set
to `true` (but empty strings), and there's no real counterpart for it, as
the opposite to `?param` as `true` (as far as I know).

So using `?param` will set `[param] = true` and `?!param` will set `[param] = false`.

Any other usage of the `!` prefix is possible (so when putting concrete values),
so this as **first** character will be removed from the key.

> [!TIP]
> If you really want a key to begin with `!`, you need to write it twice.

