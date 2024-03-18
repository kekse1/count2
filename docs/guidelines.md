<img src="https://kekse.biz/github.php?draw&override=github:count2">

# Coding Guidelines
Since I'm learning PHP **again**, I'm putting some comments into this file,
to give a rough outline of how to implement "correctly"[tm]..

## Namespace
Most general abstractions are implemented in my own `kekse` namespace.
Most counter related things should be defined below `kekse\count2`.

## **Error** vs. **Exception**

* `Error` if a programming error occures (so e.g. unexpected parameter type)
* `Exception` if a user error like wrong `$_GET[]` params occures

> [!IMPORTANT]
> I think you'd like to write `\Exception` etc. (so with the backslash `\`),
> since most times we're working in the `namespace \kekse`.

## String security
There are some functions available at `mod/security.inc.php`. The first one,
marked bold, is the most recommended one:

* **`Security::checkString($string, $removeBinary = true, $trim = true)`**
* `Security::isValidString($value)`
* `Security::removeBinary($string, $null = false, $removeSpaces = false)`
* `Security::removeWhiteSpaces($string, $null = false)`

Therefore there's also the constant `KEKSE_LIMIT_STRING = 255`.

## Documentation
//TODO//

