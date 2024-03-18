<img src="https://kekse.biz/github.php?draw&override=github:count2">

# Internals
Since I'm learning PHP **again**, I'm putting some comments into this file,
to give a rough outline of how to implement "correctly"[tm]..

## **Error** vs. **Exception**
* `Error` if a programming error occures (so e.g. unexpected parameter type)
* `Exception` if a user error like wrong `$_GET[]` params occures

## `require_once()`
.. `require_once()` should be the most used function to import other modules.

I'd recommend (here) to `require_once()` in each module for any extension used,
so not relying on a 'bigger'/parent construction or so. Since PHP should not
import modules multiple times (so with `require_once()`), this won't do any harm
to our efficience. 8?

