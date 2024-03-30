<img src="https://kekse.biz/github.php?draw&override=github:count2">

# Coding Guidelines
Since I'm learning PHP **again**, I'm putting some comments into this file,
to give a rough outline of how to implement "correctly"[tm]..

//TODO/

## Namespace
Most general abstractions are implemented in my own `kekse` namespace.
Most counter related things should be defined below `kekse\count2`.

## **Error** vs. **Exception**

* `Error` if a programming error occures (so e.g. unexpected parameter type)
* `Exception` if a user error like wrong `$_GET[]` params occures

I wrote it this way: if it's an instance of **`\Error`**, the whole process
will be stopped (return code `255`).

> [!IMPORTANT]
> I think you'd like to write `\Exception` etc. (so with the backslash `\`),
> since most times we're working in the `namespace \kekse`.

I'm handling those ones via the [`logger`](kekse/logger.md) module, which will,
one the one hand, write anything into log files, but is also going to create
nice outputs, both in console and browser.

## String security
There are some functions available at `kekse/security.inc.php`. Also used to
secure up fs paths. And more is yet to come.

Therefore there's also the constant `KEKSE_LIMIT_STRING = 255`, which is heavily
used e.g. to avoid too long `$_GET[]` parameters, and more.

## TODO
//TODO/

