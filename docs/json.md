<img src="https://kekse.biz/github.php?draw&override=github:count2">

# Definitions
Hard coded stuff is bad.. so this is it. Intended for _automatic_ checking of all
[**`Map`**](php/kekse/map.inc.php) related stuff (e.g. [`Configuration`](php/kekse/configuration.inc.php),
[`Parameter`](php/kekse/parameter.inc.php), .. maybe more).

## `kekse`
There's the 'meta' scheme defining any [`Map`](php/kekse/map.inc.php) **scheme** itself:

* [`map.json`](json/kekse/map.json)

## `count2`
Here are the schemes for this counter itself.

| File                                     | Description                                                  |
| ---------------------------------------: | :----------------------------------------------------------- |
| [`config.json`](json/count2/config.json) | The configuration itself                                     |
| [`getopt.json`](json/count2/getopt.json) | Command line parameters in CLI mode (TODO)                   |
| [`param.json`](json/count2/param.json)   | Possible `$_GET[]` parameters (when called via HTTP/Browser) |

