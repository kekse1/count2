<img src="https://kekse.biz/github.php?draw&override=github:count2">

# Coding Guidelines
Since I'm learning PHP **again**, I'm putting some comments into this file,
to give a rough outline of how to implement "correctly"[tm]..

//TODO/

## Namespace
Most general abstractions are implemented in my own `kekse` namespace.
Most counter related things should be defined below `kekse\count2`.
Every other module should also be below the `kekse\*` namespace.

## Constants
All need their own `constants.inc.php` files (in all sub-project directories,
including the `kekse` base).

## `Throwable`
They all get logged (but not Warnings), and are shown to the client, too.
Of course, in my own form (not the whole output). //TODO/

## Output and Headers
Please use the functions in the `Quant` instances, as they're 'managed' some way.
Please do **not** use regular `echo`, etc..

Even using `STDOUT` etc. is 'bad' HERE: I defined the three [ `INPUT`, `OUTPUT`, `ERROR` ]
file descriptors for it. It works great in the browser, but also in CLI mode they are the
three [ `stdin`, `stdout`, `stderr` ]!

And if you want to send headers to your HTTP clients, please also do **not** use
regular `header()` function, there's the `set()` (and `get()` or `has()`) functions
in the `Connection` class.

## `Connection`
BTW: I designed this `Connection` **not** to be used statically or so, since my
objections is: some day we could use **many** connections...

## Strings and more Security
There are some functions available at `kekse/security.inc.php`. Also used to
secure up fs paths. And more is yet to come.

BTW: There's also the `kekse/string.inc.php`, e.g. with `str_trim()` to also
remove all binary data (beneath `str_remove_binary()`, etc.).

## TODO
//TODO/
