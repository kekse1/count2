<img src="https://kekse.biz/github.php?draw&override=github:count2">

[**Back to Index**](../kekse.md)

# `Parameter`
Most documentation in this sub directories will be added l8rs..

## Negation (to `false` values)
**BUT** I just wanted to notice, that my `$_GET[]` parameter parser (which
uses - by default - the `$_SERVER['QUERY_STRING']`) supports setting `false`
by using a `!` prefix. That's because parameters without `=` will be set
to `true`, and there's no real counterpart for it (as far as I know).

