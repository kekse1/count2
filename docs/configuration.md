<img src="https://kekse.biz/github.php?draw&override=github:count2">

# Differences
All configurations are pure differences.

## Base configuration
You could copy this script etc. to an arbitrary directory in your file system,
and create symlinks to there from many places (in your web root, mostly).

Every such location can then hold it's own base `.json` configuration (differences),
whereas this file (really) needs to be named by the `basename($0, .php).json`!

## Per-host configuration
Since every managed host(name)/domain/override/ is located in a special directory
for all the values etc., they won't only have their own counter value files (beneath
their directories for caching fingerprints, etc.), they also can have (optional)
`.json` config diff files, one for each host.

### TODO
//TODO//

