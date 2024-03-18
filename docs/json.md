<img src="https://kekse.biz/github.php?draw&override=github:count2">

# JSON data (vectors)
Hard coded stuff is bad.. so this is it.

## Configuration JSON
At first, we use the `basename($0, '.js').json` in the same directory where the `count2.php` is
(so you could put a symlink from many directories, so each with own configuration (diffs) ;-)

This will relocate the default settings for a 'counter instance'. But if you use keys there
which don't exist in the 'vector data structs' (see next section in here), an error will occure.

## JSON 'vector' data
The other `.json` in the `json/` directory define some (more/less internal) structure, some kind
of vectors for different purposes, mostly for automatic checking the validity of the parameters.

|          Path | Description                                                                |
| ------------: | :------------------------------------------------------------------------- |
| `config.json` | All configurable parameters                                                |
| `getopt.json` | Command line parameters in the CLI mode                                    |
| `param.json`  | `$_GET[]` parameters possible when called by an HTTPD (so via Web Browser) |

